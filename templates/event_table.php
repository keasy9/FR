<div class="wrap">
    <h1 class="wp-heading-inline">Мероприятия</h1>
    <a href="<?php echo site_url() ?>/wp-admin/admin.php?page=event_edit" class="page-title-action">Добавить новое</a>
    <hr class="wp-header-end">
    <form method="get">
        <?php
        if (isset($GLOBALS['fr_action_ok'])) {
            ?>
            <div id="message" class="updated notice is-dismissible">
                <p><?php echo $GLOBALS['fr_action_ok'] ?></p>
            </div>
            <?php
        }
        unset($GLOBALS['fr_action_ok']);
        ?>
        <input type="hidden" name="page" value="event_list">
        <?php $table->search_box('Поиск', 'seach'); ?>
        <?php $table->display(); ?>
    </form>
</div>
