function fromStorage(form, season) {
    const storage = timeshareMain.timesharePriceCalcData ? JSON.parse(timeshareMain.timesharePriceCalcData) : {};


    if (!storage[form]) storage[form] = {}
    if (!storage[form][season]) storage[form][season] = {}

    const date_range = storage[form][season]['date_range']
    const discount_mode = storage[form][season]['discount_mode']
    const weekly_percent = storage[form][season]['weekly_percent']
    const weeks = storage[form][season]['weeks']

    reset(form, date_range, discount_mode, weekly_percent, weeks);

    if (date_range || discount_mode || weekly_percent || weeks) {
        viewValues(form, season, {date_range, discount_mode, weekly_percent, weeks});
    }
}


function reset(form, date_range, discount_mode, weekly_percent, weeks) {
    const range_block = $(`.range-blocks[data-form=${form}]`);
    if (date_range?.length) {
        for (let i = 1; i < date_range.length; i++) {
            $(range_block).append(addDate());
        }

        $(range_block).children().each(function (i) {
            $(this).attr('id', i);
            $(this).find('input.date-range').off('change').on('change', onDateChange);

            i && $(this).find('button.remove-date').off('click').on('click', onDateRemove);
        });
    } else {
        $(`.date-range[data-form=${form}]`).each(function () {
            $(this).val('');
        });
    }

    const weeks_block = $(`tbody[data-form=${form}]`);
    if (weeks?.length) {
        for (let i = 1; i < weeks.length; i++) {
            $(weeks_block).append(addWeek(form));
        }

        $(weeks_block).children().each(function (i) {
            $(this).attr('id', i);

            $(this).find('input.weekday-input')
                .prop('disabled', false)
                .off('click').on('click', onChangeWeekday);

            $(this).find('input.daily-percent')
                .prop('disabled', false)
                .off('change').on('change', onDailyPercentChange);
        });
    }

    $(`.discount-mode[data-form=${form}]`).val(discount_mode || 'weekly');

    // $(`.weekly-percent[data-form=${ form }]`).prop('disabled', !(discount_mode === 'weekly'));

    $(`.discount-sets[data-form=${form}]`).prop('disabled', !(discount_mode === 'daily'));
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
    if (value.discount_mode) {
        $(`.discount-mode[data-form=${form}]`)
            .val(value.discount_mode)
            .prop('disabled', false);
    }

    // Set weekly percent
    if (value.weekly_percent) {
        $(`.weekly-percent[data-form=${form}]`)
            .val(value.weekly_percent)
            .prop('disabled', false);
    }

    // Set weekdays
    if (value.weeks?.length) {
        $(`tr[data-form=${form}]`).each(function (i) {
            for (const week in value.weeks[i]) {
                $(this).find('input.weekday-input').prop('disabled', false);

                const weekday = $(this).find(`input.weekday-input[data-week="${week}"]`);
                $(weekday).prop('checked', $(weekday).data('week') in value.weeks[i]);

                $(this).find('input.daily-percent').prop('disabled', false).val(value.weeks[i].daily_percent);
            }
        });
    }
}
