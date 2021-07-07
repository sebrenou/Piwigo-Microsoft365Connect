<?php
defined('M365CONNECT_PATH') or die('Hacking attempt!');

function m365connect_admin_plugin_menu_links($menu) 
{
  $menu[] = array(
    'NAME' => 'Social Connect',
    'URL' => M365CONNECT_ADMIN,
    );
  return $menu;
}

function m365connect_user_list_columns($aColumns)
{
  $aColumns[] ='m365connect_id';
  return $aColumns;
}

function m365connect_user_list_render($output)
{
  global $aColumns, $hybridauth_conf;
  
  $m365connect_col = array_search('m365connect_id', $aColumns);
  $username_col = array_search('username', $aColumns);
  
  foreach ($output['aaData'] as &$user)
  {
    if (!empty($user[$m365connect_col]))
    {
      list($provider) = explode('---', $user[$m365connect_col], 2);
      $user[$username_col] = '<span class="m365connect_16px '.strtolower($provider).'" title="'. $hybridauth_conf['providers'][$provider]['name'] .'"></span> 
' . $user[$username_col];
    }
    
    unset($user[$m365connect_col]);
  }
  unset($user);
  
  return $output;
}

function m365connect_user_list()
{
  global $template, $page;
  
  if ($page['page'] != 'user_list')
  {
    return;
  }
  
}
