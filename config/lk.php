<?php

$conf = [];

//дата ограничения регистрации
$conf['dateRegisterLimit'] = '2024-09-06 00:00:00';

//группы полей, отключенные после даты
$conf['disabledFieldsets'] = [
    'request-live',
    'request-speaker',
    'speaker_shows',
];

//поля, отключенные после даты
$conf['disabledFields'] = [
    'part_live',
    'part_type_2',
    'part_type_3',
];