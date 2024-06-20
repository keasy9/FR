<?php

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class fr_participant_table_builder extends WP_List_Table
{
    public static $participant;

    public function __construct()
    {
        parent::__construct(['participants', 'participant']);

        if (empty(self::$participant)) {
            self::$participant = new fr_participant_model();
        }

    }

    public function prepare_items()
    {
        // Получаем данные для формирования таблицы
        $data = $this->table_data();
        usort($data, [$this, 'usort_reorder']);

        // Устанавливаем данные для пагинации
        $per_page = 20;
        $this->set_pagination_args([
            'total_items' => count($data),
            'per_page' => $per_page
        ]);

        // Делим массив на части для пагинации
        $data = array_slice($data, (($this->get_pagenum() - 1) * $per_page), $per_page);

        // Устанавливаем данные колонок
        $this->_column_headers = [
            $this->get_columns(), // Получаем массив названий колокнок
            $this->get_hidden_columns(), // Получаем массив названий колонок которые нужно скрыть
            $this->get_sortable_columns() // Получаем массив названий колонок которые можно сортировать
        ];

        // Устанавливаем данные таблицы
        $this->items = $data;
    }

    public function get_columns()
    {
        return [
            'id' => 'id',
            'user_id' => 'user_id',
            'cb' => '<input type="checkbox">',
            'name' => 'ФИО',
            'contact' => 'Контактные данные',
            'register_date' => 'Регистрационные данные',
            'city' => 'Город',
            'job' => 'Должность',
            'sci' => 'Ученое звание',
            'part_form' => 'Форма участия',
            'part_type' => 'Категория',
            'curator' => 'Куратор',
            'events_map' => 'Участие в мероприятиях',
            'additional_parts' => 'Дополнительное участие',
            'docs' => 'Файлы пользователя'
        ];
    }

    public function column_cb($item)
    {
        return '<input type="checkbox" name="id[]" value="' . $item['id'] . '" >';
    }

    public function get_hidden_columns()
    {
        return [
            'id',
            'user_id',
            'registered_email',
        ];
    }

    public function get_sortable_columns()
    {
        return [
            'name' => ['name', true],
            'register_date' => ['register_date', false],
            'birth_date' => ['birth_date', false],
            'sex' => ['sex', false],
            'city' => ['city', false],
            'part_form' => ['part_form', false],
            'part_type' => ['part_type', false]
        ];
    }

    private function table_data()
    {
        return self::$participant::select();
    }

    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'docs':
                $links = '';
                foreach ($item['docs'] as $name => $file) {
                    if (empty($file)) {
                        continue;
                    }
                    $links .= '<li><a target="_blank" href="/lk-user-file?filename=' . $file . '&uid=' . $item['user_id'] . '">' . $name . '</a></li>';
                }
                if (empty($links)) {
                    return 'Нет';
                }
                return '<ul>' . $links . '</ul>';
            case 'birth_date':
                $date = DateTime::createFromFormat('Y-m-d', $item['birth_date']);
                if ($item['birth_date'] == '0000-00-00' || !$date) {
                    return 'Не указана';
                }
                return $date->format('d.m.y');
            case 'register_date':
                $date = DateTime::createFromFormat('Y-m-d H:i:s', $item['register_date']);
                if (!$date) {
                    return 'ошибка';
                }
                $str = $date->format('d.m.y');
                return 'Email: <nobr>' . $item['registered_email'] . "</nobr><br>Дата рег.:" .  $str;
            case 'curator':
                if ($item['part_form'] == 'Дистанционно') {
                    return '-';
                } elseif (empty($item['curator'])) {
                    return 'Не назначен';
                } else {
                    return $item['curator'];
                }
            case 'events_map':
                if (empty($item['events_map'])) {
                    return 'Нет';

                }
                $events = [];
                foreach ($item['events_map'] as $event) {
                    if ($event['type'] === 'Программное мероприятие') {
                        $events[] = "<li>Программное мероприятие {$event['track']} сессии</li>";
                    } elseif ($event['type'] === 'Другое') {
                        $events[] = '<li>' . $event['type_custom'] ?? $event['type'] . '</li>';
                    }
                }
                return '<ul>' . implode('', $events) . '</ul>';
            case 'part_type':
                $tpl = $item['part_type']['type'];
                if (empty($item['part_type']['shows'])) {
                    return $tpl;
                }
                $tpl .= '<br>';
                $tpl .= '<ul>';
                foreach ($item['part_type']['shows'] as $show) {
                    $tpl .= '<li>' . $show['show_type'] . ' ';
                    if (!empty($show['show_name'])) {
                        $tpl .= '&quot;' . $show['show_name'] . '&quot;';
                    }
                    if (!empty($show['id_event'])) {
                        $tpl .= ' в ';
                        if ($show['type'] == 'Трек') {
                            $tpl .= $show['track'] . ' треке';
                        } elseif ($show['type'] == 'Шорт-трек') {
                            $tpl .= $show['short-track'] . ' шорт-треке ' . $show['track'] . ' трека';
                        }
                    } else {
                        $tpl .= ' мероприятие не выбрано';
                    }
                    if (!empty($show['file_presentation'])) {
                        $tpl .= '<br><a href="/lk-user-file/?filename=' . $show['file_presentation'] . '&uid=' . $item['user_id'] . '">Презентация</a>';
                    } else {
                        $tpl .= '<br> Презентация не прикреплена.';
                    }
                    $tpl .= '</li>';
                }
                $tpl .= '</ul>';
                return $tpl;
            default:
                return $item[$column_name] ?? '-';
        }
    }

    public function usort_reorder($a, $b)
    {
        // Если не отсортировано, по умолчанию name
        $orderby = $_GET['orderby'] ?? 'name';
        // Если не отсортировано, по умолчанию asc
        $order = $_GET['order'] ?? 'asc';
        // Определяем порядок сортировки
        if ($orderby === 'part_type') {
            $result = strcmp($a[$orderby]['type'], $b[$orderby]['type']);
        } else {
            $result = strcmp($a[$orderby], $b[$orderby]);
        }
        // Отправляем конечный порядок сортировки usort
        return ($order === 'asc') ? $result : -$result;
    }

    function get_bulk_actions()
    {
        return [
            'set_curator' => __('Назначить куратора', 'plance'),
            'remove_curator' => __('Отменить куратора', 'plance')
        ];
    }
}