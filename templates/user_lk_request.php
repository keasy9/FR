<div class="tab request-tab" data-tab="request">
    <?php
    $limitation = false;
    
    if (!empty($conf)) {
        $dateLimitation = DateTime::createFromFormat('Y-m-d H:i:s', $conf['dateRegisterLimit']);
        if ($dateLimitation) {
            if (new DateTime('now') >= $dateLimitation) {
                $dateRegistration = DateTime::createFromFormat('Y-m-d H:i:s', $user->user_registered);
                if ($dateRegistration >= $dateLimitation) {
                    $limitation = true;
                }
            }
        }
    }
    

    if (isset($_SESSION['saved']) && $_SESSION['saved']) {
        unset($_SESSION['saved']);
        ?>
        <div class="success-saved">
            <span class="success-title">Успешно сохранено</span>
            <button id="saved_message_hide" type="button"></button>
        </div>
    <?php } ?>
    <div class="request-form-block">
            <form method="post" enctype="multipart/form-data" id="request-form">
                <input type="hidden" name="action" value="request">
                
                <div class="request-default request-block">
                    <fieldset>
                        <legend class="legend">Профиль</legend>
                        <div>
                            <label>
                                <span class="title">Фамилия*</span>
                                <span class="input-text-wrap"><input type="text" name="s_name" value="<?php echo $request_default['s_name'] ?? '' ?>" required></span>
                            </label>
                            <label>
                                <span class="title">Имя*</span>
                                <span class="input-text-wrap"><input type="text" name="f_name" value="<?php echo $request_default['f_name'] ?? '' ?>" required></span>
                            </label>
                            <label>
                                <span class="title">Отчество (при наличии)</span>
                                <span class="input-text-wrap"><input type="text" name="l_name" value="<?php echo $request_default['l_name'] ?? '' ?>"></span>
                            </label>
                            <?php /*
                            <label>
                                <span class="title">Дата рождения</span>
                                <span class="input-text-wrap"><input type="date" name="birth_date" value="<?php echo $request_default['birth_date'] ?? '' ?>"></span>
                            </label>
                            <label>
                                <span class="title">Пол</span>
                                <?php
                                $f_checked = isset($request_default) && $request_default['sex'] == 0 ? 'checked' : '';
                                ?>
                                <span class="input-radio-wrap">
                                   <label class="input-radio inline-radio" for="sex-m"><input type="radio" name="sex" value="1" id="sex-m" checked>Муж</label>
                                   <label class="input-radio inline-radio" for="sex-f"><input type="radio" name="sex" value="0" id="sex-f" <?php echo $f_checked ?>>Жен</label>
                               </span>
                            </label>
                            */ ?>
                        </div>
                        <div>
                            <label>
                                <span class="title">Страна*</span>
                                <span class="input-select-wrap">
                                    <select name="sci_title">
                                        <?php
                                            foreach ([
                                                'Россия',
                                                'Белоруссия',
                                                'Армения',
                                                'Казахстан',
                                                'Киргизия',
                                                'Китай',
                                                'Монголия',
                                                'Таджикистан',
                                                'Туркменистан',
                                                'Узбекистан',
                                                'Африка',
                                                'Индия',
                                                'Бразилия',
                                                'Япония',
                                                'Корея',
                                                'ОАЭ',
                                                'Египет',
                                                'Другая',
                                            ] as $country) {
                                        ?>
                                                <option
                                                    value="<?= $country ?>"
                                                    <?= isset($request_default) && $request_default['country'] === $country ? 'selected' : '' ?>
                                                    ><?= $country ?></option>
                                        <?php
                                            }
                                        ?>
                                   </select>
                                </span>
                            </label>
                            <label>
                                <span class="title">Город*</span>
                                <span class="input-text-wrap"><input type="text" name="city" value="<?php echo $request_default['city'] ?? '' ?>" required></span>
                            </label>
                            <label>
                                <span class="title">Полное название организации*</span>
                                <span class="input-text-wrap"><input type="text" name="org_name" value="<?php echo isset($request_default) ? htmlspecialchars($request_default['org_name']) : '' ?>" required></span>
                            </label>
                            <label>
                                <?php
                                $ot2_checked = isset($request_default) && $request_default['org_type'] == 'Образование' ? 'checked' : '';
                                $ot3_checked = isset($request_default) && $request_default['org_type'] == 'Государственный сектор' ? 'checked' : '';
                                $ot4_checked = isset($request_default) && $request_default['org_type'] == 'Другое' ? 'checked' : '';
                                ?>
                                <span class="title">Тип организации</span>
                                <span class="input-radio-wrap">
                                   <label class="input-radio block-radio" for="org_type_1"><input type="radio" name="org_type" value="Бизнес" id="org_type_1" checked>Бизнес</label>
                                   <label class="input-radio block-radio" for="org_type_2"><input type="radio" name="org_type" value="Образование" id="org_type_2" <?php echo $ot2_checked ?>>Образование</label>
                                   <label class="input-radio block-radio" for="org_type_3"><input type="radio" name="org_type" value="Государственный сектор" id="org_type_3" <?php echo $ot3_checked ?>>Государственный сектор</label>
                                   <label class="input-radio block-radio" for="org_type_4"><input type="radio" name="org_type" value="Другое" id="org_type_4" <?php echo $ot4_checked ?>>Другое</label>
                               </span>
                            </label>
                            <label>
                                <span class="title">Должность*</span>
                                <span class="input-text-wrap"><input type="text" name="org_place" value="<?php echo $request_default['org_place'] ?? '' ?>" required></span>
                            </label>
                            <label>
                                <span class="title">Ученая степень</span>
                                <span class="input-text-wrap"><input type="text" name="sci_degree" value="<?php echo htmlspecialchars($request_default['sci_degree'] ?? '') ?>"></span>
                            </label>
                            <label>
                                <span class="title">Ученое звание</span>
                                <span class="input-select-wrap">
                                   <select name="sci_title">
                                       <?php
                                       $sci_titles = ['Отсутствует', 'Доцент', 'Профессор', 'Академик', 'Старший научный сотрудник', 'Младший научный сотрудник', 'Член-корреспондент'];
                                       foreach ($sci_titles as $sci_title) {
                                           $selected = isset($request_default) && $request_default['sci_title'] == $sci_title ? 'selected' : '';
                                           echo '<option value="', $sci_title, '" ', $selected, '>', $sci_title, '</option>';
                                       }
                                       ?>
                                   </select>
                               </span>
                            </label>
                        </div>
                        <div>
                            <label>
                                <span class="title">E-mail*</span>
                                <span class="input-text-wrap"><input type="email" name="email" value="<?php echo $request_default['email'] ?? $email ?? '' ?>" required></span>
                            </label>
                            <label>
                                <span class="title">Контактный телефон (сотовый)*</span>
                                <span class="input-text-wrap"><input type="text" name="phone" value="<?php echo $request_default['phone'] ?? '' ?>" required></span>
                            </label>
                            <label>
                                <span class="title inline-title">Загрузить фото профиля <?php echo isset($user_photo) ? '(заменить)' : '' ?></span>
                                <span class="title inline-title" style="color: #cf2e2e;">Размер файла не должен превышать 30 мб</span>
                                <span class="input-file-wrap">
                                   <input type="file" name="user-photo" accept="image/*">
                                   <?php if (isset($user_photo)) { ?>
                                       <span>
                                       <a href="/lk-user-file/?filename=<?php echo $user_photo ?>" target="_blank"><?php echo $user_photo ?></a>
                                       <input type="checkbox" data-file-input="user-photo-deleted" title="Удалить"/>
                                   </span>
                                   <?php } ?>
                                   <input type="hidden" name="user-photo-deleted">
                               </span>
                            </label>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend class="legend">Форма участия</legend>
                        <div>
                            <label>
                                <span class="title">Форма участия</span>
                                <?php
                                $live_checked = isset($request_default) && $request_default['part_form'] == 1 ? 'checked' : '';
                                $remote_checked = !isset($request_default) || $request_default['part_form'] == 0 ? 'checked' : '';
                                ?>
                                <span class="input-radio-wrap">
                                    <?php if (!$limitation || !in_array('part_live', $conf['disabledFields'])) { ?>
                                    <label class="input-radio block-radio" for="part_live"><input type="radio" name="part_form" value="1" id="part_live" <?php echo $live_checked ?>>Очная</label>
                                    <?php } ?>
                                    <label class="input-radio block-radio" for="part_remote"><input type="radio" name="part_form" value="0" id="part_remote" <?php echo $remote_checked ?>>Дистанционная</label>
                                </span>
                            </label>
                            <label>
                                <span class="title">Категория участия</span>
                                <?php
                                $pt2_checked = isset($request_default) && $request_default['part_type'] == 'Спикер' ? 'checked' : '';
                                $pt3_checked = isset($request_default) && $request_default['part_type'] == 'Модератор' ? 'checked' : '';
                                ?>
                                <span class="input-radio-wrap">
                                    <label class="input-radio block-radio" for="part_type_1"><input type="radio" name="part_type" value="Слушатель" id="part_type_1" checked>Слушатель</label>
                                    <?php if (!$limitation || !in_array('part_type_2', $conf['disabledFields'])) { ?>
                                    <label class="input-radio block-radio" for="part_type_2"><input type="radio" name="part_type" value="Спикер" id="part_type_2" <?php echo $pt2_checked ?>>Спикер</label>
                                    <?php } ?>
                                    <?php if (!$limitation || !in_array('part_type_3', $conf['disabledFields'])) { ?>
                                    <label class="input-radio block-radio" for="part_type_3"><input type="radio" name="part_type" value="Модератор" id="part_type_3" <?php echo $pt3_checked ?>>Модератор/Эксперт</label>
                                    <?php } ?>
                                </span>
                            </label>
                        </div>
                    </fieldset>
                </div>
                <?php if (!$limitation || !in_array('request-live', $conf['disabledFieldsets'])) {?>
                <div class="request-live request-block">
                    <fieldset>
                        <legend class="legend">Очное участие</legend>
                        <div>
                            <label style="display: none;">
                                <span class="title">Необходимо ли официальное приглашение?</span>
                                <?php
                                $checked = isset($request_live) && $request_live['required_invite'] == 0 ? 'checked' : '';
                                ?>
                                <span class="input-radio-wrap">
                                    <label class="input-radio block-radio" for="invite_1"><input type="radio" name="required_invite" value="1" id="invite_1" checked>Да</label>
                                    <label class="input-radio block-radio" for="invite_2"><input type="radio" name="required_invite" value="0" id="invite_2" <?php echo $checked ?>>Нет</label>
                                </span>
                            </label>
                            <label>
                                <span class="title">Дата и время приезда</span>
                                <span class="input-text-wrap"><input type="datetime-local" name="datetime_come" value="<?php echo $request_live['datetime_come'] ?? '' ?>"></span>
                            </label>
                            <label>
                                <span class="title">Место приезда (город)</span>
                                <span class="input-text-wrap"><input type="text" name="place_come" value="<?php echo htmlspecialchars($request_live['place_come']) ?? '' ?>"></span>
                            </label>
                            <label>
                                <span class="title">Рейс приезда</span>
                                <span class="input-text-wrap"><input type="text" name="flight_come" value="<?php echo htmlspecialchars($request_live['flight_come']) ?? '' ?>"></span>
                            </label>
                            <label>
                                <span class="title">Дата и время отъезда</span>
                                <span class="input-text-wrap"><input type="datetime-local" name="datetime_gone" value="<?php echo $request_live['datetime_gone'] ?? '' ?>"></span>
                            </label>
                            <label>
                                <span class="title">Место отъезда (город)</span>
                                <span class="input-text-wrap"><input type="text" name="place_gone" value="<?php echo htmlspecialchars($request_live['place_gone']) ?? '' ?>"></span>
                            </label>
                            <label>
                                <span class="title">Рейс отъезда</span>
                                <span class="input-text-wrap"><input type="text" name="flight_gone" value="<?php echo htmlspecialchars($request_live['flight_gone']) ?? '' ?>"></span>
                            </label>
                            <label>
                                <span class="title">Гостиница (отель)</span>
                                <span class="input-text-wrap"><input type="text" name="hotel" value="<?php echo htmlspecialchars($request_live['hotel']) ?? '' ?>"></span>
                            </label>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend class="legend">Дополнительные мероприятия в г.Белокуриха:</legend>
                        <div>
<!--                            <label>
                                <span class="title">Планируете ли Вы участие в Торжественном приеме от имени ректора АлтГУ 15.09.2022?</span>
                                <?php
/*                                $checked = isset($request_live) && $request_live['part_reception'] == 0 ? 'checked' : '';
                                */?>
                                <span class="input-radio-wrap">
                                    <label class="input-radio block-radio" for="reception_1"><input type="radio" name="part_reception" value="1" id="reception_1" checked>Да</label>
                                    <label class="input-radio block-radio" for="reception_2"><input type="radio" name="part_reception" value="0" id="reception_2" <?php /*echo $checked */?>>Нет</label>
                                </span>
                            </label>-->
                            <label>
                                <span class="title">Планируете ли Вы участие в Торжественном ужине 03.10.2024?</span>
                                <?php
                                $checked = isset($request_live) && $request_live['part_dinner'] == 0 ? 'checked' : '';
                                ?>
                                <span class="input-radio-wrap">
                                    <label class="input-radio block-radio" for="dinner_1"><input type="radio" name="part_dinner" value="1" id="dinner_1" checked>Да</label>
                                    <label class="input-radio block-radio" for="dinner_2"><input type="radio" name="part_dinner" value="0" id="dinner_2" <?php echo $checked ?>>Нет</label>
                                </span>
                            </label>
                            <label>
                                <span class="title">Планируете ли Вы участие в культурно-экскурсионной программе 04.10.2024?</span>
                                <?php
                                $checked = isset($request_live) && $request_live['part_excursion'] == 0 ? 'checked' : '';
                                ?>
                                <span class="input-radio-wrap">
                                    <label class="input-radio block-radio" for="excursion_1"><input type="radio" name="part_excursion" value="1" id="excursion_1" checked>Да</label>
                                    <label class="input-radio block-radio" for="excursion_2"><input type="radio" name="part_excursion" value="0" id="excursion_2" <?php echo $checked ?>>Нет</label>
                                </span>
                            </label>
                            <!--<label>
                                <span class="title">Планируете ли Вы участие в программах повышения квалификации?</span>
                                <?php
                            /*                                $pq1_checked = isset($request_live) && $request_live['part_qualify'] == 1 ? 'checked' : '';
                                                            $pq2_checked = (isset($request_live) && $request_live['part_qualify'] == 0) || $pq1_checked != 'checked' ? 'checked' : '';
                                                            */ ?>
                                <span class="input-radio-wrap">
                                    <label class="input-radio block-radio" for="qualify_1"><input type="radio" name="part_qualify" value="1" id="qualify_1" <?php /*echo $pq1_checked */ ?>>Да</label>
                                    <label class="input-radio block-radio" for="qualify_2"><input type="radio" name="part_qualify" value="0" id="qualify_2" <?php /*echo $pq2_checked */ ?>>Нет</label>
                                </span>
                            </label>-->
                        </div>
                    </fieldset>
                    <!--<fieldset>
                        <legend class="legend">Дополнительные документы</legend>
                        <div>
                            <label>
                                <span class="title inline-title">ПЦР-тест <?php /*echo !empty($request_live['file_pcr']) ? '(заменить)' : '' */ ?></span>
                                <span class="title inline-title" style="color: #cf2e2e;">Размер файла не должен превышать 30 мб</span>
                                <span class="input-file-wrap">
                                    <input type="file" name="file_pcr">
                                    <?php /*if (!empty($request_live['file_pcr'])) { */ ?>
                                    <span>
                                        <a href="/lk-user-file/?filename=<?php /*echo $request_live['file_pcr'] */ ?>" target="_blank"><?php /*echo $request_live['file_pcr'] */ ?></a>
                                        <input type="checkbox" data-file-input="file_pcr-deleted" title="Удалить" />
                                    </span>
                                    <?php /*} */ ?>
                                    <input type="hidden" name="file_pcr-deleted">
                                </span>
                            </label>
                            <label>
                                <span class="title inline-title">Сертификат вакцинации<?php /*echo !empty($request_live['file_vaccine']) ? '(заменить)' : '' */ ?></span>
                                <span class="title inline-title" style="color: #cf2e2e;">Размер файла не должен превышать 30 мб</span>
                                <span class="input-file-wrap">
                                    <input type="file" name="file_vaccine" accept="*/*" <?php /*// echo !empty($request_live['file_vaccine']) ? '' : 'required' */ ?>>
                                    <?php /*if (!empty($request_live['file_vaccine'])) { */ ?>
                                    <span>
                                        <a href="/lk-user-file/?filename=<?php /*echo $request_live['file_vaccine'] */ ?>" target="_blank"><?php /*echo $request_live['file_vaccine'] */ ?></a>
                                        <input type="checkbox" data-file-input="file_vaccine-deleted" title="Удалить" />
                                    </span>
                                    <?php /*} */ ?>
                                    <input type="hidden" name="file_vaccine-deleted">
                                </span>
                            </label>
                        </div>
                    </fieldset>-->
                </div>
                <?php } ?>
                
                <?php if (!$limitation || !in_array('request-speaker', $conf['disabledFieldsets'])) {?>
                <div class="request-speaker request-block">
                    <fieldset id="speaker-fieldset">
                        <legend class="legend">Выступления спикера</legend>
                        <template id="speaker-inputs">
                            <div class="border-bottom">
                                <label>
                                    <span class="title inline-title">Мероприятие</span>
                                    <span class="input-select-wrap">
                                    <select name="id_event[]">
                                        <option value="null">Не выбрано</option>
                                        <?php
                                        foreach ($events as $event) {
                                            if ($event['type'] != 'Программное мероприятие'  || $event['speaker_allow'] == '0') {
                                                continue;
                                            }

                                            if (empty($event['track'])) {
                                                if ($event['title'] == 'Пленарное заседание') {
                                                    $title = "{$event['title']} {$event['description']}";
                                                } else {
                                                    $title = $event['title'];
                                                }
                                            } elseif (mb_strpos($event['title'], $event['track'])) {
                                                $title = "Программное мероприятие экспертной сессии {$event['track']}";
                                            } else {
                                                $title = "Экспертная сессия {$event['track']}";
                                            }

                                            echo '<option value="', $event['id'], '" title="', htmlspecialchars($event['title']), '">', $title, '</option>';
                                        } ?>
                                    </select>
                                </span>
                                </label>
                                <label>
                                    <span class="title inline-title">Выступление</span>
                                    <span class="input-select-wrap">
                                    <select class="speaker_show_type_select" name="show_type[]">
                                        <?php
                                        $types = ['Доклад', 'Мастер-класс', 'Визионерская лекция', 'Фокус-сессия', 'Экспертно-аналитическая сессия', 'Форсайт-сессия', 'Проектная сессия', 'Участие в дискуссии', 'Другое'];
                                        foreach ($types as $type) {
                                            echo '<option value="', $type, '">', $type, '</option>';
                                        } ?>
                                    </select>
                                </span>
                                </label>
                                <label style="display: none;">
                                    <span class="title inline-title">Введите тип выступления</span>
                                    <span class="input-text-wrap"><input type="text" name="show_type_other[]"></span>
                                </label>
                                <label>
                                    <span class="title inline-title">Название выступления</span>
                                    <span class="input-text-wrap"><input type="text" name="show_name[]"></span>
                                </label>
                                <label>
                                    <span class="title inline-title">Презентация</span>
                                    <span class="title inline-title" style="color: #cf2e2e;">Размер файла не должен превышать 30 мб</span>
                                    <span class="input-file-wrap">
                                        <input type="file" name="file_presentation[]">
                                    </span>
                                </label>
                                <label class="label-btn">
                                    <button class="button-delete btn-speaker-delete" type="button">Удалить выступление</button>
                                </label>
                            </div>
                        </template>
                        <?php if (!empty($requests_speaker)) {
                            foreach ($requests_speaker as $key => $request) { ?>
                                <div class="border-bottom">
                                    <input type="hidden" name="speaker_id[]" value="<?php echo $request['id'] ?>">
                                    <label>
                                        <span class="title inline-title">Мероприятие*</span>
                                        <span class="input-select-wrap">
                                    <select name="id_event_<?php echo $request['id'] ?>">
                                        <option value="null">Не выбрано</option>
                                        <?php
                                        $repeats = [];

                                        foreach ($events as $event) {
                                            if ($event['type'] != 'Программное мероприятие' || $event['speaker_allow'] == '0') {
                                                continue;
                                            }

                                            if (empty($event['track'])) {
                                                if ($event['title'] == 'Пленарное заседание') {
                                                    $title = "{$event['title']} {$event['description']}";
                                                } else {
                                                    $title = $event['title'];
                                                }
                                            } elseif (mb_strpos($event['title'], $event['track'])) {
                                                $title = "Программное мероприятие экспертной сессии {$event['track']}";
                                            } else {
                                                $title = "Экспертная сессия {$event['track']}";
                                            }

                                            $selected = $request['id_event'] == $event['id'] ? ' selected' : '';

                                            echo '<option value="', $event['id'], '" title="', htmlspecialchars($event['title']), '"', $selected, '>', $title, '</option>';
                                        } ?>
                                    </select>
                                    </span>
                                    </label>
                                    <label>
                                        <span class="title inline-title">Выступление</span>
                                        <span class="input-select-wrap">
                                        <select class="speaker_show_type_select" name="show_type_<?php echo $request['id'] ?>">
                                            <?php
                                            $types = ['Доклад', 'Мастер-класс', 'Визионерская лекция', 'Фокус-сессия', 'Экспертно-аналитическая сессия', 'Форсайт-сессия', 'Проектная сессия', 'Другое'];
                                            foreach ($types as $type) {
                                                $selected = $request['show_type'] == $type ? 'selected' : '';
                                                echo '<option value="', $type, '"', $selected, '>', $type, '</option>';
                                            } ?>
                                        </select>
                                    </span>
                                    </label>
                                    <label <?php echo $request['show_type'] != 'Другое' ? 'style="display: none"' : '' ?>>
                                        <span class="title inline-title">Введите тип выступления</span>
                                        <span class="input-text-wrap"><input type="text" name="show_type_other_<?php echo $request['id'] ?>" value="<?php echo htmlspecialchars($request['show_type_other']) ?? '' ?>"></span>
                                    </label>
                                    <label>
                                        <span class="title inline-title">Название выступления</span>
                                        <span class="input-text-wrap"><input type="text" name="show_name_<?php echo $request['id'] ?>" value="<?php echo htmlspecialchars($request['show_name']) ?>"></span>
                                    </label>
                                    <label>
                                        <span class="title inline-title">Презентация</span>
                                        <span class="input-file-wrap">
                                        <input type="file" name="file_presentation_<?php echo $request['id'] ?>">
                                        <?php if (isset($request['file_presentation'])) { ?>
                                            <span>
                                            <a href="/lk-user-file/?filename=<?php echo $request['file_presentation'] ?>" target="_blank"><?php echo $request['file_presentation'] ?></a>
                                            <input type="checkbox" data-file-input="file_presentation-<?php echo $request['id'] ?>-deleted" title="Удалить"/>
                                        </span>
                                        <?php } ?>
                                        <input type="hidden" name="file_presentation-<?php echo $request['id'] ?>-deleted">
                                    </span>
                                    </label>
                                    <label class="label-btn">
                                        <button class="button-delete btn-speaker-delete" type="button">Удалить выступление</button>
                                    </label>
                                </div>
                            <?php }
                        } ?>
                        <button class="button-add btn-speaker-add" type="button">Добавить выступление</button>
                    </fieldset>
                </div>
                <?php } ?>
                <div class="request-final request-block">
                    <fieldset>
                        <label>
                                        <span class="input-radio-wrap">
                                            <label for="confirm">
                                                <input type="checkbox" name="confirm" id="confirm">
                                                *Я даю свое согласие ФГБОУ ВО «Алтайский государственный
                                                университет» на обработку персональных данных с целью участия в
                                                Форуме. Согласие предоставляется с момента подписания и
                                                действительно в течение года. Отзыв согласия может быть произведен в
                                                письменной форме.
                                            </label>
                                        </span>
                        </label>
                    </fieldset>
                </div>
                <input type="submit" class="disabled" value="Сохранить" id="request-save">
            </form>
    </div>
</div>