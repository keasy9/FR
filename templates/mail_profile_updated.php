<?php
$keyStrings = [
    's_name' => 'Фамилия',
    'f_name' => 'Имя',
    'l_name' => 'Отчество',
    'birth_date' => 'Дата рождения',
    'sex' => 'Пол',
    'city' => 'Город',
    'org_name' => 'Наименование организации',
    'org_type' => 'Тип организации',
    'org_place' => 'Должность',
    'sci_degree' => 'Ученая степень',
    'sci_title' => 'Ученое звание',
    'part_form' => 'Форма участия',
    'part_type' => 'Тип участия',
    'email' => 'Email',
    'phone' => 'Номер телефона',
    'datetime_come' => 'Дата и время приезда',
    'place_come' => 'Место приезда',
    'flight_come' => 'Рейс приезда',
    'datetime_gone' => 'Дата и время отъезда',
    'place_gone' => 'Место отъезда',
    'flight_gone' => 'Рейс отъезда',
    'part_excursion' => 'Участие в экскурсии 17.09.22',
    'part_dinner' => 'Участие в ужине 16.09.22',
    'part_reception' => 'Участие в торжественном приеме 15.09.22',
    'part_qualify' => 'Участие в программе повышения квалификации',
    'required_invite' => 'Необходимо официальное приглашение',
];

$speakerStatusStrings = [
    'updated' => 'Обновлено',
    'deleted' => 'Удалено',
    'added' => 'Добавлено',
];
?>

<h2>Пользователь обновил информацию профиля:</h2>
<br>
<p>Участник: <?php echo $dataUpdated['s_name']['new'] ?? $request_default['s_name'], ' ', $dataUpdated['f_name']['new'] ?? $request_default['f_name'], ' ', $dataUpdated['l_name']['new'] ?? $request_default['l_name'] ?? ''; ?></p>
<p>Логин участника: <?php echo $user->user_email; ?></p>

<table>
    <tr><th>Поле</th><th>Старое значение</th><th>Новое значение</th></tr>
    <?php foreach ($dataUpdated as $key => $value) {
        if (in_array($key, ['speakers', 'user_photo'])) {
            continue;
        } 
        if ($key == 'sex') {
            foreach (['old', 'new'] as $val) {
                if ($value[$val] == 1) {
                    $value[$val] = 'Мужской';
                } else {
                    $value[$val] = 'Женский';
                }
            }
        } elseif ($key == 'part_form') {
            foreach (['old', 'new'] as $val) {
                if ($value[$val] == 1) {
                    $value[$val] = 'Очно';
                } else {
                    $value[$val] = 'Дистанционно';
                }
            }
        } elseif (in_array($key, ['part_excursion', 'part_dinner', 'part_reception', 'part_qualify', 'required_invite'])) {
            foreach (['old', 'new'] as $val) {
                if ($value[$val] == 1) {
                    $value[$val] = 'Да';
                } else {
                    $value[$val] = 'Нет';
                }
            }
        }
        ?>
    <tr><td><?php echo $keyStrings[$key] ?? $key ?></td><td><?php echo $value['old'] ?? '-' ?></td><td><?php echo $value['new'] ?? '-' ?></td></tr>
    <?php } ?>
</table>
<br>


<?php if(!empty($dataUpdated['speakers'])){ ?>
    <h2>Изменения в выступлениях спикера:</h2>
    <table>
        <tr><th>Статус</th><th>Старое значение</th><th>Новое значение</th></tr>
        <?php
        $repeats = [];
        foreach ($dataUpdated['speakers'] as $value) {
            if(in_array($value['old']['id'] ?? $value['new']['id'], $repeats)){
                continue;
            }
            $repeats[] = $value['old']['id'] ?? $value['new']['id'];
            $event = array_filter(
                    $events, 
                    function ($event) use ($value) {
                        return $event['id'] == $value['old']['id_event'] ?? $value['new']['id_event'];    
                    }
                );
            $title = '';
            $event = array_pop($event);
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
            ?>
        <tr>
            <td><?php echo $speakerStatusStrings[$value['status']] ?? $value['status'] ?></td>
            <td>
                <?php
                    echo $value['old']['show_type'] == 'Другое' ? $value['old']['show_type_other'] :  $value['old']['show_type'];
                    echo empty($value['old']['show_name']) ? '' : " под названием &quot;{$value['old']['show_name']}&quot;";
                    echo " для мероприятия {$title}";
                ?>
            </td>
            <td>
                <?php
                    echo $value['new']['show_type'] == 'Другое' ? $value['new']['show_type_other'] :  $value['new']['show_type'];
                    echo empty($value['new']['show_name']) ? '' : " под названием &quot;{$value['new']['show_name']}&quot;";
                    echo " для мероприятия {$title}";
                ?>
            </td>
        </tr>
        <?php } ?>
    </table>
<?php } ?>

<style>
    table {
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid grey;
    }
</style>