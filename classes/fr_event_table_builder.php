<?php

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class fr_event_table_builder extends WP_List_Table
{
    public static $event;

    public function __construct()
    {
        parent::__construct(['events', 'event']);

        if (empty(self::$event)) {
            self::$event = new fr_event_model();
        }
        if (isset($_GET['action']) && $_GET['action'] == 'delete') {
            if (isset($_GET['event'])) {
                $this->deleteEventItem($_GET['event']);
            } elseif (isset($_GET['id'])) {
                $this->deleteEventItems($_GET['id']);
            }
        }
    }

    private function deleteEventItem($id)
    {
        if (self::$event::delete($id)) {
            $GLOBALS['fr_action_ok'] = 'Событие успешно удалено.';
        }
    }

    private function deleteEventItems($ids)
    {
        if (self::$event::delete($ids)) {
            $GLOBALS['fr_action_ok'] = 'События(' . count($ids) . ') успешно удалены.';
        }
    }

    public function prepare_items()
    {
        // Получаем данные для формирования таблицы
        $data = $this->table_data();
        if (!empty($_GET['orderby'])) {
            usort($data, [$this, 'usort_reorder']);
        }

        // Устанавливаем данные колонок
        $this->_column_headers = [
            $this->get_columns(), // Получаем массив названий колокнок
            $this->get_hidden_columns(), // Получаем массив названий колонок которые нужно скрыть
            $this->get_sortable_columns() // Получаем массив названий колонок которые можно сортировать
        ];

        // Устанавливаем данные таблицы
        $this->items = $data;
    }

    private function table_data()
    {
        return self::$event::select();
    }

    public function get_columns()
    {
        return [
            'id'          => 'id',
            'cb'          => '<input type="checkbox">',
            'date'        => 'Дата проведения',
            'time_start'  => 'Время начала',
            'time_end'    => 'Время окончания',
            'type'        => 'Тип мероприятия',
            'track'       => 'Экспертная сессия',
            'title'       => 'Название мероприятия',
            'description' => 'Описание',
            'place'       => 'Место проведения',
        ];
    }

    public function get_hidden_columns()
    {
        return [
            'id',
            'type_custom',
            'event',
            'short_track',
        ];
    }

    public function get_sortable_columns()
    {
        return [
            /*'date'  => ['date', true],
            'event' => ['event', false],
            'type'  => ['type', false],*/
        ];
    }

    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'date':
                $actions = [
                    'edit'   => sprintf('<a href="?page=event_edit&event=%s">Редактировать</a>', $item['id']),
                    'delete' => sprintf('<a href="?page=%s&action=%s&event=%s">Удалить</a>', $_REQUEST['page'], 'delete', $item['id']),
                ];
                return sprintf('%1$s %2$s', $item['date'], $this->row_actions($actions));
            default:
                return $item[$column_name] ?? '-';
        }
    }

    public function column_cb($item)
    {
        return '<input type="checkbox" name="id[]" value="' . $item['id'] . '" >';
    }

    public function usort_reorder($a, $b)
    {
        // Если не отсортировано, по умолчанию date
        $orderby = $_GET['orderby'] ?? 'date';
        // Если не отсортировано, по умолчанию asc
        $order = $_GET['order'] ?? 'asc';
        // Определяем порядок сортировки
        $result = strcmp($a[$orderby], $b[$orderby]);
        // Отправляем конечный порядок сортировки usort
        return ($order === 'asc') ? $result : -$result;
    }

    function get_bulk_actions()
    {
        return [
            'delete' => __('Удалить', 'plance'),
        ];
    }
}