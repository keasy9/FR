<?php
function init_js()
{
    echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';
    echo '<script>';

    if (isset($_GET['page'])) {

        switch ($_GET['page']) {
            case 'event_edit':
                echo require PLUGIN_JS_PATH . 'event_edit.js';
                break;
            case 'request_list':
                echo require PLUGIN_JS_PATH . 'request_list.js';
        }
    }
    echo '</script>';

    if (current_user_can('fr_moderator')) {
        echo '<script>';
        echo require PLUGIN_JS_PATH . 'remove-menu-item-recall.js';
        echo '</script>';
        if (in_array(explode( '?', $_SERVER['REQUEST_URI'])[0], ['/wp-admin/user-edit.php', '/wp-admin/user-new.php'])) {
            echo '<script>';
            echo require PLUGIN_JS_PATH . 'remove-additional-roles.js';
            echo '</script>';
        }elseif(get_current_screen()->id == 'users'){
            echo '<script>';
            echo require PLUGIN_JS_PATH . 'remove-additional-roles.on-list.js';
            echo '</script>';
        }
    }
}