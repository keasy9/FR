<!-- список файлов -->
<?php

require PLUGIN_CONF_PATH . 'lk.php';

//получить меню
$menu = wp_get_nav_menu_object('ЛК дополнительное');
//получить пункты меню
$menu_items = wp_get_nav_menu_items($menu->term_id);
//если в меню есть пункты
if (!empty($menu_items) && !empty($request_live)) { ?>
    <nav id="site-navigation" role="navigation" aria-label="Основная навигация" style="margin:0 auto;max-width:1020px;padding:0 12px;"
         class="lk-navigation main-navigation header-navigation nav--toggle-sub header-navigation-style-underline header-navigation-dropdown-animation-none">
        <div class="primary-menu-container header-menu-container">
            <ul id="primary-menu" class="menu" style="padding: 0;">
                <?php foreach ($menu_items as $item) { ?>
                    <li id="menu-item-1096" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1096">
                        <a href="<?php echo $item->url ?>"><?php echo $item->title ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </nav>
<?php } ?>

<?php include 'user_lk_info.php'; ?>
<div class="lk-main-block">
    <div class="tab-bar-wrapper">
        <div class="tab-bar">
            <?php if (!empty($request_default)) { ?>
                <p class="tab-picker map-tab-picker" data-hash="map" data-tab-name="map">Программа форума</p>
            <?php } ?>
            <p class="tab-picker request-tab-picker" data-hash="profile" data-tab-name="request">Профиль участника</p>
            <?php if (!empty($request_default) && !empty($selected_events)) { ?>
                <p class="tab-picker map-tab-picker" data-hash="selected" data-tab-name="selected">Маршрутная карта</p>
            <?php } ?>
        </div>
    </div>
    <?php
    if (empty($_GET) || $_GET['download'] != 'map') {
        require PLUGIN_TPLS_PATH . 'user_lk_request.php';
    }
    if (!empty($request_default) && (empty($_GET) || $_GET['download'] != 'map')) {
        require PLUGIN_TPLS_PATH . 'user_lk_map.php';
    }
    if (!empty($selected_events)) {
        require PLUGIN_TPLS_PATH . 'user_lk_selected_events.php';
    } ?>
</div>

<?php
