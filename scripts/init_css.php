<?php
function init_css()
{
    echo '<style>';
    if (isset($_GET['page'])) {
        switch ($_GET['page']) {
            case 'event_list':
                echo require PLUGIN_CSS_PATH . 'event_table.css';
                break;
            case 'request_list':
                echo require PLUGIN_CSS_PATH . 'request_list.css';
                break;
        }
    }
    echo '</style>';
}