$(document).ready(function () {
    $('#bulk-action-selector-top, #bulk-action-selector-bottom').change(function () {
        if ($(this).children(':selected').val() == 'set_curator') {
            $('#overlay').fadeIn(297, function () {
                $('#curator_edit')
                    .css('display', 'block')
                    .animate({opacity: 1}, 198);
            });
        }
    });

    $('#curator_edit_cancel, #overlay').click(function () {
        $('#curator_edit').animate({opacity: 0}, 198, function () {
            $(this).css('display', 'none');
            $('#overlay').fadeOut(297);
        });
        $('#bulk-action-selector-top option:first-child, #bulk-action-selector-bottom option:first-child').prop('selected', true);
    });
    $('#part_form_filter').on('change', function () {
        window.location.href = document.location.href + '&filter=' + $(this).children(':selected').val();
    });

});