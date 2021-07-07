<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

define('PHPWG_ROOT_PATH', '../../');
include_once(PHPWG_ROOT_PATH.'include/common.inc.php');

require_once('include/hybridauth/src/autoload.php');
use Hybridauth\Hybridauth;  
use Hybridauth\Provider;  

$provider = 'MicrosoftGraph' ;

$config = [
 
  'callback' => str_replace('http://','https://',get_absolute_root_url()).'plugins/m365connect/auth.php', 
  'keys' => [      
      'id' => $hybridauth_conf['providers'][$provider]['keys']['key'],
      'secret' => $hybridauth_conf['providers'][$provider]['keys']['secret']     
  ],
  'tenant' => $hybridauth_conf['providers'][$provider]['keys']['tenant'],
];

try {

  $adapter = new Provider\MicrosoftGraph($config);

  $adapter->authenticate();
  $isConnected = $adapter->isConnected();
  $userProfile = $adapter->getUserProfile();
  $user_email = $userProfile->email ;

  if ($isConnected)
  {   
   
      // check is already registered
      $query = '
      SELECT * FROM ' . USERS_TABLE . '
      WHERE mail_address = \'' . pwg_db_real_escape_string($user_email) . '\'
      ORDER BY username ASC
      LIMIT 1
      ;';
      $result = pwg_query($query);

      // registered : log_user and redirect
      if (pwg_db_num_rows($result))
      {         

        list($user_id) = pwg_db_fetch_row($result);       
        log_user($user_id, false);
        
        $redirect_to = 'default';       
        $template->assign('REDIRECT_TO', $redirect_to);
      }  
      // not registered : redirect to register page
      else
      {
        $_SESSION['page_errors'][] = l10n('User not found') ;

        if ($conf['allow_user_registration'])
        {
          pwg_set_session_var('m365connect_new_user', $user_email);
          $redirect_to = 'register';
        }
        else
        {
          $_SESSION['page_errors'][] = l10n('Sorry, new registrations are blocked on this gallery.');
          $adapter->disconnect();
          $redirect_to = 'identification';
        }
      }

      $template->assign('REDIRECT_TO', $redirect_to);

  } else {
    $_SESSION['page_errors'][] = l10n('Authentication canceled');
  }

}
catch(\Exception $e){
    echo 'Oops, we ran into an issue! ' . $e->getMessage();
}


$template->assign(array(
    'GALLERY_TITLE' => $conf['gallery_title'],
    'CONTENT_ENCODING' => get_pwg_charset(),
    'U_HOME' => get_gallery_home_url(),    
    'M365CONNECT_PATH' => M365CONNECT_PATH,
    'PROVIDER' => $hybridauth_conf['providers']['MicrosoftGraph']['name'],
    'SELF_URL' => M365CONNECT_PATH . 'auth.php?provider='.$provider,
    ));
  

$template->set_filename('index', realpath(M365CONNECT_PATH . 'template/auth.tpl'));
$template->pparse('index');