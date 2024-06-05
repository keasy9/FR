<div class="tab sel-tab" data-tab="selected">
    <div id="map-download"><a href="/lk?download=map" target="_blank">Скачать маршрутную карту в PDF</a></div>
    <div class="event-sel sel">
        <?php
        $events_list = [];
        foreach ($selected_events as $event) {
            $date = $fmt->format(strtotime($event['date']));
            $event['time_start'] = mb_substr($event['time_start'], 0, 5);
            $event['time_end'] = mb_substr($event['time_end'], 0, 5);
            if (!empty($event['track'])) {
                $event['event_track'] = 'Экспертная сессия ' . $event['track'];
            }
            /* для вывода номера шорт-трека, больше не нужно
            if (!empty($event['short_track'])) {
                $event['event_short_track'] = 'Шорт-трек ' . $event['short_track'];
            }*/
            $events_list[$date][$event['time_start']][] = $event;
        }
        ?>
        <ul>
            <?php
            foreach ($events_list as $day => $times) {
                //получение города для дня
                $cityOfDay = '';
                foreach ($times as $time) {
                    foreach ($time as $event) {
                        if (!empty($event['place_city'])) {
                            $cityOfDay = $event['place_city'];
                            break 2;
                        }
                    }
                }

                ?>
                <li class="event-map event-day">
                    <div class="event-day-header">
                        <span class="event-date"><?= $day ?></span>
                        <span class="event-city"><?php echo $cityOfDay ?></span>
                    </div>
                    <ul>
                        <?php foreach ($times as $time => $events_) { ?>
                            <li class="event-map event-time">
                                <table class="event-map-table">
                                    <tr>
                                        <td class="event-map-time">
                                            <div class="event-time-time">
                                                <?php echo $events_[0]['time_start'], ' &ndash; ', $events_[0]['time_end'] ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="events-container">
                                                <?php if (count($events_) >= 1 && $events_[0]['type_id'] <= 3) {
                                                    foreach ($events_ as $event) { ?>
                                                        <div class="event-map event-block" data-event-track="<?= $event['track'] ?>" data-event-selected="1">
                                                            <label class="event-label" for="cb<?php echo $event['id'] ?>" data-start="<?php echo $event['date'], 'T', $event['time_start'] ?>" data-end="<?php echo $event['date'], 'T', $event['time_end'] ?>">
                                                                <?php if (!empty($event['event_track']) || !empty($event['event_short_track'])) { ?>
                                                                    <div>
                                                                        <?php if (!empty($event['event_track'])) { ?>
                                                                        <span class="event-track"><?= $event['event_track'] ?? '' ?></span>
                                                                        <?php } ?>
                                                                    </div>
                                                                <?php } ?>
                                                                <div class="event-place"><?php echo $event['place_addr'], !empty($event['place_room']) ? ', ' . $event['place_room'] : '' ?></div>
                                                                <div class="event-title">
                                                                    <?php if ($event['type'] == 'Шорт-трек' && empty($event['title'])) {
                                                                        echo "Программное мероприятие Экспертной сессии {$event['track']}";
                                                                    } else {
                                                                        echo $event['title'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <?php if (!empty($event['description'])) { ?>
                                                                    <div class="event-desc">
                                                                        <?php echo $event['description']; ?></div>
                                                                <?php } ?>
                                                            </label>
                                                        </div>
                                                    <?php }
                                                } else { ?>
                                                    <div class="inline-event-container">
                                                        <?php
                                                        echo empty($events_[0]['title']) ? $events_[0]['type_custom'] : $events_[0]['title'];
                                                        ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
                <?php
            } ?>
        </ul>
    </div>
</div>
