function fromStorage(form, season) {
    const date_range = timesharePriceCalcData[form][season]['date_range'];
    const discount_mode = timesharePriceCalcData[form][season]['discount_mode'];

    reset(form, season, date_range, discount_mode);

    viewValues(form, season, {date_range, discount_mode});
}


function reset(form, season, date_range, discount_mode) {
    const range_block = $(`.range-blocks[data-form=${form}]`);
    if (date_range?.length) {
        range_block.children().not(':first-child').remove();
        for (let i = 1; i < date_range.length; i++) {
            $(range_block).append(addDate(form));
        }

        $(range_block).children().each(function (i) {
            $(this).attr('id', i);
            $(this).find('input.date-range').off('change').on('change', onDateChange);

            i && $(this).find('button.remove-date').off('click').on('click', onDateRemove);
        });
    } else {
        range_block.children().not(':first-child').remove();
        $(`.date-range[data-form=${form}]`).val('');
    }

    if (!discount_mode.mode) discount_mode.mode = $(`.discount-mode[data-form=${form}]`).val();

    const weeks_block = $(`tbody[data-form=${form}]`);
    if (discount_mode.weeks?.length) {
        weeks_block.empty();
        for (let i = 0; i < discount_mode.weeks.length; i++) {
            const week = $(addWeek(form)).on('mouseup', removeWeek);
            $(weeks_block).append(week);
        }

        $(weeks_block).children().each(function (i) {
            $(this).attr('id', i);

            $(this).find('input.weekday-input')
                .prop('disabled', !(discount_mode.mode === 'daily'))
                .off('click').on('click', onChangeWeekday);

            $(this).find('input.daily-percent')
                .prop('disabled', !(discount_mode.mode === 'daily'))
                .off('change').on('change', onDailyPercentChange);
        });
    }
}


function viewValues(form, season, value) {
    // Set date
    if (value.date_range?.length) {
        $(`.range-blocks[data-form=${form}]`).children().each(function (i) {
            for (const date in value.date_range[i]) {
                $(this).find(`input[type="date"][data-range="${date}"]`).val(value.date_range[i][date]);
            }
        });
    }

    // Set discount mode
    if (value.discount_mode?.mode) {
        $(`.discount-mode[data-form=${form}]`)
            .val(value.discount_mode.mode);
    }

    // Set weekly percent
    if (value.discount_mode?.weekly_percent) {
        $(`.weekly-percent[data-form=${form}]`)
            .prop('disabled', false)
            .val(value.discount_mode.weekly_percent);
    }

    // Set weekly percent
    if (value.discount_mode?.always_percent) {
        $(`.weekly-percent[data-form=${form}]`)
            .prop('disabled', false)
            .val(value.discount_mode.always_percent);
    }

    // Set yearly percent
    if (value.discount_mode?.yearly_percent) {
        $(`.yearly-percent[data-form=${form}]`)
            .val(value.discount_mode.yearly_percent);
    }

    // Set weekdays
    if (value.discount_mode?.weeks?.length) {
        $(`tr[data-form=${form}]`).each(function (i) {
            for (const week in value.discount_mode.weeks[i]) {
                $(this).find('input.weekday-input');

                const weekday = $(this).find(`input.weekday-input[data-week="${week}"]`);
                $(weekday).prop('checked', $(weekday).data('week') in value.discount_mode.weeks[i]);

                $(this).find('input.daily-percent').prop('disabled', false).val(value.discount_mode.weeks[i].daily_percent);
            }
        });
    }
}
