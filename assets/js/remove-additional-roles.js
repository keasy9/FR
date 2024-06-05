$(function () {
    $h2 = $('h2:contains("Персональные настройки"), h2:contains("О пользователе")');
    $h2.next('.form-table').remove();
    $h2.remove();
    $('h2:contains("Пароли приложений"),' +
        '#application-passwords-section,' +
        'select#role option[value="fr_moderator"],' +
        'select#role option[value="banned"],' +
        'select#role option[value="css_js_designer"],' +
        'select#role option[value="subscriber"],' +
        'select#role option[value="author"],' +
        'select#role option[value="editor"],' +
        'select#role option[value="administrator"],' +
        'select#role option[value=""],' +
        'tr.user-url-wrap').remove();
});