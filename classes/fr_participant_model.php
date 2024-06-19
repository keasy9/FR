<?php

class fr_participant_model
{
    public static function select()
    {
        global $wpdb;

        $filter = '';
        if (!empty($_GET['filter']) && $_GET['filter'] != 'all') {
            switch ($_GET['filter']) {
                case 'part_type_live':
                    $filter = $wpdb->prepare('WHERE frd.part_form = 1');
                    break;
                case 'part_type_remote':
                    $filter = $wpdb->prepare('WHERE frd.part_form = 0');
                    break;
                case 'empty_profile':
                    $filter = $wpdb->prepare('WHERE NOT EXISTS(SELECT id from fr_request_default frd2 WHERE frd2.id_user = wp_users.ID)');
                    break;
                case 'not_empty_profile':
                    $filter = $wpdb->prepare('WHERE EXISTS(SELECT id from fr_request_default frd2 WHERE frd2.id_user = wp_users.ID)');
                    break;
                case 'speakers':
                    $filter = $wpdb->prepare('WHERE part_type = \'Спикер\'');
                    break;
            }
        }
        $participants = $wpdb->get_results(
            '
            SELECT wp_users.ID as user_id, wp_users.user_email, frd.id, s_name, f_name, l_name, email, phone, user_registered, birth_date, sex, city, org_place, 
                   org_name, org_type, sci_degree, sci_title, part_form, part_qualify, part_excursion, part_dinner,
                   file_photo, file_diplom, file_marriage_doc, '/*file_pcr,*/.' file_snils, '/*file_vaccine,*/.' curator_phone, curator_email, curator_fio, part_type
            FROM wp_users
                LEFT JOIN fr_profile_photos fpp on wp_users.ID = fpp.user_id
                LEFT JOIN fr_request_default frd on wp_users.ID = frd.id_user
                LEFT JOIN fr_request_live frl on frd.id = frl.id_request_default
                LEFT JOIN fr_request_qualify frq on frd.id = frq.id_request_default
        ' . $filter,
            ARRAY_A
        );
        $fr_event_map_ = $wpdb->get_results('SELECT * FROM fr_event_map LEFT JOIN fr_event ON fr_event_map.event_id = fr_event.id ORDER BY time_start', ARRAY_A);
        $fr_event_map = [];
        foreach ($fr_event_map_ as $map) {
            $fr_event_map[$map['user_id']][] = $map;
        }

        $spkr_requests_ = $wpdb->get_results('SELECT * FROM fr_request_speaker JOIN fr_event fe ON fr_request_speaker.id_event = fe.id', ARRAY_A);
        $spkr_requests = [];
        foreach ($spkr_requests_ as $request) {
            $spkr_requests[$request['id_request_default']][] = $request;
        }

        $return = [];
        foreach ($participants as $key => $user) {
            if (
                (!empty($_GET['filter']) && $_GET['filter'] == 'no_verified' && !user_can($user['user_id'], 'need-confirm')) ||
                (!user_can($user['user_id'], 'contributor') && !user_can($user['user_id'], 'need-confirm'))
            ) {
                continue;
            }
            //$additionalParts = 'Программа повышения квалификации: ' . ($user['part_qualify'] == '1' ? 'Да' : 'Нет');
            //$additionalParts .= "<br>" . 'Прием 15.09.22: ' . ($user['part_dinner'] == '1' ? 'Да' : 'Нет');
            $additionalParts /*.*/= /*"<br>" . */'Ужин 03.10.24: ' . ($user['part_dinner'] == '1' ? 'Да' : 'Нет');
            $additionalParts .= "<br>" . 'Экскурсия 04.10.24: ' . ($user['part_excursion'] == '1' ? 'Да' : 'Нет');
            $return[] = [
                'id'               => $user['id'],
                'user_id'          => $user['user_id'],
                'registered_email' => $user['user_email'],
                'name'             => $user['s_name'] . ' ' . $user['f_name'] . ' ' . $user['l_name'],
                'contact'          => $user['email'] . "\n\r" . $user['phone'],
                'register_date'    => $user['user_registered'],
                'birth_date'       => $user['birth_date'],
                'sex'              => $user['sex'] == '1' ? 'Мужской' : 'Женский',
                'city'             => $user['city'],
                'job'              => $user['org_place'] . (empty($user['org_name']) ? '' : ' в ' . $user['org_name'] .(empty($user['org_type']) ? '' :' (' . $user['org_type'] . ')')),
                'sci'              => $user['sci_title'] . (!empty($user['sci_degree']) ? ' (' . $user['sci_degree'] . ')' : ''),
                'part_form'        => $user['part_form'] == '1' ? 'Очно' : 'Дистанционно',
                'additional_parts' => $additionalParts,
                'docs'             => [
                    'Фото'                        => $user['file_photo'],
                    'ПЦР-тест'                    => $user['file_pcr'],
                    'Сертификат вакцинации'       => $user['file_vaccine'],
                    'Копия диплома'               => $user['file_diplom'],
                    'Копия СНИЛСа'                => $user['file_snils'],
                    'Документ о заключении брака' => $user['file_marriage_doc'],
                ],
                'curator'          => (!empty($user['curator_fio']) ? $user['curator_fio'] . '<br>' : '') . (!empty($user['curator_phone']) ? $user['curator_phone'] . '<br>' : '') . (!empty($user['curator_email']) ? $user['curator_email'] : ''),
                'events_map'       => $fr_event_map[$user['user_id']] ?? null,
                'part_type'        => ['type' => $user['part_type'], 'shows' => !empty($spkr_requests[$user['id']]) ? $spkr_requests[$user['id']] : null],
            ];
        }

        return $return;
    }

    public static function remove_curator($ids)
    {
        if (empty($ids)) {
            return false;
        }
        $whereIds = [];
        foreach ($ids as $id) {
            $whereIds[] = '%d';
        }
        global $wpdb;
        return $wpdb->query(
            $wpdb->prepare(
                '
            UPDATE fr_request_live 
            SET curator_email = null, curator_fio = null, curator_phone = null 
            WHERE id_request_default IN (' . implode(', ', $whereIds) . ')
            ',
                $ids
            )
        );
    }

    public static function set_curator($ids, $curator_info)
    {
        global $wpdb;

        if (empty($ids)) {
            return false;
        }

        $query = $params = [];
        foreach ($ids as $id) {
            $query[] = '(%s, %s, %s, %d)';
            $params[] = $curator_info['curator_fio'];
            $params[] = $curator_info['curator_phone'];
            $params[] = $curator_info['curator_email'];
            $params[] = $id;
        }
        $params[] = $curator_info['curator_fio'];
        $params[] = $curator_info['curator_phone'];
        $params[] = $curator_info['curator_email'];

        return $wpdb->query(
            $wpdb->prepare(
            "INSERT INTO fr_request_live (curator_fio, curator_phone, curator_email, id_request_default)
                VALUES " . implode(',', $query) . "
                    AS new
                ON DUPLICATE KEY UPDATE 
                    curator_fio = %s,
                    curator_phone = %s,
                    curator_email = %s,
                    id_request_default = new.id_request_default",
                $params
            )
        );
    }

}