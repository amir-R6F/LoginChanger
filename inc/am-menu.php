<?php

defined("ABSPATH" || exit());

require 'abstract/am-base-menu.php';

class AM_Menu extends AM_Base_Menu
{

    private $wp_login_file_name;
    private $wp_login_new_file_name;
    private $wp_redirect_name;
    private $wp_new_redirect_name;

    public function __construct()
    {

        $this->page_title = 'test';
        $this->menu_title = 'wett';
        $this->menu_slug = 'etttt-plugin';


        parent::__construct();
    }


    public function page()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $am_setting = get_option('_am_auth_setting');

            if (isset($_POST['submit'])) {
                $data = [];
                foreach ($_POST as $key => $item) {
                    if ($key != 'submit') {
                        $data[$key] = $item;
                    }
                }

                $wplogin = $am_setting['my-wp-login'];
                $redirect = $am_setting['redirect-page'];
                $newLogin = $_POST['my-wp-login'];
                $newRedire = $_POST['redirect-page'];

            } else if (isset($_POST['reset'])) {
                $data = [];
                $data['my-wp-login'] = 'wp-login';
                $data['redirect-page'] = 'wp-login';

                $wplogin = $am_setting['my-wp-login'];
                $redirect = $am_setting['redirect-page'];
                $newLogin = 'wp-login';
                $newRedire = 'wp-login';
            }


            $this->initialize_variable($wplogin, $redirect, $newLogin, $newRedire);
            $this->change_wp_login();
            $wplogin == 'wp-login' || $redirect == 'wp-login' ?
                $this->change_general_template() : $this->change_from_to_general();

            update_option('_am_auth_setting', $data);

        }


        $field_value = get_option('_am_auth_setting');
        if (!$field_value) {
            $data = [];
            $data['my-wp-login'] = 'wp-login';
            $data['redirect-page'] = 'wp-login';
            add_option('_am_auth_setting', $data);
            $field_value = get_option('_am_auth_setting');
        }
        include AM_VIEW_PATH . 'main.php';

    }


    public function initialize_variable($oldName, $oldRedirect, $newName, $newRedirect)
    {
        $this->wp_login_file_name = $oldName;
        $this->wp_redirect_name = $oldRedirect;
        $this->wp_login_new_file_name = $newName;
        $this->wp_new_redirect_name = $newRedirect;
    }


    public function change_wp_login()
    {
        $wp_login = file_get_contents(ABSPATH . $this->wp_login_file_name . '.php');
        $new_wp_login = str_replace($this->wp_login_file_name . '.php', $this->wp_login_new_file_name . '.php', $wp_login);
        file_put_contents(ABSPATH . $this->wp_login_file_name . '.php', $new_wp_login);
        rename(ABSPATH . $this->wp_login_file_name . '.php', ABSPATH . $this->wp_login_new_file_name . '.php');
    }

    // Change General_Template
    public function change_general_template()
    {
        $general_template = file_get_contents(General_Template);
        $pos = strpos($general_template, $this->wp_login_file_name . '.php', strpos($general_template, $this->wp_login_file_name . '.php') + 1);
        $new_general_template = substr_replace($general_template, $this->wp_new_redirect_name . '.php', $pos, strlen($this->wp_login_file_name . '.php'));
        $new_general_template_2 = str_replace($this->wp_login_file_name . '.php', $this->wp_login_new_file_name . '.php', $new_general_template);
        file_put_contents(General_Template, $new_general_template_2);
    }

    public function change_from_to_general()
    {
        $general_template = file_get_contents(General_Template);
        $new_general_template = str_replace($this->wp_redirect_name . '.php', $this->wp_new_redirect_name . '.php', $general_template);
        $new_general_template_2 = str_replace($this->wp_login_file_name . '.php', $this->wp_login_new_file_name . '.php', $new_general_template);
        file_put_contents(General_Template, $new_general_template_2);
    }

}
