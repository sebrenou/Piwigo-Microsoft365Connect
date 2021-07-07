<?php
defined('M365CONNECT_PATH') or die('Hacking attempt!');

/**
 * identification page
 */
function m365connect_begin_identification()
{
  global $template, $conf, $hybridauth_conf;
  
  if ($hybridauth_conf['enabled'] == 0)
  {
    return;
  }

  $u_redirect = !empty($_GET['redirect']) ? urldecode($_GET['redirect']) : get_gallery_home_url();
  m365connect_assign_template_vars($u_redirect);

  $template->set_prefilter('identification', 'm365connect_add_buttons_prefilter');
}

/**
 * load common javascript
 */
function m365connect_page_header()
{
  global $conf, $template;

  if (isset($conf['m365connect']['include_common_template']))
  {
    $template->set_filename('m365connect', realpath(M365CONNECT_PATH . 'template/identification_common.tpl'));
    $template->parse('m365connect');
  }
}


/**
 * prefilters
 */

function m365connect_add_buttons_prefilter($content)
{
  $search = '</form>';
  $add = file_get_contents(M365CONNECT_PATH . 'template/identification_page.tpl');
  return str_replace($search, $search.$add, $content);
}

function m365connect_remove_password_fields_prefilter($content)
{
  $search = 'type="password" ';
  $add = 'disabled="disabled" ';
  $script = '
{footer_script require="jquery"}
jQuery("input[type=password], input[name=send_password_by_mail]").parent().hide();
{/footer_script}';

  $content = str_replace($search, $search.$add, $content);
  return $content.$script;
}

function m365connect_add_profile_prefilter($content)
{
  $search = '#(</legend>)#';
  $add = file_get_contents(M365CONNECT_PATH . 'template/profile.tpl');
  return preg_replace($search, '$1 '.$add, $content, 1);
}

function m365connect_add_menubar_buttons_prefilter($content)
{
  $search = '#({include file=\$block->template\|@?get_extent:\$id ?})#';
  $add = file_get_contents(M365CONNECT_PATH . 'template/identification_menubar.tpl');
  return preg_replace($search, '$1 '.$add, $content);
}

function m365connect_add_login_in_register($content)
{
  $search[0] = '<form method="post" action="{$F_ACTION}"';
  $replace[0] = file_get_contents(M365CONNECT_PATH . 'template/register.tpl') . $search[0];
  
  $search[1] = '{\'Enter your personnal informations\'|@translate}';
  $replace[1] = '{\'Create a new account\'|@translate}';
  
  return str_replace($search, $replace, $content);
}
