//Need to keep  here for global scope
const timesharePriceCalcData = timeshareMain.timesharePriceCalcData
    ? JSON.parse(timeshareMain.timesharePriceCalcData)
    : {};

$(document).ready(function () {
    disable(['more_six', 'four_six_before', 'two_four_before', 'less_two'], true);
    $('.season-rate').off('change').on('change', function () {
        const form = $(this).data('form');

        disable([form], !$(this).val());

        if (!timesharePriceCalcData[form]) timesharePriceCalcData[form] = {}
        if (!timesharePriceCalcData[form][$(this).val()]) timesharePriceCalcData[form][$(this).val()] = {}

        fromStorage(form, $(this).val());
    });


    $('.date-range').on('change', onDateChange);


    $('.add-date').on('click', function () {
        const dates_block = $(this).parent().parent('.range-blocks');

        dates_block.append(addDate());

        $(this).parent().parent().children().each(function (i) {
            $(this).attr('id', i);
            $(this).find('input.date-range').off('change').on('change', onDateChange);

            i && $(this).find('button.remove-date').off('click').on('click', onDateRemove);
        });
    });


    $('.discount-mode').on('change', function () {
        const form = $(this).data('form');

        disable([form], $(this).val() === 'weekly', 'weekly');

        const season = $(`.season-rate[data-form=${form}]`).val();

        if (!timesharePriceCalcData[form]) timesharePriceCalcData[form] = {}
        if (!timesharePriceCalcData[form][season]) timesharePriceCalcData[form][season] = {}

        if ($(this).val() === 'weekly') {
            delete timesharePriceCalcData[form][season].weeks;

            $(`input.weekly-percent[data-form=${form}]`).each(function () { // restore prev value
                $(this).change();
            });
        }

        if ($(this).val() === 'daily') {
            delete timesharePriceCalcData[form][season].weekly_percent;

            $(`input.weekday-input[data-form=${form}]`).each(function () { // restore prev value
                if ($(this).prop('checked')) {
                    $(this).click();
                    $(this).click();
                }
            });

            $(`input.daily-percent[data-form=${form}]`).each(function () { // restore prev value
                $(this).change();
            });
        }

        timesharePriceCalcData[form][season]['discount_mode'] = $(this).val() || 'weekly';
    });


    $('.weekly-percent').on('change', function () {
        const form = $(this).data('form');
        const season = $(`.season-rate[data-form=${form}]`).val();

        if (!timesharePriceCalcData[form]) timesharePriceCalcData[form] = {}
        if (!timesharePriceCalcData[form][season]) timesharePriceCalcData[form][season] = {}

        timesharePriceCalcData[form][season]['weekly_percent'] = $(this).val();
    });


    $('.discount-sets').on('keyup', function (e) {
        if (e.key === 'Enter') {
            const form = $(this).data('form');
            const value = e.target.value;
            const container = $(`.week[data-form=${form}]`);

            for (let i = 0; i < +value; i++) {
                $(container).append(addWeek(form));
                $(container).find('tr').off('mouseup').on('mouseup', removeWeek);
                $(container).find('input[type="number"]').off('change').on('change', onDailyPercentChange);
                $(container).find('input[type="checkbox"]').off('change').on('change', onChangeWeekday);
            }

            for (let i = 1; i < $(container).children().length; i++) {
                $($(container).children()[i]).attr('id', i);
            }
        }
    });

    $('.weekday-input').on('change', onChangeWeekday);

    $('.daily-percent').on('change', onDailyPercentChange);

    $('.save-button button').click(function () {
        $.ajax({
            type: "POST",
            url: timeshareMain.ajaxUrl,
            data: {
                action: 'price_calc_data',
                security: timeshareMain.security,
                timesharePriceCalcData: JSON.stringify(timesharePriceCalcData)
            },
            success: function (msg) {
                $('.timeshare-toast').css({ display: 'block' });
                const timer = setTimeout(() => {
                    $('.timeshare-toast').css({ display: 'none' });
                    clearTimeout(timer);
                }, 800);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.error(errorThrown);
            }
        });
    });
});
