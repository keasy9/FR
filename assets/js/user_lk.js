jQuery(function () {

    let input_live_dynamic, input_speaker_dynamic, input_qualify_dynamic;
    let block_default_dynamic, block_live_dynamic, block_speaker_dynamic, block_qualify_dynamic, block_final_dynamic;

    let input_confirm;

    let $r_tab, $m_tab;


    function dynamic_form_blocks(block_name) {
        if (block_name == 'part_form') {
            if (input_live_dynamic.prop('checked') == true) {
                block_live_dynamic.removeClass('hidden');
            } else {
                block_live_dynamic.addClass('hidden');
                block_qualify_dynamic.addClass('hidden');
            }
        } else if (block_name == 'part_type') {
            if (input_speaker_dynamic.prop('checked') == true) {
                block_speaker_dynamic.removeClass('hidden');
            } else {
                block_speaker_dynamic.addClass('hidden');
            }
        } else if (block_name == 'part_qualify') {
            if (input_qualify_dynamic.prop('checked') == true) {
                block_qualify_dynamic.removeClass('hidden');
            } else {
                block_qualify_dynamic.addClass('hidden');
            }
        }
    }

    function remove_inputs(button) {
        let tr1 = button.parents('tr');
        console.log(tr1);
        let tr2 = tr1.prev();
        console.log(tr2);
        let tr3 = tr2.prev();
        console.log(tr3);
        tr1.remove();
        tr2.remove();
        tr3.remove();
    }


    function events_time_check() {
        $('.wrong-by-time_notice').remove();
        $('.event-map.event-block')
            .removeClass('wrong-by-time')
            .filter('[data-event-selected="1"]')
            .each(function () {
                this.classList.toggle('wrong-by-time', $(this).siblings('[data-event-selected="1"]').length > 0);
            });
        $('.wrong-by-time').closest('tr').find('td.event-map-time')
            .append('<div class="wrong-by-time_notice">Выбраны пересекающиеся по времени мероприятия</div>');
    }

    $('#request-form fieldset input[type="checkbox"][data-file-input]').on('click', function () {
        let $this = $(this);
        if ($this.prev('a').text().length != 0) {
            $this.parent().next().val(this.checked ? 'delete' : '');
            $this.parent().toggleClass('file-deleted', this.checked);
        }
    }).prop('checked', false);

    function add_change_trigger_on_speaker_select() {
        $('.speaker_show_type_select').on('change', function () {
            if ($(this).find(':selected').val() == 'Другое') {
                $(this).parent().parent().next().css('display', 'block');
            } else {
                $(this).parent().parent().next().css('display', 'none');
            }
        });
    }

    add_change_trigger_on_speaker_select();

    let $speakerTpl = $('template#speaker-inputs');
    let $speakerFieldset = $('#speaker-fieldset');
    let $speakerBtnAdd = $speakerFieldset.find('.btn-speaker-add');

    $speakerFieldset.on('click', '.btn-speaker-delete', function () {
        $(this).closest('div').remove();
    });

    $speakerBtnAdd.on('click', function () {
        $(this).before($speakerTpl.clone()[0].content);
        add_change_trigger_on_speaker_select();
    });

    if ($speakerFieldset.children('div').length === 0) {
        $speakerBtnAdd.click();
    }

    let $tabs = $('.tab-bar > .tab-picker');
    let $selectedEventsTab = $tabs.filter('[data-tab-name="selected"]')
    let $events = jQuery('input[type="checkbox"][name="events[]"]');

    $events.on('click', function () {
        jQuery(this).siblings('.set_event_message, .loader').remove();
        let $message = $('<span class="set_event_message"></span>');
        let $loader = $('<span class="loader"></span>');
        jQuery(this).parent().append($loader);
        let $parent = $(this).parents('div.event-block');
        let $el = $(this);
        jQuery.post(
            '/lk/',
            {
                value: $el.prop('checked'),
                id: $el.val(),
                action: 'set_event'
            },
            function (data) {
                $loader.remove();
                if (data != 'success') {
                    $message.addClass('set_event_error');
                    $message.text('Ошибка');
                    $el.prop('checked', !$el.prop('checked'));
                    $el.parent('label').append();
                } else {
                    $message.addClass('set_event_success');
                    $message.text('Сохранено');
                    if ($el.prop('checked')) {
                        $parent.attr('data-event-selected', '1');
                    } else {
                        $parent.removeAttr('data-event-selected');
                    }
                }
                $el.parent('label').append($message);
                setTimeout(function () {
                    $message.remove();
                }, 5000);
                events_time_check();
                if ($selectedEventsTab.length > 0) {
                    $selectedEventsTab.toggleClass('hidden', $events.filter(':checked').length == 0);
                    $selectedEventsTab.addClass('tab-reload');
                }
            }
        );
    });
    events_time_check();
    input_live_dynamic = jQuery('input[name="part_form"][value="1"]');
    input_speaker_dynamic = jQuery('input[name="part_type"][value="Спикер"]');
    input_qualify_dynamic = jQuery('input[name="part_qualify"][value="1"]');

    block_live_dynamic = jQuery('.request-live.request-block');
    block_speaker_dynamic = jQuery('.request-speaker.request-block');
    block_qualify_dynamic = jQuery('.request-qualify.request-block');


    dynamic_form_blocks('part_type');
    dynamic_form_blocks('part_form');
    dynamic_form_blocks('part_qualify');

    jQuery('input[name="part_form"]').on('click', function () {
        dynamic_form_blocks('part_form')
    });
    jQuery('input[name="part_type"]').on('click', function () {
        dynamic_form_blocks('part_type')
    });
    jQuery('input[name="part_qualify"]').on('click', function () {
        dynamic_form_blocks('part_qualify')
    });

    let $tabsContainers = $('.tab');
    let $selectedTab = $tabs.filter('[data-hash="' + location.hash.substr(1) + '"]');
    if ($selectedTab.length == 0) $selectedTab = $tabs.first();

    $tabs.on('click', function () {
        $tabs.removeClass('picked');
        $(this).addClass('picked');
        $tabsContainers.removeClass('picked');
        $tabsContainers.filter('[data-tab="' + this.dataset.tabName + '"]').addClass('picked');
        location.hash = this.dataset.hash;
        if (this.classList.contains('tab-reload')) {
            location.reload();
        }
    });

    $selectedTab.click();

    jQuery('#request-save').on('click', function () {
        if (jQuery('#confirm').prop('checked')) {
            jQuery('label[for="confirm"]').removeClass('warning');
            jQuery('.request-block.hidden').remove();
            return true;
        } else {
            jQuery('label[for="confirm"]').addClass('warning');
        }
        return false;
    });

    jQuery('#confirm').on('change', function () {
        if (!jQuery(this).prop('checked')) {
            jQuery('#request-save').addClass('disabled');
        } else {
            jQuery('#request-save').removeClass('disabled');
        }
    });

    jQuery('#saved_message_hide').on('click', function () {
        jQuery('.success-saved').remove();
    })

});
