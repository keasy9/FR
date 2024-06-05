<?php

class fr_event_model
{

    public static function select($id = null)
    {
        global $wpdb;

        if ($id == null) {
            $events = $wpdb->get_results("SELECT * FROM fr_event ORDER BY date, time_start", ARRAY_A);
            foreach ($events as &$event) {
                $event['event'] = '-';
                $event['place'] = '-';
                if ($event['type'] == 'Шорт-трек') {
                    $event['event'] = 'Шорт-трек ' . $event['track'] . ' экспертной сессии';
                } elseif ($event['type'] == 'Экспертная сессия') {
                    $event['event'] = $event['track'] . ' сессия';
                } elseif (($event['type'] == 'Другое')) {
                    $event['type'] = $event['type_custom'] ?? $event['type'];
                } elseif ($event['type'] == 'Ланч-тайм') {
                    $event['title'] = $event['type'];
                }
                $event['place'] = implode(', ', [$event['place_city'], $event['place_addr'], $event['place_room']]);
            }
            return $events;
        } else {
            return $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * FROM fr_event WHERE id=%d",
                    [$id]
                ), ARRAY_A
            );
        }
    }

    public static function delete($id)
    {
        global $wpdb;

        if (is_array($id)) {
            $ids_prp = '';
            foreach ($id as $id_) {
                $ids_prp .= '%d,';
            }
            $ids_prp = mb_substr($ids_prp, 0, -1);
            return $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM fr_event
                        WHERE id IN($ids_prp)",
                    $id
                )
            );
        } else {
            return $wpdb->delete('fr_event', ['id' => $id], '%d');
        }
    }

    public static function selectTracks()
    {
        global $wpdb;

        return $wpdb->get_results(
            "select DISTINCT track from fr_event where type='Экспертная сессия' AND track IS NOT NULL AND track <> 0",
            ARRAY_A
        );
    }

    public static function save($id = null)
    {
        global $wpdb;

        $data = array_fill_keys(['date', 'time_start', 'time_end', 'type', 'type_custom', 'track', 'short_track', 'title', 'description', 'place_city', 'place_addr', 'place_room', 'speaker_allow'], null);


        // Значения по умолчанию
        $data['date'] = date('Y-m-d');
        $data['time_start'] = '00:00';
        $data['time_end'] = '00:00';
        $data['speaker_allow'] = '0';

        // Проверка входных данных
        if (isset($_POST['year'], $_POST['month'], $_POST['day']) && checkdate($_POST['month'], $_POST['day'], $_POST['year'])) {
            $_POST['date'] = implode('-', [$_POST['year'], $_POST['month'], $_POST['day']]);
        }
        if (isset($_POST['time_start_h'], $_POST['time_start_m']) && $_POST['time_start_h'] >= '0' && $_POST['time_start_h'] <= '23' && $_POST['time_start_m'] >= '0' && $_POST['time_start_m'] <= '59') {
            $_POST['time_start'] = implode(':', [$_POST['time_start_h'], $_POST['time_start_m']]);
        }
        if (isset($_POST['time_end_h'], $_POST['time_end_m']) && $_POST['time_end_h'] >= '0' && $_POST['time_end_h'] <= '23' && $_POST['time_end_m'] >= '0' && $_POST['time_end_m'] <= '59') {
            $_POST['time_end'] = implode(':', [$_POST['time_end_h'], $_POST['time_end_m']]);
        }
        if (isset($_POST['type'])) {
            if ($_POST['type'] != 'Программное мероприятие') {
                $_POST['track'] = $_POST['short_track'] = null;
            }
            if ($_POST['type'] != 'Другое') {
                $_POST['type_custom'] = null;
            }
        }
        if(in_array($_POST['track'], [0, '0', null, 'null'])){
            $_POST['track'] = null;
        }

        $data = array_merge($data, array_intersect_key(array_filter($_POST), $data));

        $id = $_POST['event_id'] ?? null;

        if ($id == null) {
            if ($wpdb->insert('fr_event', $data)) {
                $data['id'] = $wpdb->insert_id;
                return $data;
            } else {
                return false;
            }
        } else {
            return $wpdb->update('fr_event', $data, ['id' => $id]);
        }
    }

    public static function getTypes()
    {

        global $wpdb;

        $types = $wpdb->get_row('SHOW COLUMNS FROM fr_event WHERE Field = \'type\'', ARRAY_A)['Type'];
        preg_match("/^enum\(\'(.*)\'\)$/", $types, $matches);
        $types = explode("','", $matches[1]);
        return $types;
    }
}