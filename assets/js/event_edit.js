$(function () {
    let $fr_form_event = $('#fr_form_event')[0];
    let $fr_other_type = $('#fr_other_type');
    let $fr_track_num = $('#fr_track_num');
    let $fr_speaker_allow = $('#fr_speaker_allow');
    $('#fr_reset_btn').on('click', function () {
        $fr_form_event.reset();
    });
    $('#fr_save_btn').on('click', function () {
        $fr_form_event.submit();
    });
    $('#fr_cancel_btn').on('click', function () {
        document.location.href = '/wp-admin/admin.php?page=event_list';
    });

    $('#fr_type_select').on('change', function () {
        let $value = $(this).find(':selected').val();
        if ($value == 'Другое') {
            $fr_other_type.css('display', 'block');
            $fr_track_num.css('display', 'none');
            $fr_speaker_allow.css('display', 'none');

        } else if ($value == 'Программное мероприятие') {
            $fr_other_type.css('display', 'none');
            $fr_track_num.css('display', 'block');
            $fr_speaker_allow.css('display', 'block');
        }

    });

});