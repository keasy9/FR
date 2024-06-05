<?php


class fr_menu_builder
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'build_menu']);
    }

    public function build_menu()
    {
        $fr_pi = new fr_page_init();
        add_menu_page(
            'Мероприятия',
            'Мероприятия',
            'manage_options',
            'event_list',
            [$fr_pi,'event_list'],
            'dashicons-calendar-alt',
            70
        );
        add_menu_page(
            'Участники',
            'Участники',
            'manage_options',
            'request_list',
            [$fr_pi, 'request_list'],
            'dashicons-id',
            71
        );
        add_submenu_page(
            'Новое мероприятие', 
            'Мероприятие',
            'Мероприятие',
            'manage_options',
            'event_edit',
            [$fr_pi,'event_edit']
        );
        if(current_user_can('fr_moderator')){
            remove_menu_page('options-general.php');
            remove_menu_page('edit.php?post_type=popup');
            remove_menu_page('edit.php?post_type=custom-css-js');
            remove_menu_page('login-customizer-settings');
            remove_menu_page('export-personal-data.php');
            remove_menu_page('wp_file_manager');
            remove_menu_page('themes.php');
        }
    }

}