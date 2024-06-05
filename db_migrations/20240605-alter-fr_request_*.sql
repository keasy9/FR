ALTER TABLE fr_request_default
    MODIFY COLUMN org_type enum('Бизнес', 'Образование', 'Государcтвенный сектор', 'Другое') NULL,
    ADD COLUMN country enum(
        'Россия',
        'Белоруссия',
        'Армения',
        'Казахстан',
        'Киргизия',
        'Китай',
        'Монголия',
        'Таджикистан',
        'Туркменистан',
        'Узбекистан',
        'Африка',
        'Индия',
        'Бразилия',
        'Япония',
        'Корея',
        'ОАЭ',
        'Египет',
        'Другая'
    );

ALTER TABLE fr_request_live
    ADD COLUMN hotel varchar(255) NULL;

ALTER TABLE fr_request_speaker
    MODIFY COLUMN show_type enum(
        'Доклад',
        'Мастер-класс',
        'Визионерская лекция',
        'Фокус-сессия',
        'Экспертно-аналитическая сессия',
        'Форсайт-сессия',
        'Проектная сессия',
        'Участие в дискуссии',
        'Другое'
    ) NOT NULL;
