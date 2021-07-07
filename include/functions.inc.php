<?php
defined('M365CONNECT_PATH') or die('Hacking attempt!');

function load_hybridauth_conf()
{
  global $hybridauth_conf, $conf;
  
  if (file_exists(PHPWG_ROOT_PATH.M365CONNECT_CONFIG))
  {
    $hybridauth_conf = include(PHPWG_ROOT_PATH.M365CONNECT_CONFIG);
    $hybridauth_conf['base_url'] = M365CONNECT_PUBLIC;
    if (!empty($conf['m365connect_debug_file']))
    {
      $hybridauth_conf['debug_mode'] = true;
      $hybridauth_conf['debug_file'] = $conf['m365connect_debug_file'];
    }
    return true;
  }
  else
  {
    return false;
  }
}

function m365connect_assign_template_vars($u_redirect=null)
{
  global $template, $conf, $hybridauth_conf, $user;
  
  $conf['m365connect']['include_common_template'] = true;
  
  if ($template->get_template_vars('M365CONNECT') == null)
  {
    if (!empty($user['m365connect_id']))
    {
      list($provider, $identifier) = explode('---', $user['m365connect_id'], 2);
    }
    
    $template->assign('M365CONNECT', array(
      'conf' => $conf['m365connect'],
      'u_login' => get_root_url() . M365CONNECT_PATH . 'auth.php?provider=',
      'providers' => $hybridauth_conf['providers'],
      'key' => get_ephemeral_key(0),
      ));
    $template->assign(array(
      'M365CONNECT_PATH' => M365CONNECT_PATH,
      'M365CONNECT_ABS_PATH' => realpath(M365CONNECT_PATH) . '/',
      'ABS_ROOT_URL' => rtrim(get_gallery_home_url(), '/') . '/',
      ));
  }
  
  if (isset($u_redirect))
  {
    $template->append('M365CONNECT', compact('u_redirect'), true);
  }
}

function get_servername($with_port=false)
{
  $scheme = 'http';
  if ( (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 )
  {
    $scheme = 'https';
  }
  
  $servername = $scheme . '://' . $_SERVER['HTTP_HOST'];
  if ($with_port)
  {
    $servername.= ':' . $_SERVER['SERVER_PORT'];
  }
    
  return $servername;
}