<?php

date_default_timezone_set('Asia/Barnaul');
$now = date('Y-m-d H:i:s');
$nearEvent = array_filter($events ?? [], function ($event) use ($now) {
    return !empty($event['selected']) && $event['date'] . ' '. $event['time_start'] >= $now;
});
if (!empty($nearEvent)) {
    $nearEvent = array_shift($nearEvent);
    $nearEvent['date_time'] = date('d.m.Y, h:i', strtotime($nearEvent['date'] . ' ' . $nearEvent['time_start']));
}
$fmt = datefmt_create(
    'ru_RU',
    IntlDateFormatter::FULL,
    IntlDateFormatter::FULL,
    'Asia/Barnaul',
    IntlDateFormatter::GREGORIAN,
    'd MMMM YYYY'
);

if (!empty($nearEvent) || !empty($request_live['curator_fio'])) { ?>
    <div class="lk-info">
        <?php if (!empty($request_live['curator_fio'])) { ?>
        <div class="lk-info-curator">
            <span class="dl">
                <span class="dt">Ваш куратор</span>
                <span class="dd">
                    <div class="lk-info-title"><?= $request_live['curator_fio'] ?></div>
                    <div class="lk-info-contacts"><span>Телефон</span> <?= $request_live['curator_phone'] ?></div>
                    <div class="lk-info-contacts"><span>E-mail</span> <?= $request_live['curator_email'] ?></div>
                </span>
            </span>
        </div>
        <?php } if (!empty($nearEvent)) { ?>
        <div class="lk-info-event">
            <span class="dl">
                <span class="dt">Ближайшее мероприятие</span>
                <span class="dd">
                    <div class="lk-info-title"><?= $nearEvent['title'] ?></div>
                    <div class="lk-info-date"><?= $nearEvent['date_time'] ?></div>
                    <div class="lk-info-place"><?= implode(', ', array_filter([$nearEvent['place_city'], $nearEvent['place_addr'], $nearEvent['place_room']])) ?></div>
                </span>
            </span>
        <?php } ?>
    </div>
<?php }
