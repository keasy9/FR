<?php

class fr_page_init
{
    public function __call(string $name, array $arguments)
    {
        $tpl = PLUGIN_TPLS_PATH . $name . '.php';
        if (file_exists($tpl)) {
            require $tpl;
        } else {
            die('страницы не существует, но она в разработке');
        }
    }

    public function event_list()
    {
        $table = new fr_event_table_builder();
        $table->prepare_items();

        include PLUGIN_TPLS_PATH . 'event_table.php';
    }

    public function event_edit()
    {
        if (!empty($_POST)) {
            if (fr_event_model::save($_GET['event'] ?? $_POST['event_id'] ?? null)) {
                global $wpdb;
                $GLOBALS['fr_action_ok'] = 'Запись успешно сохранена.';
                $id = $wpdb->insert_id == 0 || $wpdb->insert_id == null ? $_POST['event_id'] : $wpdb->insert_id;
                $event = fr_event_model::select($id);
            }
        }

        if (isset($_GET['event'])) {
            $event = fr_event_model::select($_GET['event']);
        }

        $event_types = fr_event_model::getTypes();
        $tracks = fr_event_model::selectTracks();

        include PLUGIN_TPLS_PATH . 'event_edit.php';
    }

    public function request_list()
    {
        if (!empty($_GET['id'])) {
            if ($_GET['action'] == 'remove_curator') {
                if (fr_participant_model::remove_curator($_GET['id'])) {
                    $GLOBALS['fr_action_ok'] = 'Успешно сохранено.';
                }
            } else {
                if (fr_participant_model::set_curator(
                    $_GET['id'],
                    [
                        'curator_fio' => $_GET['curator_fio'],
                        'curator_phone' => $_GET['curator_phone'],
                        'curator_email' => $_GET['curator_email']
                    ]
                )) {
                    $GLOBALS['fr_action_ok'] = 'Успешно сохранено.';
                }
            }
        }
        $table = new fr_participant_table_builder();
        $table->prepare_items();

        include PLUGIN_TPLS_PATH . 'request_list.php';
    }
}