<?php
session_start();
?>
<div class="user-photo">
    <img src="/wp-content/plugins/FR/<?php echo isset($user_photo) ? 'user-uploaded-files/' . $user_photo : '/assets/img/no-profile-photo.png' ?>" id="user-photo" height="35px" width="35px">
    <div class="user-name div"><?php echo $f_name ?? '' ?></div>
    <a href="/auth/?action=logout&_wpnonce=7190820ee8"><img src="/wp-content/plugins/FR/assets/img/sign-out.png" id="sign-out" height="35px" width="35px" title="Выйти"></a>
</div>
<div id="sitename">
    <?php bloginfo('name'); ?>
</div>
<div class="lk-main-block">
    <div class="tab-bar">
        <p class="tab-picker request-tab-picker <?php echo isset($request_default) ? '' : 'picked' ?>">Профиль</p>
        <p class="tab-picker map-tab-picker <?php echo isset($request_default) ? 'picked' : '' ?>">Маршрутная карта</p>
    </div>
    <?php
    if (isset($_SESSION['saved']) && $_SESSION['saved'] == 'ok') { ?>
        <div class="success-saved">
            <p class="success-title">Успешно сохранено</p>
            <a id="saved_message_hide">скрыть</a>
        </div>
    <?php } ?>
    <div class="tab request-tab" <?php echo isset($request_default) ? 'style="display: none"' : '' ?>>
        <div class="request-form-block">
            <?php if (new DateTime('now') >= new DateTime('2024-08-01')) { ?>
                <div class="form-disabled">Редактирование профиля недоступно с 1 августа 2024 г.</div>
            <?php } else { ?>
                <form method="post" enctype="multipart/form-data" id="request-form">
                    <input type="hidden" name="action" value="request">
                    <div class="request-default request-block">
                        <fieldset>
                            <legend class="legent">Данные профиля</legend>
                            <table class="request-default form-table">
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title">Фамилия*</span>
                                            <span class="input-text-wrap"><input type="text" name="s_name" value="<?php echo $request_default['s_name'] ?? '' ?>" required></span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title">Имя*</span>
                                            <span class="input-text-wrap"><input type="text" name="f_name" value="<?php echo $request_default['f_name'] ?? $f_name ?? '' ?>" required></span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <span class="title">Отчество(при наличии)</span>
                                            <span class="input-text-wrap"><input type="text" name="l_name" value="<?php echo $request_default['l_name'] ?? '' ?>"></span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title">Дата рождения</span>
                                            <span class="input-text-wrap"><input type="date" name="birth_date" value="<?php echo $request_default['birth_date'] ?? '' ?>"></span>
                                        </label>
                                    </td>
                                    <td>
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
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title">Город*</span>
                                            <span class="input-text-wrap"><input type="text" name="city" value="<?php echo $request_default['city'] ?? '' ?>" required></span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title">Полное название организации*</span>
                                            <span class="input-text-wrap"><input type="text" name="org_name" value="<?php echo $request_default['org_name'] ?? '' ?>" required></span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <?php
                                            $ot2_checked = isset($request_default) && $request_default['org_type'] == 'Образование' ? 'checked' : '';
                                            $ot3_checked = isset($request_default) && $request_default['org_type'] == 'Государственный сектор' ? 'checked' : '';
                                            ?>
                                            <span class="title">Тип организации</span>
                                            <span class="input-radio-wrap">
                                            <label class="input-radio block-radio" for="org_type_1"><input type="radio" name="org_type" value="Бизнес" id="org_type_1" checked>Бизнес</label>
                                            <label class="input-radio block-radio" for="org_type_2"><input type="radio" name="org_type" value="Образование" id="org_type_2" <?php echo $ot2_checked ?>>Образование</label>
                                            <label class="input-radio block-radio" for="org_type_3"><input type="radio" name="org_type" value="Государственный сектор" id="org_type_3" <?php echo $ot3_checked ?>>Государственный сектор</label>
                                        </span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title">Должность*</span>
                                            <span class="input-text-wrap"><input type="text" name="org_place" value="<?php echo $request_default['org_place'] ?? '' ?>" required></span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title">Ученая степень</span>
                                            <span class="input-text-wrap"><input type="text" name="sci_degree" value="<?php echo $request_default['sci_degree'] ?? '' ?>"></span>
                                        </label>
                                    </td>
                                    <td>
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
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title">E-mail*</span>
                                            <span class="input-text-wrap"><input type="email" name="email" value="<?php echo $request_default['email'] ?? $email ?? '' ?>" required></span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <span class="title">Контактный телефон*</span>
                                            <span class="input-text-wrap"><input type="tel" name="phone" pattern="^[ 0-9]+$" value="<?php echo $request_default['phone'] ?? '' ?>" required></span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title inline-title">Загрузить фото профиля <?php echo isset($user_photo) ? '(заменить)' : '' ?></span>
                                            <span class="input-file-wrap">
                                            <input type="file" name="user-photo" accept="image/*">
                                                <?php if (isset($user_photo)) { ?>
                                                    <a href="/lk-user-file/?filename=<?php echo $user_photo ?>" target="_blank">Просмотр</a>
                                                    <button class="button-delete" data-file-input="user-photo" type="button">Удалить</button>
                                                <?php } ?>
                                                <input type="hidden" name="user-photo-deleted">
                                            </span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title">Форма участия</span>
                                            <?php
                                            $live_checked = isset($request_default) && $request_default['part_form'] == 1 ? 'checked' : '';
                                            $remote_checked = !isset($request_default) || $request_default['part_form'] == 0 ? 'checked' : '';
                                            ?>
                                            <span class="input-radio-wrap">
                                            <label class="input-radio block-radio" for="part_live"><input type="radio" name="part_form" value="1" id="part_live" <?php echo $live_checked ?>>Очная</label>
                                            <label class="input-radio block-radio" for="part_remote"><input type="radio" name="part_form" value="0" id="part_remote" <?php echo $remote_checked ?>>Дистанционная</label>
                                        </span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title">Категория участия</span>
                                            <?php
                                            $pt2_checked = isset($request_default) && $request_default['part_type'] == 'Спикер' ? 'checked' : '';
                                            $pt3_checked = isset($request_default) && $request_default['part_type'] == 'Модератор' ? 'checked' : '';
                                            ?>
                                            <span class="input-radio-wrap">
                                            <label class="input-radio block-radio" for="part_type_1"><input type="radio" name="part_type" value="Слушатель" id="part_type_1" checked>Слушатель</label>
                                            <label class="input-radio block-radio" for="part_type_2"><input type="radio" name="part_type" value="Спикер" id="part_type_2" <?php echo $pt2_checked ?>>Спикер</label>
                                            <label class="input-radio block-radio" for="part_type_3"><input type="radio" name="part_type" value="Модератор" id="part_type_3" <?php echo $pt3_checked ?>>Модератор</label>
                                        </span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                    <div class="request-live    request-block">
                        <fieldset>
                            <legend class="legent">Очное участие</legend>
                            <table class="request-live form-table">
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title">Необходимо ли официальное приглашение?</span>
                                            <?php
                                            $checked = isset($request_live) && $request_live['required_invite'] == 0 ? 'checked' : '';
                                            ?>
                                            <span class="input-radio-wrap">
                                            <label class="input-radio block-radio" for="invite_1"><input type="radio" name="required_invite" value="1" id="invite_1" checked>Да</label>
                                            <label class="input-radio block-radio" for="invite_2"><input type="radio" name="required_invite" value="0" id="invite_2" <?php echo $checked ?>>Нет</label>
                                        </span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title">Дата и время приезда</span>
                                            <span class="input-text-wrap"><input type="datetime-local" name="datetime_come" value="<?php echo $request_live['datetime_come'] ?? '' ?>"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <span class="title">Дата и время отъезда</span>
                                            <span class="input-text-wrap"><input type="datetime-local" name="datetime_gone" value="<?php echo $request_live['datetime_gone'] ?? '' ?>"></span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title">Место приезда(город)</span>
                                            <span class="input-text-wrap"><input type="text" name="place_come" value="<?php echo $request_live['place_come'] ?? '' ?>"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <span class="title">Место отъезда(город)</span>
                                            <span class="input-text-wrap"><input type="text" name="place_gone" value="<?php echo $request_live['place_gone'] ?? '' ?>"></span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title">Рейс</span>
                                            <span class="input-text-wrap"><input type="text" name="flight_come" value="<?php echo $request_live['flight_come'] ?? '' ?>"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <span class="title">Рейс</span>
                                            <span class="input-text-wrap"><input type="text" name="flight_gone" value="<?php echo $request_live['flight_gone'] ?? '' ?>"></span>
                                        </label>
                                    </td>
                                </tr>
                                <tr class="form-subtitle title">
                                    <td colspan="2">
                                        Культурная программа:
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title">Планируете ли Вы участие в экскурсионной программе?</span>
                                            <?php
                                            $checked = isset($request_live) && $request_live['part_excursion'] == 0 ? 'checked' : '';
                                            ?>
                                            <span class="input-radio-wrap">
                                            <label class="input-radio block-radio" for="excursion_1"><input type="radio" name="part_excursion" value="1" id="excursion_1" checked>Да</label>
                                            <label class="input-radio block-radio" for="excursion_2"><input type="radio" name="part_excursion" value="0" id="excursion_2" <?php echo $checked ?>>Нет</label>
                                        </span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title">Планируете ли Вы участие в праздничном ужине-фуршете 17.09.2024?</span>
                                            <?php
                                            $checked = isset($request_live) && $request_live['part_dinner'] == 0 ? 'checked' : '';
                                            ?>
                                            <span class="input-radio-wrap">
                                            <label class="input-radio block-radio" for="dinner_1"><input type="radio" name="part_dinner" value="1" id="dinner_1" checked>Да</label>
                                            <label class="input-radio block-radio" for="dinner_2"><input type="radio" name="part_dinner" value="0" id="dinner_2" <?php echo $checked ?>>Нет</label>
                                        </span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title">Планируете ли Вы участие в программах повышения квалификации?</span>
                                            <?php
                                            $pq1_checked = isset($request_live) && $request_live['part_qualify'] == 1 ? 'checked' : '';
                                            $pq2_checked = (isset($request_live) && $request_live['part_qualify'] == 0) || $pq1_checked != 'checked' ? 'checked' : '';
                                            ?>
                                            <span class="input-radio-wrap">
                                            <label class="input-radio block-radio" for="qualify_1"><input type="radio" name="part_qualify" value="1" id="qualify_1" <?php echo $pq1_checked ?>>Да</label>
                                            <label class="input-radio block-radio" for="qualify_2"><input type="radio" name="part_qualify" value="0" id="qualify_2" <?php echo $pq2_checked ?>>Нет</label>
                                        </span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                        <fieldset>
                            <legend class="legent">Дополнительная информация</legend>
                            <table class="request-qualify form-table">
                                <tr class="form-subtitle title">
                                    <td colspan="2">
                                        Прикрепить файлы:
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title inline-title">ПЦР-тест <?php echo isset($request_live, $request_live['file_pcr']) ? '(заменить)' : '' ?></span>
                                            <span class="input-file-wrap">
                                            <input type="file" name="file_pcr">
                                                <?php if (isset($request_live, $request_live['file_pcr'])) { ?>
                                                    <a href="/lk-user-file/?filename=<?php echo $request_live['file_pcr'] ?>" target="_blank">Просмотр</a>
                                                    <button class="button-delete" data-file-input="file_pcr" type="button">Удалить</button>
                                                <?php } ?>
                                                <input type="hidden" name="file_pcr-deleted">
                                    </span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title inline-title">Сертификат вакцинации* <?php echo isset($request_live, $request_live['file_vaccine']) ? '(заменить)' : '' ?></span>
                                            <span class="input-file-wrap">
                                            <input type="file" name="file_vaccine" accept="*/*" <?php echo isset($request_live, $request_live['file_vaccine']) ? '' : 'required' ?>>
                                            <?php if (isset($request_live, $request_live['file_vaccine'])) { ?>
                                                <a href="/lk-user-file/?filename=<?php echo $request_live['file_vaccine'] ?>" target="_blank">Просмотр</a>
                                                <button class="button-delete" data-file-input="file_vaccine" type="button">Удалить</button>
                                            <?php } ?>
                                                <input type="hidden" name="file_vaccine-deleted">
                                            </span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                    <div class="request-qualify request-block">
                        <fieldset>
                            <legend class="legent">Повышение квалификации</legend>
                            <table class="request-qualify form-table">
                                <tr class="form-subtitle title">
                                    <td colspan="2">
                                        Для участия в программах повышения квалификации просим Вас выбрать
                                        программу повышения квалификации, заполнить соответствующие поля и
                                        прикрепить документы:
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title">Выберите программу повышения квалификации:</span>
                                            <span class="input-select-wrap">
                                            <select name="qualify_program">
                                                <?php
                                                foreach ($qualify_progs as $prog) {
                                                    $selected = isset($request_qualify) && $request_qualify['id_qualify_program'] == $prog['id'] ? 'selected' : '';
                                                    echo '<option value="', $prog['id'], '" ', $selected, '>', $prog['title'], '</option>';
                                                }
                                                ?>
                                            </select>
                                        </span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title">Гражданство*</span>
                                            <span class="input-text-wrap"><input type="text" name="nationality" value="<?php echo $request_qualify['nationality'] ?? '' ?>" required></span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title">Наименование документа об образовании*</span>
                                            <span class="input-text-wrap"><input type="text" name="document" value="<?php echo $request_qualify['document'] ?? '' ?>" required></span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table class="request-qualify form-table subtable">
                                            <tr>
                                                <td>
                                                    <label>
                                                        <span class="title">Серия*</span>
                                                        <span class="input-text-wrap"><input type="text" name="document_ser" value="<?php echo $request_qualify['document_ser'] ?? '' ?>" required></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <span class="title">Номер*</span>
                                                        <span class="input-text-wrap"><input type="text" name="document_num" value="<?php echo $request_qualify['document_num'] ?? '' ?>" required></span>
                                                    </label>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title">Регистрационный номер*</span>
                                            <span class="input-text-wrap"><input type="text" name="document_reg_num" value="<?php echo $request_qualify['document_reg_num'] ?? '' ?>" required></span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <span class="title">Дата выдачи*</span>
                                            <span class="input-text-wrap"><input type="date" name="document_date" value="<?php echo $request_qualify['document_date'] ?? '' ?>" required></span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title">Паспортные данные</span>
                                            <span class="input-text-wrap"><input type="text" name="pasport_data" value="<?php echo $request_qualify['pasport_data'] ?? '' ?>"></span>
                                        </label>
                                    </td>
                                </tr>
                                <tr class="form-subtitle title">
                                    <td colspan="2">
                                        Прикрепите файлы:
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title inline-title">Копия диплома об образовании <?php echo isset($request_qualify, $request_qualify['file_diplom']) ? '(заменить)' : '' ?></span>
                                            <span class="input-file-wrap">
                                            <input type="file" name="file_diplom">
                                                <?php if (isset($request_qualify, $request_qualify['file_diplom'])) { ?>
                                                    <a href="/lk-user-file/?filename=<?php echo $request_qualify['file_diplom'] ?>" target="_blank">Просмотр</a>
                                                    <button class="button-delete" data-file-input="file_diplom" type="button">Удалить</button>
                                                <?php } ?>
                                                <input type="hidden" name="file_diplom-deleted">
                                    </span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title inline-title">Копия снилса <?php echo isset($request_qualify, $request_qualify['file_snils']) ? '(заменить)' : '' ?></span>
                                            <span class="input-file-wrap">
                                            <input type="file" name="file_snils">
                                                <?php if (isset($request_qualify, $request_qualify['file_snils'])) { ?>
                                                    <a href="/lk-user-file/?filename=<?php echo $request_qualify['file_snils'] ?>" target="_blank">Просмотр</a>
                                                    <button class="button-delete" data-file-input="file_snils" type="button">Удалить</button>
                                                <?php } ?>
                                                <input type="hidden" name="file_snils-deleted">
                                    </span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <span class="title inline-title">Копия свидетельства о заключении брака (при необходимости) <?php echo isset($request_qualify, $request_qualify['file_marriage_doc']) ? '(заменить)' : '' ?></span>
                                            <span class="input-file-wrap">
                                            <input type="file" name="file_marriage_doc">
                                                <?php if (isset($request_qualify, $request_qualify['file_marriage_doc'])) { ?>
                                                    <a href="/lk-user-file/?filename=<?php echo $request_qualify['file_marriage_doc'] ?>" target="_blank">Просмотр</a>
                                                    <button class="button-delete" data-file-input="file_marriage_doc" type="button">Удалить</button>
                                                <?php } ?>
                                                <input type="hidden" name="file_marriage_doc-deleted">
                                        </span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                    <div class="request-speaker request-block">
                        <fieldset>
                            <legend class="legent">Хочу стать спикером</legend>
                            <table class="request-speaker form-table">
                                <?php if (!empty($requests_speaker)) {
                                    foreach ($requests_speaker as $key => $request) { ?>
                                        <tr>
                                            <td>
                                                <input type="hidden" name="speaker_id[]" value="<?php echo $request['id'] ?>">
                                                <label>
                                                    <span class="title inline-title">Мероприятие</span>
                                                    <span class="input-select-wrap">
                                            <select name="id_event[]">
                                                <option value="null">Не выбрано</option>
                                                <?php
                                                $repeats = [];
                                                foreach ($events as $event) {
                                                    if (($event['type'] != 'Трек' && $event['type'] != 'Шорт-трек') || ($event['type'] == 'Трек' && in_array($event['track'], $repeats))) {
                                                        continue;
                                                    }
                                                    $repeats[] = $event['track'];
                                                    $selected = $request['event_id'] == $event['id'] ? 'selected' : '';
                                                    $track = isset($event['short_track']) ? $event['short_track'] . ' шорт-трек ' . $event['track'] . ' трека ' : $event['track'] . ' трек ';
                                                    echo '<option value="', $event['id'], '" title="', $event['title'], '"', $selected, '>',
                                                    $track, '</option>';
                                                } ?>
                                            </select>
                                        </span>
                                                </label>
                                            </td>
                                            <td>
                                                <label>
                                                    <span class="title inline-title">Выступление</span>
                                                    <span class="input-select-wrap">
                                                    <select name="show_type[]">
                                                        <?php
                                                        $types = ['Доклад', 'Мастер-класс', 'Визионерская лекция', 'Фокус-сессия', 'Экспертно-аналитическая сессия', 'Форсайт-сессия', 'Проектная сессия', 'Другое'];
                                                        foreach ($types as $type) {
                                                            $selected = $request['type'] == $type ? 'selected' : '';
                                                            echo '<option value="', $type, '"', $selected, '>', $type, '</option>';
                                                        } ?>
                                                    </select>
                                                </span>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label>
                                                    <span class="title inline-title">Название выступления</span>
                                                    <span class="input-text-wrap"><input type="text" name="show_name[]" value="<?php echo $request['show_name'] ?>"></span>
                                                </label>
                                            </td>
                                            <td>
                                                <label>
                                                    <span class="title inline-title">Презентация</span>
                                                    <span class="input-file-wrap"><input type="file" name="file_presentation[]"></span>
                                                    <?php if (isset($request['file_presentation'])) { ?>
                                                        <a href="/lk-user-file/?filename=<?php echo $request['file_presentation'] ?>" target="_blank">Просмотр</a>
                                                        <button class="button-delete" data-file-input="file_presentation-<?php echo $key?>" type="button">Удалить</button>
                                                    <?php } ?>
                                                    <input type="hidden" name="file_presentation-<?php echo $key?>-deleted">
                                                </label>
                                            </td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td>
                                            <label>
                                                <span class="title inline-title">Мероприятие</span>
                                                <span class="input-select-wrap">
                                            <select name="id_event[]">
                                                <option value="null">Не выбрано</option>
                                                <?php
                                                $repeats = [];
                                                foreach ($events as $event) {
                                                    if (($event['type'] != 'Трек' && $event['type'] != 'Шорт-трек') || ($event['type'] == 'Трек' && in_array($event['track'], $repeats))) {
                                                        continue;
                                                    }
                                                    $track = isset($event['short_track']) ? $event['short_track'] . ' шорт-трек ' . $event['track'] . ' трека ' : $event['track'] . ' трек ';
                                                    echo '<option value="', $event['id'], '" title="', $event['title'], '">',
                                                    $track, '</option>';
                                                    $repeats[] = $event['track'];
                                                } ?>
                                            </select>
                                        </span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <span class="title inline-title">Выступление</span>
                                                <span class="input-select-wrap">
                                            <select name="show_type[]">
                                                <?php
                                                $types = ['Доклад', 'Мастер-класс', 'Визионерская лекция', 'Фокус-сессия', 'Экспертно-аналитическая сессия', 'Форсайт-сессия', 'Проектная сессия', 'Другое'];
                                                foreach ($types as $type) {
                                                    echo '<option value="', $type, '">', $type, '</option>';
                                                } ?>
                                            </select>
                                        </span>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>
                                                <span class="title inline-title">Название выступления</span>
                                                <span class="input-text-wrap"><input type="text" name="show_name[]"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <span class="title inline-title">Презентация</span>
                                                <span class="input-file-wrap">
                                            <input type="file" name="file_presentation[]">
                                        </span>
                                            </label>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </fieldset>
                    </div>
                    <div class="request-final   request-block">
                        <fieldset>
                            <table class="request-default form-table">
                                <tr>
                                    <td>
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
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                    <input type="submit" class="disabled" value="Сохранить" id="request-save">
                </form>
            <?php } ?>
        </div>
    </div>
    <div class="tab map-tab" <?php echo isset($request_default) ? '' : 'style="display: none"' ?>>
        <?php if (!empty($request_default)) { ?>
            <div class="map current-event-bar">
                <?php foreach ($events as $event) {
                    if ($event['selected']) {
                        $date = DateTime::createFromFormat('Y-m-d', $event['date']);
                        $date = $date->format('d.m');
                        ?>
                        <div class="current-event">
                            <p>Ближайшее мероприятие:</p>
                            <p class="title">
                                <?php echo $event['title'] ?>
                            </p>
                            <p class="time-event">
                                Время проведения:
                                <?php echo $date, ', ', mb_substr($event['time_start'], 0, 5), '-', mb_substr($event['time_end'], 0, 5) ?>
                            </p>
                        </div>
                        <?php break;
                    }
                } ?>
            </div>
            <?php if (isset($request_live)) { ?>
                <div class="map curator-info">
                    <p class="curator-title title">Ваш персональный куратор для решения организационных вопросов:</p>
                    <?php if (new DateTime('now') < new DateTime('2024-08-01')) { ?>
                        <p>Контактные данные появятся с 01.08.2024 <i>в случае очного участия</i>.</p>
                    <?php } else {
                        echo '<p>Ваш куратор: ', $request_live['curator_fio'], '.</p>',
                        '<p>Вы можете связаться с ним по email: ', $request_live['curator_email'], ' или по телефону: ', $request_live['curator_phone'], '.</p>';
                    } ?>
                </div>
            <?php } ?>
            <div class="event-map map">
                <form id="map-form" method="post">
                    <input type="hidden" name="action" value="map">
                    <?php
                    $repeats = [];
                    $events_list = [];
                    foreach ($events as $event) {
                        $date = DateTime::createFromFormat('Y-m-d', $event['date']);
                        $date = $date->format('d.m.y');
                        $event['time_start'] = mb_substr($event['time_start'], 0, 5);
                        $event['time_end'] = mb_substr($event['time_end'], 0, 5);
                        if ($event['type'] == 'Трек') {
                            $event['event'] = $event['track'] . ' трек. ';
                        } elseif ($event['type'] == 'Шорт-трек') {
                            $event['event'] = $event['short_track'] . ' шорт-трек ' . $event['track'] . ' трека. ';
                        }
                        $events_list[$date][$event['time_start']][] = $event;
                    }
                    ?>
                    <ul>
                        <?php foreach ($events_list as $day => $times) { ?>
                            <li class="event-map event-day">
                                <p><?php echo $day ?></p>
                                <div class="event-city"><?php echo $times[array_key_first($times)][0]['place_city'] ?></div>
                                <ul>
                                    <?php foreach ($times as $time => $events_) { ?>
                                        <li class="event-map event-time">
                                            <table>
                                                <tr>
                                                    <td>
                                                        <div class="event-time-time">
                                                            <?php echo $events_[0]['time_start'], '-', $events_[0]['time_end'] ?>
                                                        </div>
                                                    </td>
                                                    <td class="events-container">
                                                        <?php if (count($events_) > 1) {
                                                            foreach ($events_ as $event) { ?>
                                                                <div class="event-map event-block" data-event-track="<?php echo $event['track'] ?>">
                                                                    <input type="checkbox" id="cb<?php echo $event['id'] ?>" value="<?php echo $event['id'] ?>" name="events[]" <?php echo $event['selected'] ? 'checked' : '' ?>>
                                                                    <label for="cb<?php echo $event['id'] ?>" data-start="<?php echo $event['date'], 'T', $event['time_start'] ?>"
                                                                           data-end="<?php echo $event['date'], 'T', $event['time_end'] ?>">
                                                                        <?php if (!in_array($event['track'], $repeats) || $event['type'] != 'Трек') { ?>
                                                                            <span class="event-track">
                                                                            <?php echo $event['event'] ?? '' ?>
                                                                    </span>
                                                                            <span class="event-title">
                                                                            <?php
                                                                            $repeats[] = $event['track'];
                                                                            echo $event['title'];
                                                                            ?>
                                                                    </span>
                                                                        <?php } ?>
                                                                        <span class="event-place">
                                                                        <?php echo $event['place_addr'], ', ', $event['place_room'] ?>
                                                                    </span>
                                                                        <span class="event-desc">
                                                                    <?php if (!empty($event['title']) && !empty($event['description'])) {
                                                                        echo $event['description'];
                                                                    } ?>
                                                                </span>
                                                                    </label>
                                                                </div>
                                                            <?php }
                                                        } else { ?>
                                                            <div class="inline-event-container">
                                                                <?php if ($events_[0]['type'] == 'Пленарное заседание') { ?>
                                                                <input type="checkbox" id="cb<?php echo $events_[0]['id'] ?>" value="<?php echo $events_[0]['id'] ?>" name="events[]" <?php echo $events_[0]['selected'] ? 'checked' : '' ?>>
                                                                <label for="cb<?php echo $events_[0]['id'] ?>" data-start="<?php echo $events_[0]['date'], 'T', $events_[0]['time_start'] ?>"
                                                                       data-end="<?php echo $events_[0]['date'], 'T', $events_[0]['time_end'] ?>">
                                                                    <?php }
                                                                    echo empty($events_[0]['title']) ? $events_[0]['type'] : $events[0]['title'] ?>
                                                                </label>
                                                            </div>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                    </ul>
                </form>
                <div class="button-save wrap">
                    <button id="map-save">Сохранить мой выбор</button>
                    <span class="map-warning" style="display: none">Выбранные вами события накладываются по времени</span>
                </div>
            </div>
        <?php } else { ?>
            <div class="map no-request">
                Маршрутная карта станет доступна после заполнения профиля.
            </div>
        <?php } ?>
    </div>
</div>

