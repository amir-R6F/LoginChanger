<?php

/*
Plugin Name: AuthoPlugin
Plugin URI: http://wordpress.org/plugins/r6f
Description: amir plugin auth.
Author: amir arbabi
Version: 1.0.0
Author URI: http://r6f.ir
*/

defined("ABSPATH" || exit());

require 'inc/am-menu.php';

class AM_Core{
    public static $_instance = null;
    const MINI_PHP_VER = '7.2';

    public static function instance()
    {
        if (is_null(self::$_instance)){
            self::$_instance = new self();
        }
    }

    public function __construct()
    {
        if (version_compare(PHP_VERSION, self::MINI_PHP_VER, '<')){
            do_action('admin_notices', [$this, 'admin_php_notice']);
            return;
        }

        $this->constans();
        $this->init();
    }

    public function constans()
    {
        if (!function_exists('get_plugin_data')){
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        define("AM_BASE_FILE", __FILE__);
        define("AM_PATH", trailingslashit(plugin_dir_path(AM_BASE_FILE)));
        define("AM_URL", trailingslashit(plugin_dir_url(AM_BASE_FILE)));
        define('General_Template', ABSPATH . 'wp-includes/general-template.php');
        define("AM_INC_PATH", trailingslashit(AM_PATH . 'inc'));
        define("AM_VIEW_PATH", trailingslashit(AM_PATH . 'views'));

        $tkt_plugin_data = get_plugin_data(AM_BASE_FILE);
        define('AM_VER', $tkt_plugin_data['Version']);
    }

    public function init()
    {
        require_once AM_INC_PATH . 'functions.php';


        register_activation_hook(AM_BASE_FILE, [$this, 'active']);
        register_deactivation_hook(AM_BASE_FILE, [$this, 'deactive']);

        new AM_Menu();

    }


    public function active()
    {

    }

    public function deactive()
    {

    }

    public function admin_php_notice()
    {
        ?>
        <div class="notice notice-warning">
            <p>نیازمند Php نسخه بالاتر است افزونه تیکت پشتیبان</p>
        </div>
        <?php
    }

}
AM_Core::instance();