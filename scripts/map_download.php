<?php

//composer autoloader
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

$html = <<<HTML
    <div style="background-color: #f2f2fa; ">
        <table>
            <tr>
                <td style="width: 55px;">
                </td>
                <td>
                    <img src="/wp-content/uploads/2022/01/logo.png" width="75" height="74" style="display:inline;vertical-align: middle">
                </td>
                <td>
                    <div style="display: inline; vertical-align: middle;">V Международный образовательный форум «Алтай — Азия 2022»</div>
                    <div style="display: inline; vertical-align: middle;font-size: 0.8em"><br>Маршрутная карта</div>
                </td>
            </tr>
        </table>
    </div>
HTML;
$events = [];
foreach ($selected_events as $event) {
    $time = mb_substr($event['time_start'], 0, 5) . ' - ' . mb_substr($event['time_end'], 0, 5);
    $events[$event['date']][$time][] = $event;
}
foreach ($events as $date => &$times) {
    //получение города для дня
    $city_of_day = '';
    foreach ($times as $events_in_time) {
        foreach ($events_in_time as $event) {
            if (!empty($event['place_city'])) {
                $city_of_day = $event['place_city'];
                break;
            }
        }
    }
    $date_str = $fmt->format(strtotime($date));
    $html .= <<< HTML
        <div style="display: flex;align-items: center;gap: 12px;border-radius: 4px;padding: 6px 0;border-bottom: solid 3px #e8e8f0;position: sticky;left: 0;">
            <span style="font-weight: bold;font-size: 1.15rem;">{$date_str}&nbsp;&nbsp;</span>
            <span style="color: #444;">{$city_of_day}</span>
        </div>
    HTML;


    foreach ($times as $time => &$events_in_time) {
        //пересечение событий по времени
        $time_crossing = count($events_in_time) > 1;
        $time_crossing_notice = '';
        if ($time_crossing) {
            $time_crossing_notice = <<<HTML
                <div style="font-size: 15px;width: 90%;color: red;margin-top: 10px;">
                    Выбраны пересекающиеся по времени мероприятия
                </div>
            HTML;
        }
        $html .= <<< HTML
        <br>
        <table>
            <tr style="width: 23%;">
                <td style="width: 110px; font-size: 1em; background-color: #f4f4fb; border-radius: 5px; border: 1px solid #e8e8ee;">&nbsp;&nbsp;{$time}</td>
                <td>{$time_crossing_notice}</td>
            </tr>
        </table>
        <div>
        HTML;


        foreach ($events_in_time as &$event) {
            //адрес события
            $address = $event['place_addr'];
            if (!empty($event['place_room'])) {
                $address .= ', ' . $event['place_room'];
            }
            //background-color для блока одного события
            $background_color = $time_crossing ? 'background-color: #ffe5e5;' : 'background-color: #f0fff0;';
            //color для заголовка одного события
            $title_color = $time_crossing ? 'color: #dc143c;' : '';
            $track_title = '';
            if (!empty($event['track'])) {
                //background-color для заголовка экспертной сессии
                $track_title_color = 'background-color:';
                switch ($event['track']) {
                    case 1:
                        $track_title_color .= '#ffa07a;';
                        break;
                    case 2:
                        $track_title_color .= '#87ceeb;';
                        break;
                    case 3:
                        $track_title_color .= '#9acd32;';
                        break;
                    case 4:
                        $track_title_color .= '#ffd700;';
                        break;
                    case 5:
                        $track_title_color .= '#ffc0cb;';
                        break;
                }
                //заголовок экспертной сессии
                $track_title = <<<HTML
                    <div>
                        <div style="background-color: #0001;{$track_title_color}font-size: 0.7em;color: black;padding: 5px 10px; border-radius: 4px; width: 115px;">
                            &nbsp;&nbsp;Экспертная сессия {$event['track']}&nbsp;&nbsp;
                        </div>
                    </div>
                HTML;
            }
            $html .= <<< HTML
                <div style="{$background_color}padding: 12px;border-radius: 4px;margin:12px;">
                    <label>
                        {$track_title}
                        <div style="color: #555; font-size: 0.8rem;margin-top: 12px;">{$address}</div>
                        <div style="{$title_color}font-weight: bold; font-size: 1rem;margin-top: 12px;">{$event['title']}</div>
                        <div style="color: #555; font-size: 0.8rem;margin-top: 12px;">{$event['description']}</div>
                        <br>
                    </label>
                </div>
            HTML;
        }
        $html .= '</div>';
    }
    $times = implode('', $times);
}
//конвертация html в pdf и выгрузка документа
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output('Маршрутная карта.pdf', 'I');