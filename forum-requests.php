<?php
/**
 * Plugin Name: Заявки для форума
 * Description: Форма регистрации, просмотр заявок и управление мероприятиями(треки, шорт-треки и т.д.) + экспорт результатов в Excel.
 * Author: krechetov@mail.asu.ru
 * Version: 1.0
 *
 * @package forum--requests
 */


defined('ABSPATH') or die("Доступ напрямую запрещен.");

/** @const Global путь к плагину */
define('PLUGIN_PATH', realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR);
/** @const Global путь к php-скриптам плагина */
define('PLUGIN_SCRIPTS_PATH', PLUGIN_PATH . 'scripts' . DIRECTORY_SEPARATOR);
/** @const Global путь к классам плагина */
define('PLUGIN_CLASSES_PATH', PLUGIN_PATH . 'classes' . DIRECTORY_SEPARATOR);
/** @const Global путь к стилям плагина */
define('PLUGIN_CSS_PATH', PLUGIN_PATH . 'assets' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR);
/** @const Global путь к js плагина */
define('PLUGIN_JS_PATH', PLUGIN_PATH . 'assets' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR);
/** @const Global путь к шабонам плагина */
define('PLUGIN_TPLS_PATH', PLUGIN_PATH . 'templates' . DIRECTORY_SEPARATOR);
/** @const Global путь к шабонам плагина */
define('PLUGIN_CONF_PATH', PLUGIN_PATH . 'config' . DIRECTORY_SEPARATOR);

$classes = [
    'menu_builder', //добавляет нужные пункты в меню админки
    'event_table_builder', // строит таблицу вкладки "мероприятия"
    'event_model', //класс-модель для мероприятий (таблица fr_event)
    'page_init', //класс с callback-функциями для отображения страниц в админке
    'participant_model', //класс-модель для участников
    'participant_table_builder', // строит таблицу вкладки "участники"
];
foreach ($classes as $class) {
    require_once PLUGIN_CLASSES_PATH . 'fr_' . $class . '.php';
}

function del_plugin()
{
    remove_role('fr_moderator');
}

function forum_request_init()
{
/*    $tpl = get_template_directory() . DIRECTORY_SEPARATOR . 'request_template.php';
    copy(
        PLUGIN_PATH . 'assets' . DIRECTORY_SEPARATOR . 'for-themes' . DIRECTORY_SEPARATOR . 'request_template.php',
        '$tpl'
    );*/
    add_role(
        'fr_moderator',
        'Оператор',
        [
            'administrator' => true,
            'manage_options' => true,
            'list_users' => true,
            'create_users' => true,
            'edit_users' => true,
            'promote_users' => true,
            'delete_users' => true,
        ]
    );

    register_deactivation_hook(__FILE__, 'del_plugin');

    new fr_menu_builder();
    require_once PLUGIN_SCRIPTS_PATH . 'init_css.php';
    require_once PLUGIN_SCRIPTS_PATH . 'init_js.php';
}

function fr_md_redir()
{
    if (current_user_can('fr_moderator') && $_SERVER['REQUEST_URI'] == '/wp-admin/') {
        wp_redirect('/wp-admin/admin.php?page=request_list');
        die;
    }
}

function redirect_after_login_if_moderator($redirect_to, $requested_redirect_to, $user)
{
    //при ошибке входа вместо WP_User приходит WP_Error
    if ($user instanceof WP_User) {
        if (user_can($user, 'administrator') || user_can($user, 'Super Admin')) {
            ?>
            <script>
                document.location.href = '/wp-admin/'
            </script>
            <?php
            die;
        } elseif (user_can($user, 'fr_moderator')) {
            ?>
            <script>
                document.location.href = '/wp-admin/admin.php?page=request_list'
            </script>
            <?php
            die;
        }
    }
    return $redirect_to;
}

function rollback_role_if_moderator($user_id, $role, $old_roles)
{
    if (is_user_logged_in() && current_user_can('fr_moderator') && !in_array($role, ['need-confirm', 'contributor'])) {
        $user = get_user_by('id', $user_id);
        $user->set_role($old_roles[0]);
    }
}

function redirect_to_list_users(){
    if (
        current_user_can('fr_moderator') &&
        explode('?', $_SERVER['REQUEST_URI'])[0] == '/wp-admin/user-edit.php' &&
        !(user_can($_GET['user_id'], 'need-confirm') || user_can($_GET['user_id'], 'contributor'))
    ) {
        wp_redirect('/wp-admin/users.php');
        die;
    }
}

function sanitize_cyrillic_chars($filename) {
    $filename = (string) $filename; // преобразуем в строковое значение
    $filename = trim($filename); // убираем пробелы в начале и конце строки
    $filename = function_exists('mb_strtolower') ? mb_strtolower($filename) : strtolower($filename); // переводим строку в нижний регистр (иногда надо задать локаль)
    $filename = strtr($filename, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
    return $filename; // возвращаем результат
}

add_action('plugins_loaded', 'forum_request_init');
add_action('admin_head', 'init_js');
add_action('admin_head', 'init_css');
add_action('init', 'fr_md_redir');
add_action('current_screen', 'redirect_to_list_users');
add_action('set_user_role', 'rollback_role_if_moderator', 10, 3);

add_filter('sanitize_file_name', 'sanitize_cyrillic_chars', 10);
add_filter( 'login_redirect', 'redirect_after_login_if_moderator', 10, 3 );
