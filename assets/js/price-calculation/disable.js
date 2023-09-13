function disable(form, disabled, by) {
    for (const f of form) {
        const date_range = $(`.date-range[data-form=${f}]`);
        const add_date = $(`.add-date[data-form=${f}]`);
        const discount_mode = $(`.discount-mode[data-form=${f}]`);
        const yearly_percent = $(`.yearly-percent[data-form=${f}]`);
        const weekly_percent = $(`.weekly-percent[data-form=${f}]`);
        const discount_sets = $(`.discount-sets[data-form=${f}]`);
        const daily_percent = $(`.daily-percent[data-form=${f}]`);
        const weekday_input = $(`.weekday-input[data-form=${f}]`);

        if (by === 'weekly') {
            toggle(yearly_percent, f, disabled);
            toggle(weekly_percent, f, !disabled);
            toggle(discount_sets, f, disabled);
            toggle(daily_percent, f, disabled);
            toggle(weekday_input, f, disabled);
            return;
        }

        if (by === 'yearly') {
            toggle(date_range, f, !disabled);
            toggle(discount_mode, f, disabled);
            toggle(yearly_percent, f, !disabled);
            toggle(weekly_percent, f, disabled);
            toggle(discount_sets, f, disabled);
            toggle(daily_percent, f, disabled);
            toggle(weekday_input, f, disabled);
            return;
        }

        if (by === 'daily') {
            toggle(date_range, f, !disabled);
            toggle(discount_mode, f, !disabled);
            toggle(weekly_percent, f, disabled);
            toggle(discount_sets, f, !disabled);
            toggle(daily_percent, f, !disabled);
            toggle(weekday_input, f, !disabled);
            return;
        }

        if (by === 'always') {
            toggle(yearly_percent, f, disabled);
            toggle(weekly_percent, f, !disabled);
            toggle(discount_sets, f, disabled);
            toggle(daily_percent, f, disabled);
            toggle(weekday_input, f, disabled);
            return;
        }

        toggle(date_range, f, !disabled);
        toggle(add_date, f, !disabled);
        toggle(discount_mode, f, !disabled);
        toggle(yearly_percent, f, disabled);
        toggle(weekly_percent, f, disabled);
        toggle(discount_sets, f, disabled);
        toggle(daily_percent, f, disabled);
        toggle(weekday_input, f, disabled);
    }
}

function toggle(elements, form, disabled) {
    elements.each(function () {
        if ($(`.season-rate[data-form=${form}]`).val()) {
            $(this).prop('disabled', disabled);
        } else {
            $(this).prop('disabled', true);
        }
    });
}
