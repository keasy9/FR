<form method="post" id="fr_form_event" style="margin-top: 30vh">
    <input type="hidden" value="<?php echo $_GET['event'] ?? $event['id'] ?? '' ?>" name="event_id">
    <?php
    if (isset($GLOBALS['fr_action_ok'])) {
        ?>
        <div id="message" class="updated notice is-dismissible" style="margin-left: 0;">
            <p><?php echo $GLOBALS['fr_action_ok'] ?></p>
        </div>
        <?php
    }
    unset($GLOBALS['fr_action_ok']);
    ?>
    <table>
        <tr class="inline-edit-row inline-edit-row-page quick-edit-row quick-edit-row-page inline-edit-page inline-editor" style="">
            <td>

                <fieldset class="inline-edit-col-left">
                    <legend class="inline-edit-legend"><?php echo empty($event) ? 'Новое событие' : 'Редактировать событие' ?></legend>
                    <div class="inline-edit-col">

                        <label>
                            <span class="title">Название</span>
                            <span class="input-text-wrap"><textarea name="title"><?php echo $event['title'] ?? '' ?></textarea></span>
                        </label>

                        <label>
                            <span class="title">Описание</span>
                            <span class="input-text-wrap"><textarea name="description"><?php echo isset($event['description']) ? htmlspecialchars($event['description']) : '' ?></textarea></span>
                        </label>

                        <label>
                            <span class="title">Тип</span>
                            <select name="type" id="fr_type_select">
                                <option value="Программное мероприятие" <?php echo isset($event) && 'Программное мероприятие' == $event['type'] ? 'selected' : ''?>>Программное мероприятие</option>
                                <option value="Другое" <?php echo isset($event) && 'Другое' == $event['type'] ? 'selected' : ''?>>Другое</option>
                                <?php /*foreach ($event_types as $key => $type) {
                                    $selected = isset($event) && $type == $event['type'] ? 'selected' : '';
                                    echo '<option value="', $type, '" ', $selected, '>', $type, '</option>';
                                } */?>
                            </select>
                        </label>

                        <label id="fr_other_type" <?php echo isset($event) && $event['type'] == 'Другое' ? '' : 'style="display: none"' ?>>
                            <span class="input-text-wrap"><input type="text" name="type_custom" value="<?php echo $event['type_custom'] ?? '' ?>"></span>
                        </label>

                        <!--<label id="fr_parent_track" <?php /*echo isset($event) && $event['type'] == 'Шорт-трек' ? '' : 'style="display: none"' */ ?>>
                            <span class="title">Входит в сессию</span>
                            <select name="track_select">
                                <option>Указать позже</option>
                                <?php /*if (!empty($tracks)) {
                                    foreach ($tracks as $track) {
                                        if ($track['track'] == $event['track'] && $event['type'] == 'Экспертная сессия') {
                                            continue;
                                        }
                                        $selected = $event['track'] == $track['track'] ? 'selected' : '';
                                        echo '<option value="', $track['track'], '" ', $selected, '>', $track['track'], ' сессия</option>';
                                    }
                                } */ ?>
                            </select>
                        </label>-->

                        <label id="fr_track_num" <?php echo isset($event) && $event['type'] != 'Программное мероприятие' ? 'style="display: none"' : '' ?>>
                            <span class="title">Номер трека</span>
                            <!--<input type="select" name="track" value="<?php /*echo $event['track'] ?? 1 */ ?>">-->
                            <select name="track">
                                <option value="null" selected>нет</option>
                                <?php
                                $i = 1;
                                while($i <= 10){
                                    ?>
                                    <option value="<?php echo $i?>" <?php echo isset($event) && $event['track'] == $i ? 'selected' : ''?>><?php echo $i?></option>
                                <?php
                                    $i++;
                                }
                                ?>
                            </select>
                        </label>

                        <label id="fr_speaker_allow" <?php echo isset($event) && $event['type'] != 'Программное мероприятие' ? 'style="display: none"' : '' ?>>
                            <span class="title">Выступления спикера</span>
                            <select name="speaker_allow">
                                <option value="0" <?php echo $event['speaker_allow'] == '0' ? 'selected' : ''?>>нет</option>
                                <option value="1" <?php echo $event['speaker_allow'] == '1' ? 'selected' : ''?>>да</option>
                            </select>
                        </label>

                </fieldset>

                <fieldset class="inline-edit-col-right">
                    <?php
                    $months = [
                        '01' => 'Янв',
                        '02' => 'Фев',
                        '03' => 'Мар',
                        '04' => 'Апр',
                        '05' => 'Май',
                        '06' => 'Июн',
                        '07' => 'Июл',
                        '08' => 'Авг',
                        '09' => 'Сен',
                        '10' => 'Окт',
                        '11' => 'Ноя',
                        '12' => 'Дек',
                    ];
                    if (empty($event)) {
                        $day = date('d');
                        $month = date('m');
                        $year = date('Y');
                    } else {
                        $date = DateTime::createFromFormat('Y-m-d', $event['date']);
                        $day = $date->format('d');
                        $month = $date->format('m');
                        $year = $date->format('Y');
                    }
                    ?>
                    <fieldset class="inline-edit-date">
                        <legend><span class="title">Дата проведения</span></legend>
                        <div class="timestamp-wrap">
                            <label>
                                <span class="screen-reader-text">День</span>
                                <input type="text" name="day" value="<?php echo $day ?>" size="2" maxlength="2" autocomplete="off" class="form-required">
                            </label>
                            <label>
                                <span class="screen-reader-text">Месяц</span>
                                <select class="form-required" name="month" style="margin-top: -6px">
                                    <?php
                                    foreach ($months as $n => $m) {
                                        $selected = $month == $n ? 'selected' : '';
                                        echo '<option value="', $n, '" data-text="', $m, '" ', $selected, '>', $m, '</option>';
                                    }
                                    ?>
                                </select>
                            </label>
                            <label>
                                <span class="screen-reader-text">Год</span>
                                <input type="text" name="year" value="<?php echo $year ?>" size="4" maxlength="4" autocomplete="off" class="form-required">
                            </label>
                        </div>
                    </fieldset>

                    <?php
                    $time_start_h = '00';
                    $time_start_m = '00';
                    $time_end_h = '00';
                    $time_end_m = '00';
                    if (!empty($event['time_start'])) {
                        $time_start = DateTime::createFromFormat('H:i:s', $event['time_start']);
                        $time_start_h = $time_start->format('H');
                        $time_start_m = $time_start->format('i');
                    }
                    if (!empty($event['time_end'])) {
                        $time_end = DateTime::createFromFormat('H:i:s', $event['time_end']);
                        $time_end_h = $time_end->format('H');
                        $time_end_m = $time_end->format('i');
                    }
                    ?>
                    <fieldset class="inline-edit-date" style="margin:10px 0 10px 0">
                        <legend><span class="title">Время начала</span></legend>
                        <div class="timestamp-wrap">
                            <label>
                                <span class="screen-reader-text">Час</span>
                                <input type="text" name="time_start_h" value="<?php echo $time_start_h ?>" size="2" maxlength="2" autocomplete="off" class="form-required">
                            </label>
                            :
                            <label>
                                <span class="screen-reader-text">Минута</span>
                                <input type="text" name="time_start_m" value="<?php echo $time_start_m ?>" size="2" maxlength="2" autocomplete="off" class="form-required">
                            </label>
                        </div>
                    </fieldset>

                    <fieldset class="inline-edit-date" style="margin-bottom: 5px;">
                        <legend style="width: 100px;"><span class="title">Время окончания</span></legend>
                        <div class="timestamp-wrap">
                            <label>
                                <span class="screen-reader-text">Час</span>
                                <input type="text" name="time_end_h" value="<?php echo $time_end_h ?>" size="2" maxlength="2" autocomplete="off" class="form-required">
                            </label>
                            :
                            <label>
                                <span class="screen-reader-text">Минута</span>
                                <input type="text" name="time_end_m" value="<?php echo $time_end_m ?>" size="2" maxlength="2" autocomplete="off" class="form-required">
                            </label>
                        </div>
                    </fieldset>
                    <span id="fr_place">
                        <label id="fr_city">
                            <span class="title">Город</span>
                            <input type="text" name="place_city" value="<?php echo isset($event['place_city']) ? htmlspecialchars($event['place_city']) : '' ?>">
                        </label>

                        <label id="fr_city">
                            <span class="title">Улица и дом</span>
                            <input type="text" name="place_addr" value="<?php echo isset($event['place_addr']) ? htmlspecialchars($event['place_addr']) : '' ?>" style="min-width: 400px;">
                        </label>

                        <label id="fr_city">
                            <span class="title">Аудитория</span>
                            <input type="text" name="place_room" value="<?php echo isset($event['place_room']) ? htmlspecialchars($event['place_room']) : '' ?>">
                        </label>
                    </span>

                </fieldset>

                <div class="submit inline-edit-save">
                    <button type="button" id="fr_cancel_btn" class="button cancel alignleft">Отмена</button>
                    <button type="button" id="fr_reset_btn" class="button cancel alignleft" style="margin:0 10px 0 10px">Восстановить</button>
                    <button type="button" id="fr_save_btn" class="button button-primary alignleft">Сохранить</button>
                </div>

            </td>
        </tr>
    </table>
</form>