<?php
/*
Template Name: Личный кабинет участника(страница полностью заменит контент из редактора)
*/
if (!is_user_logged_in()) {
    header('Location: ' . wp_login_url());
}

$FR_dir = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'FR' . DIRECTORY_SEPARATOR;

require $FR_dir . 'scripts' . DIRECTORY_SEPARATOR . 'user_lk_controller.php';

get_header();
require_once 'header.php';


echo '<div id="map_belokuriha"></div>';

echo '<style>';
echo require $FR_dir . 'assets' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'user_lk.css';
echo '</style>';

echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';
echo '<script>';
echo require $FR_dir . 'assets' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'user_lk.js';

if(isset($request_default) && $request_default['part_form'] != '0'){
    echo require $FR_dir . 'assets' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'user_lk_live.js';
}

echo '</script>';

require $FR_dir . 'templates' . DIRECTORY_SEPARATOR . 'user_lk.php';

require_once 'footer.php';
?>




