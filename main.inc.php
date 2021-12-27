<?php 
/*
Plugin Name: Microsoft 365 Connect
Version: auto
Description: Provides a way to sign in your gallery with your Microsoft 365 account. (original plugin : piwigo-social-connect)
Plugin URI: auto
Author: Sébastien Renou
Author URI: https://solutions-opensource-pour-collectivites.fr
Has Settings: true
*/  

defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

if (basename(dirname(__FILE__)) != 'm365connect')
{
  add_event_handler('init', 'ouath_error');
  function ouath_error()
  {
    global $page;
    $page['errors'][] = 'Microsoft 365 Connect folder name is incorrect, uninstall the plugin and rename it to "m365connect"';
  }
  return;
}

define('M365CONNECT_PATH' ,  PHPWG_PLUGINS_PATH . 'm365connect/');
define('M365CONNECT_ADMIN',  get_root_url() . 'admin.php?page=plugin-m365connect');
define('M365CONNECT_CONFIG', PWG_LOCAL_DIR . 'config/hybridauth.inc.php');
define('M365CONNECT_PUBLIC', get_absolute_root_url() . ltrim(M365CONNECT_PATH,'./') . 'include/hybridauth/');

include_once(M365CONNECT_PATH . 'include/functions.inc.php');


// try to load hybridauth config
global $hybridauth_conf;
load_hybridauth_conf();

add_event_handler('init', 'm365connect_init');

if (defined('IN_ADMIN'))
{
  add_event_handler('get_admin_plugin_menu_links', 'm365connect_admin_plugin_menu_links');  
  include_once(M365CONNECT_PATH . 'include/admin_events.inc.php');
}
else if (!empty($hybridauth_conf) and function_exists('curl_init'))
{
  add_event_handler('loc_begin_identification', 'm365connect_begin_identification');  
  add_event_handler('loc_after_page_header', 'm365connect_page_header');    
  include_once(M365CONNECT_PATH . 'include/public_events.inc.php');
}


/**
 * plugin initialization
 */
function m365connect_init()
{
  global $conf, $page, $hybridauth_conf, $template;
  
  load_language('plugin.lang', M365CONNECT_PATH);
  
  $conf['m365connect'] = safe_unserialize($conf['m365connect']);
  
  // check config
  if (defined('IN_ADMIN'))
  {
    if (empty($hybridauth_conf) and strpos(@$_GET['page'],'plugin-m365connect')===false)
    {
      $page['warnings'][] = '<a href="'.M365CONNECT_ADMIN.'">'.l10n('Microsoft 365 Connect: You need to configure the credentials').'</a>';
    }
    if (!function_exists('curl_init'))
    {
      $page['warnings'][] = l10n('Microsoft 365 Connect: PHP Curl extension is needed');
    }
  }  
}
