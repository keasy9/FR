<form method="get">
    <div class="wrap">
        <h1 class="wp-heading-inline">Участники</h1>
        <select id="part_form_filter">
            <option value="all">Все участники</option>
            <option value="not_empty_profile" <?php echo isset($_GET['filter']) && $_GET['filter'] == 'not_empty_profile' ? 'selected' : ''?>>Заполнившие профиль</option>
            <option value="part_type_live" <?php echo isset($_GET['filter']) && $_GET['filter'] == 'part_type_live' ? 'selected' : ''?>>Только очное участие</option>
            <option value="part_type_remote" <?php echo isset($_GET['filter']) && $_GET['filter'] == 'part_type_remote' ? 'selected' : ''?>>Только дистанционное участие</option>
            <option value="empty_profile" <?php echo isset($_GET['filter']) && $_GET['filter'] == 'empty_profile' ? 'selected' : ''?>>Не заполнившие профиль</option>
            <option value="no_verified" <?php echo isset($_GET['filter']) && $_GET['filter'] == 'no_verified' ? 'selected' : ''?>>Не подвердившие email</option>
            <option value="speakers" <?php echo isset($_GET['filter']) && $_GET['filter'] == 'speakers' ? 'selected' : ''?>>Спикеры</option>
        </select>
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
        <input type="hidden" name="page" value="request_list">
        <?php $table->search_box('Поиск', 'seach'); ?>
        <?php $table->display(); ?>
    </div>
    <div id="curator_edit">
        <p class="title">Назначить куратора</p>
        <div id="inputs">
            <label>
                <span class="title">Фио</span>
                <span class="input-text-wrap"><input type="text" name="curator_fio"></span>
            </label>
            <br>
            <label>
                <span class="title">Номер телефона</span>
                <span class="input-text-wrap"><input type="tel" name="curator_phone"></span>
            </label>
            <br>
            <label>
                <span class="title">Email</span>
                <span class="input-text-wrap"><input type="text" name="curator_email"></span>
            </label>
            <br>
        </div>
        <div class="submit inline-edit-save">
            <button type="button" id="curator_edit_cancel" class="button cancel alignleft">Отмена</button>
            <button type="submit" id="curator_edit_save" class="button button-primary alignleft">Сохранить</button>
        </div>
    </div>
</form>

<div id="overlay"></div>
