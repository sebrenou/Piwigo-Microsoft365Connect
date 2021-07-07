<?php
defined('M365CONNECT_PATH') or die('Hacking attempt!');
 
global $template, $page, $conf;

// get current tab
$page['tab'] = (isset($_GET['tab'])) ? $_GET['tab'] : $page['tab'] = 'providers';

// tabsheet
include_once(PHPWG_ROOT_PATH.'admin/include/tabsheet.class.php');
$tabsheet = new tabsheet();
$tabsheet->set_id('m365connect');

$tabsheet->add('providers', l10n('Configuration'), M365CONNECT_ADMIN . '-providers');
$tabsheet->select($page['tab']);
$tabsheet->assign();
  
// include page
include(M365CONNECT_PATH . 'admin/' . $page['tab'] . '.php');

// template vars
$template->assign('M365CONNECT_PATH', get_root_url() . M365CONNECT_PATH);
  
// send page content
$template->assign_var_from_handle('ADMIN_CONTENT', 'm365connect_content');
