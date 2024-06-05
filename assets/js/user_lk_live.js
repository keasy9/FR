/*
jQuery(function () {
    jQuery('input[type="checkbox"][name="events[]"]').on('click', $events_time_check());

    function $events_time_check() {
        $event_blocks.removeClass('wrong-by-time');
        let $previousInterval = {};
        $event_blocks.each(function () {
            if ($(this).find('input[type="checkbox"][name="events[]"]').prop('checked')) {
                let label = jQuery(this).find('label.event-label');
                if (Object.keys($previousInterval).length != 0) {
                    let start = new Date(Date.parse(label.attr('data-start')));
                    if (start >= $previousInterval.start && start <= $previousInterval.end) {
                        $(this).addClass('wrong-by-time');
                        $previousInterval.el.addClass('wrong-by-time');
                    }
                }
                $previousInterval = {
                    start: new Date(Date.parse(label.attr('data-start'))),
                    end: new Date(Date.parse(label.attr('data-end'))),
                    el: $(this)
                };
            }
        });
    }
});*/
