<?php

//массив и изменениями данных для отправки изменений на почту
$dataUpdated = [];


$user = wp_get_current_user();

$id = $user->ID;
$f_name = $user->user_nicename;
$email = $user->user_email;

$files_dir = PLUGIN_PATH . 'user-uploaded-files' . DIRECTORY_SEPARATOR;

global $wpdb;

$user_photo = $wpdb->get_var($wpdb->prepare('SELECT file_photo FROM fr_profile_photos WHERE user_id = %d', $id));
$request_default = $wpdb->get_row(
    $wpdb->prepare('SELECT * FROM fr_request_default WHERE id_user = %d', [$id]),
    ARRAY_A
);

$events = $wpdb->get_results(
    'SELECT *, CAST(type AS UNSIGNED) AS type_id FROM fr_event ORDER BY date, time_start, COALESCE(track, 99), COALESCE(short_track, 99)',
    ARRAY_A
);
$events_tracks = $wpdb->get_results(
    'SELECT * FROM fr_event WHERE type = \'Программное мероприятие\' AND track IS NOT NULL ORDER BY date, time_start',
    ARRAY_A
);
$events_selectected = $wpdb->get_results(
    $wpdb->prepare('SELECT * FROM fr_event_map WHERE user_id = %d', $id),
    ARRAY_A
);
foreach ($events as $key => $event) {
    $events[$key]['description'] = str_replace('"', '&quot;', $event['description']);
    $events[$key]['selected'] = false;
    foreach ($events_selectected as $i => $sel) {
        if ($sel['event_id'] == $event['id']) {
            $events[$key]['selected'] = true;
            unset($events_selectected[$i]);
            break;
        }
    }
}
//$qualify_progs = $wpdb->get_results('SELECT * FROM fr_qualify_program', ARRAY_A);


if (!empty($request_default)) {
    $request_id = $request_default['id'];
    $request_live = $wpdb->get_row(
        $wpdb->prepare('SELECT * FROM `fr_request_live` WHERE id_request_default = %d', [$request_id]),
        ARRAY_A
    );
    $request_qualify = $wpdb->get_row(
        $wpdb->prepare('SELECT * FROM fr_request_qualify WHERE id_request_default = %d', [$request_id]),
        ARRAY_A
    );
    $requests_speaker = $wpdb->get_results(
        $wpdb->prepare('SELECT * FROM fr_request_speaker WHERE id_request_default = %d', [$request_id]),
        ARRAY_A
    );

    //выбранные мероприятия
    $selected_events_query = "
SELECT fe.*, CAST(type AS UNSIGNED) AS type_id
FROM fr_event fe
         RIGHT JOIN fr_event_map fem ON fe.id = fem.event_id
WHERE fem.user_id = %d
ORDER BY date, time_start, NULLIF(track, 0), NULLIF(short_track, 0)";
    $selected_events = $wpdb->get_results(
        $wpdb->prepare($selected_events_query, [$id]),
        ARRAY_A
    );
}

if (!empty($_POST['action']) && $_POST['action'] == 'request') {
    foreach ($_POST as &$value) {
        $value = str_replace('\"', '"', $value);
    }

    $dir = PLUGIN_PATH . 'user-uploaded-files' . DIRECTORY_SEPARATOR . get_current_user_id() . DIRECTORY_SEPARATOR;
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $filename = $wpdb->get_var($wpdb->prepare('SELECT file_photo FROM fr_profile_photos WHERE user_id = %d', [$id]));
    if (
        (isset($_POST['user-photo-deleted']) && $_POST['user-photo-deleted'] == 'delete') ||
        (isset($_FILES['user-photo']) && $_FILES['user-photo']['error'] == UPLOAD_ERR_OK && !empty($filename))
    ) {
        if (file_exists(PLUGIN_PATH . 'user-uploaded-files' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . $filename)) {
            unlink(PLUGIN_PATH . 'user-uploaded-files' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . $filename);
        }
        $wpdb->delete('fr_profile_photos', ['user_id' => $id]);
        
        //удалено фото профиля
        //$dataUpdated['user_photo'] = ['status' => 'deleted'];
    }
    if (isset($_FILES['user-photo']) && $_FILES['user-photo']['error'] == UPLOAD_ERR_OK) {
        //обработка кириллицы
        $filename_parts = pathinfo(sanitize_file_name($_FILES['user-photo']['name']));

        $filename = $filename_parts['filename'] . '.' . $filename_parts['extension'];
        if (move_uploaded_file(
            $_FILES['user-photo']['tmp_name'],
            PLUGIN_PATH . 'user-uploaded-files' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . $filename
        )) {
            $wpdb->insert('fr_profile_photos', ['user_id' => $id, 'file_photo' => $filename], ['%d', '%s']);
            
            //обновлено фото профиля
            //$dataUpdated['user_photo'] = ['status' => 'updated'];
        }
    }

    foreach (['file_pcr', 'file_vaccine', 'file_diplom', 'file_snils', 'file_marriage_doc'] as $filename) {
        if (isset($_POST[$filename . '-deleted']) && $_POST[$filename . '-deleted'] == 'delete') {
            $$filename = '';
            if (!empty($request_default)) {
                $table = 'fr_request_qualify';
                if (in_array($filename, ['file_pcr', 'file_vaccine'])) {
                    $table = 'fr_request_live';
                }
                $filename = $wpdb->get_var($wpdb->prepare('SELECT ' . $filename . ' FROM ' . $table . 'WHERE id_request_default = %d', [$request_id]));
                if (!empty($filename)) {
                    unlink(PLUGIN_PATH . 'user-uploaded-files' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . $filename);
                    //$dataUpdated[$filename] = ['status' => 'deleted'];
                }
            }
        }
        if (isset($_FILES[$filename]) && $_FILES[$filename]['error'] == UPLOAD_ERR_OK) {
            //обработка кириллицы
            $filename_parts = pathinfo(sanitize_file_name($_FILES[$filename]['name']));
            $$filename = $filename_parts['filename'] . '.' . $filename_parts['extension'];
            if(move_uploaded_file(
                $_FILES[$filename]['tmp_name'],
                PLUGIN_PATH . 'user-uploaded-files' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . $$filename
            )) {
                //$dataUpdated[$filename] = ['status' => 'updated'];
            }
        }
    }

    $data = [
        's_name'     => $_POST['s_name'],
        'f_name'     => $_POST['f_name'] ?? $f_name,
        'l_name'     => empty($_POST['l_name']) ? null : $_POST['l_name'],
        'birth_date' => empty($_POST['birth_date']) ? null : $_POST['birth_date'],
        'sex'        => $_POST['sex'],
        'city'       => $_POST['city'],
        'org_name'   => $_POST['org_name'],
        'org_type'   => $_POST['org_type'],
        'org_place'  => $_POST['org_place'],
        'sci_degree' => empty($_POST['sci_degree']) ? null : $_POST['sci_degree'],
        'sci_title'  => $_POST['sci_title'],
        'part_form'  => $_POST['part_form'],
        'part_type'  => $_POST['part_type'],
        'email'      => $_POST['email'] ?? $email,
        'phone'      => $_POST['phone'],
    ];
    $format = ['%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s'];
    if (!empty($request_default)) {
        $debug = $wpdb->update('fr_request_default', $data, ['id_user' => $id], $format, '%d');
        
        //обновлен профиль
        foreach ($data as $key => $value) {
            if($data[$key] != $request_default[$key]) {
                $dataUpdated[$key] = ['status' => 'updated', 'old' => $request_default[$key], 'new' => $data[$key]];
            }
        }
        
    } else {
        $data['id_user'] = $id;
        $format[] = '%d';
        $wpdb->insert('fr_request_default', $data, $format);
        $request_id = $wpdb->insert_id;
        
        //обновлен профиль, но старые значения - пустые
        foreach ($data as $key => $value) {
            $dataUpdated[$key] = ['status' => 'updated', 'old' => null, 'new' => $data[$key]];
        }
    }

    if ($_POST['part_form'] == 0) {
        $wpdb->delete('fr_request_live', ['id_request_default' => $request_id], '%d');
        goto speaker;
    }

    $data = [
        'part_qualify'    => /*$_POST['part_qualify']*/
            '0',
        'required_invite' => $_POST['required_invite'] ?? '0',
        'datetime_come'   => empty($_POST['datetime_come']) ? null : $_POST['datetime_come'],
        'place_come'      => empty($_POST['place_come']) ? null : $_POST['place_come'],
        'flight_come'     => empty($_POST['flight_come']) ? null : $_POST['flight_come'],
        'datetime_gone'   => empty($_POST['datetime_gone']) ? null : $_POST['datetime_gone'],
        'place_gone'      => empty($_POST['place_gone']) ? null : $_POST['place_gone'],
        'flight_gone'     => empty($_POST['flight_gone']) ? null : $_POST['flight_gone'],
        'part_excursion'  => $_POST['part_excursion'],
        'part_dinner'     => $_POST['part_dinner'],
        'part_reception'  => $_POST['part_reception'],
    ];
    $format = ['%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d'];
    if (isset($file_pcr)) {
        $data['file_pcr'] = $file_pcr;
        $format[] = '%s';
    }
    if (isset($file_vaccine)) {
        $data['file_vaccine'] = $file_vaccine;
        $format[] = '%s';
    }
    if (!empty($request_default) && $request_live) {
        $wpdb->update('fr_request_live', $data, ['id_request_default' => $request_id], $format, '%d');
        
        //обновлен профиль для очного участия
        foreach ($data as $key => $value) {
            if($data[$key] != $request_live[$key]) {
                $dataUpdated[$key] = ['status' => 'updated', 'old' => $request_live[$key], 'new' => $data[$key]];
            }
        }
        
    } else {
        $data['id_request_default'] = $request_id;
        $format[] = '%d';
        $wpdb->insert('fr_request_live', $data, $format);
        
        //обновлен профиль для очного участия, но старые значения - пустые
        foreach ($data as $key => $value) {
            $dataUpdated[$key] = ['status' => 'updated', 'old' => null, 'new' => $data[$key]];
        } 
    }

    // чтобы отключить повышение квалификации
    goto speaker;

    if ($_POST['part_qualify'] == 0) {
        $wpdb->delete('fr_request_qualify', ['id_request_default' => $request_id], '%d');
        goto speaker;
    }


    $data = [
        'id_qualify_program' => $_POST['id_qualify_program'],
        'nationality'        => $_POST['nationality'],
        'document'           => $_POST['document'],
        'document_ser'       => $_POST['document_ser'],
        'document_num'       => $_POST['document_num'],
        'document_reg_num'   => $_POST['document_reg_num'],
        'document_date'      => $_POST['document_date'],
        'pasport_data'       => $_POST['pasport_data'] ?? null,
    ];
    $format = ['%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'];
    if (isset($file_diplom)) {
        $data['file_diplom'] = $file_diplom;
        $format[] = '%s';
    }
    if (isset($file_snils)) {
        $data['file_snils'] = $file_snils;
        $format[] = '%s';
    }
    if (isset($file_marriage_doc)) {
        $data['file_marriage_doc'] = $file_marriage_doc;
        $format[] = '%s';
    }
    if ($request_qualify) {
        $wpdb->update('fr_request_qualify', $data, ['id_request_default' => $request_id], $format, '%d');
    } else {
        $data['id_request_default'] = $request_id;
        $format[] = '%d';
        $wpdb->insert('fr_request_qualify', $data, $format);
    }

    speaker:

    if ($_POST['part_type'] != 'Спикер') {
        goto exit_;
    }

    $old_request_speaker = $wpdb->get_results(
        $wpdb->prepare('SELECT * FROM fr_request_speaker WHERE id_request_default = %d', [$request_id]),
        ARRAY_A
    );
    if (!empty($old_request_speaker)) {
        foreach ($old_request_speaker as $old_request) {
            if ($_POST['id_event'] == 'null') {
                unset($_POST['id_event']);
            }
            $dataUpdate = [];
            if (
                (empty($_POST['speaker_id']) && !isset($_POST['show_type_' . $old_request['id']])) ||
                (!in_array($old_request['id'], $_POST['speaker_id']))
            ) {
                $wpdb->delete('fr_request_speaker', ['id' => $old_request['id']]);
                if (!empty($old_request['file_presentation'])) {
                    unlink(PLUGIN_PATH . 'user-uploaded-files' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . $old_request['file_presentation']);
                }
                //удалено выступление спикера
                $dataUpdated['speakers'][] = ['status' => 'deleted', 'old' => $old_request];
            }
            if (isset($_POST['file_presentation-' . $old_request['id'] . '-deleted']) && $_POST['file_presentation-' . $old_request['id'] . '-deleted'] == 'delete') {
                $wpdb->query($wpdb->prepare('UPDATE fr_request_speaker SET file_presentation = NULL WHERE id = %d', [$old_request['id']]));
                unlink(PLUGIN_PATH . 'user-uploaded-files' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . $old_request['file_presentation']);
            }
            if (isset($_FILES['file_presentation_' . $old_request['id']]) && $_FILES['file_presentation_' . $old_request['id']]['error'] == UPLOAD_ERR_OK) {
                if (!empty($old_request['file_presentation'])) {
                    unlink(PLUGIN_PATH . 'user-uploaded-files' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . $old_request['file_presentation']);
                }
                $filename_parts = pathinfo(sanitize_file_name($_FILES['file_presentation_' . $old_request['id']]['name']));
                $file_presentation = $filename_parts['filename'] . '.' . $filename_parts['extension'];
                $dataUpdate['file_presentation'] = $file_presentation;
                move_uploaded_file(
                    $_FILES['file_presentation_' . $old_request['id']]['tmp_name'],
                    PLUGIN_PATH . 'user-uploaded-files' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . $file_presentation
                );
            }
            foreach (['id_event', 'show_type', 'show_name', 'show_type_other'] as $column) {
                if (isset($_POST[$column . '_' . $old_request['id']]) && $_POST[$column . '_' . $old_request['id']] != $old_request['column']) {
                    if ($column == 'show_name') {
                        $_POST[$column . '_' . $old_request['id']] = $_POST[$column . '_' . $old_request['id']];
                    }
                    $dataUpdate[$column] = $_POST[$column . '_' . $old_request['id']];
                }
            }
            if (!empty($dataUpdate)) {
                $wpdb->update('fr_request_speaker', $dataUpdate, ['id' => $old_request['id']], [], ['%d']);
                
                //обновлено выступление спикера
                foreach ($dataUpdate as $key => $value) {
                    if($dataUpdate[$key] == $old_request[$key]) {
                        continue;
                    }else{
                        $dataUpdated['speakers'][] = ['status' => 'updated', 'old' => $old_request, 'new' => $dataUpdate];
                    }
                }
            }
        }
    }

    if (!empty($_POST['id_event']) && !in_array($_POST['id_event'], ['null', 0, '0'])) {
        foreach ($_POST['id_event'] as $key => $id_event) {
            unset($file_presentation);
            if ($_FILES['file_presentation']['error'][$key] == UPLOAD_ERR_OK) {
                $filename_parts = pathinfo(sanitize_file_name($_FILES['file_presentation']['name'][$key]));
                $file_presentation = $filename_parts['filename'] . '.' . $filename_parts['extension'];
                move_uploaded_file(
                    $_FILES['file_presentation']['tmp_name'][$key],
                    PLUGIN_PATH . 'user-uploaded-files' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . $file_presentation
                );
            }

            $data = [
                'id_event'           => $id_event,
                'show_type'          => $_POST['show_type'][$key] ?? null,
                'show_name'          => !empty($_POST['show_name'][$key]) ? $_POST['show_name'][$key] : null,
                'show_type_other'    => $_POST['show_type_other'][$key] ?? null,
                'id_request_default' => $request_id,
            ];
            $format = ['%d', '%s', '%s', '%s', '%d'];
            if (isset($file_presentation)) {
                $data['file_presentation'] = $file_presentation;
                $format[] = '%s';
            }
            $wpdb->insert('fr_request_speaker', $data, $format);
            
            //создано выступление спикера
            $dataUpdated['speakers'][] = ['status' => 'added', 'new' => $data];
        }
    }
    exit_:
        
                                //для тестирования
    if(!empty($dataUpdated) && !in_array($user->user_email, ['belorukov87@yandex.ru'])) {
        $headers = [
            'From: Форум "Алтай-Азия 2024" <noreply@asu.ru>',
            'content-type: text/html',
        ];
        
        ob_start();
        require_once PLUGIN_TPLS_PATH . "mail_profile_updated.php";
        $message=ob_get_contents();
        ob_end_flush();
        
        wp_mail("altaiasia2024@asu.ru", "Изменения профиля пользователя {$user->user_email}", $message, $headers);
    }

    $_SESSION['saved'] = true;
    //очищаем post и get
    //if($user->user_email != 'belorukov87@yandex.ru') {
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit();
    //} else {
        //var_dump($wpdb->$last_error);
    //}
}

if (!empty($_POST['action']) && $_POST['action'] == 'set_event') {
    if ($_POST['value'] == false || $_POST['value'] == 'false') {
        $wpdb->delete('fr_event_map', ['user_id' => $id, 'event_id' => $_POST['id']], ['%d', '%d']);
        echo 'success';
        die;
    }
    if ($wpdb->insert('fr_event_map', ['user_id' => $id, 'event_id' => $_POST['id']])) {
        echo 'success';
        die;
    } else {
        echo 'error';
        die;
    }
}

/*if (!empty($_POST['action']) && $_POST['action'] == 'map') {

    $wpdb->delete('fr_event_map', ['user_id' => $id], '%d');

    $values = [];
    foreach ($_POST['events'] as $event_id) {
        $values[] = $wpdb->prepare('(%d, %d)', $id, $event_id);
    }
    $wpdb->query('INSERT INTO fr_event_map(user_id, event_id) VALUES ' . implode(',', $values));

    $_SESSION['saved'] = 'ok';
    //очищаем post
    header('Location: ' . $_SERVER['REQUEST_URI']);

}*/

?>

