<?php
defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

class m365connect_maintain extends PluginMaintain
{
  private $default_conf = array(
    'display_menubar' => true,
    'display_register' => true    
    );
    
  private $file;
  
  function __construct($plugin_id)
  {
    parent::__construct($plugin_id);
    $this->file = PWG_LOCAL_DIR . 'config/hybridauth.inc.php';
  }

  function install($plugin_version, &$errors=array())
  {
    global $conf;

    if (empty($conf['m365connect']))
    {
      conf_update_param('m365connect', $this->default_conf, true);
    }
    else
    {
      $conf['m365connect'] = safe_unserialize($conf['m365connect']);
      
      if (!isset($conf['m365connect']['allow_merge_accounts']))
      {
        conf_update_param('m365connect', $conf['m365connect']);
      }
    }
  
    
    // add 'total' and 'enabled' fields in hybridauth conf file
    if (file_exists($this->file))
    {
      $hybridauth_conf = include($this->file);
      if (!isset($hybridauth_conf['total']))
      {
        $enabled = array_filter($hybridauth_conf['providers'], create_function('$p', 'return $p["enabled"];'));
        
        $hybridauth_conf['total'] = count($hybridauth_conf['providers']);
        $hybridauth_conf['enabled'] = count($enabled);
        
        $content = "<?php\ndefined('PHPWG_ROOT_PATH') or die('Hacking attempt!');\n\nreturn ";
        $content.= var_export($hybridauth_conf, true);
        $content.= ";\n?>";
        
        file_put_contents($this->file, $content);
      }
    }
  }

  function update($old_version, $new_version, &$errors=array())
  {
    $this->install($new_version, $errors);
  }

  function uninstall()
  {
    conf_delete_param('m365connect'); 
    @unlink($this->file);
  }
}
