//Need to keep  here for global scope
const timesharePriceCalcData = timeshareMain.timesharePriceCalcData
    ? JSON.parse(timeshareMain.timesharePriceCalcData)
    : {};

$(document).ready(function () {
    disable(['more_six', 'four_six_before', 'two_four_before', 'less_two'], true);

    $('.season-rate').on('change', function () {
        const season = $(this).val();
        const form = $(this).data('form');

        if (form === 'less_two' && $(this).val() === 'all') {
            $('.yearly-percent').css('display', 'inline-block');
            disable([form], true, 'yearly');
        } else if (form === 'less_two' && $(this).val() !== 'all') {
            $('.yearly-percent').css('display', 'none');
            disable([form], true);
        } else if (form !== 'less_two' && $(this).val() !== 'all') {
            disable([form], true);
        }

        if (!timesharePriceCalcData[form]) timesharePriceCalcData[form] = {}
        if (!timesharePriceCalcData[form][season]) timesharePriceCalcData[form][season] = {}
        if (!timesharePriceCalcData[form][season]['discount_mode']) timesharePriceCalcData[form][season]['discount_mode'] = {};

        fromStorage(form, season);
    });


    $('.date-range').on('change', onDateChange);


    $('.add-date').on('click', function () {
        const dates_block = $(this).parent().parent('.range-blocks');
        const form = dates_block.data('form');

        dates_block.append(addDate(form));

        $(this).parent().parent().children().each(function (i) {
            $(this).attr('id', i);
            $(this).find('input.date-range').off('change').on('change', onDateChange);

            i && $(this).find('button.remove-date').off('click').on('click', onDateRemove);
        });
    });


    $('.discount-mode').on('change', function () {
        const form = $(this).data('form');
        const season = $(`.season-rate[data-form=${form}]`).val();

        if ($(this).val() === '') {
            disable([form], true);
        }

        if ($(this).val() === 'weekly') {
            const weekly_percent = $(`.weekly-percent[data-form=${form}]`);

            weekly_percent.attr('placeholder', 'Weekly percent');
            disable([form], $(this).val() === 'weekly', 'weekly');
            weekly_percent.change();
        }

        if ($(this).val() === 'daily') {
            disable([form], $(this).val() === 'daily', 'daily');
        }

        if ($(this).val() === 'always') {
            const weekly_percent = $(`.weekly-percent[data-form=${form}]`);

            weekly_percent.attr('placeholder', 'Always percent');
            disable([form], $(this).val() === 'always', 'always');
            weekly_percent.change();
        }

        timesharePriceCalcData[form][season]['discount_mode']['mode'] = $(this).val();
    });


    $('.yearly-percent').on('change', function () {
        const form = $(this).data('form');
        const season = $(`.season-rate[data-form=${form}]`).val();

        timesharePriceCalcData[form][season]['discount_mode']['yearly_percent'] = $(this).val();
    });


    $('.weekly-percent').on('change', function () {
        const form = $(this).data('form');
        const season = $(`.season-rate[data-form=${form}]`).val();

        const percent_key = $(`.discount-mode[data-form=${form}]`).val() === 'always'
            ? 'always_percent'
            : 'weekly_percent';

        timesharePriceCalcData[form][season]['discount_mode'][percent_key] = $(this).val();
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
        const clean = (obj) => {
            if (typeof obj !== 'object' || obj === null) return obj;

            if (Array.isArray(obj)) {
                const cleanedArray = obj.map(clean).filter(item => item !== undefined);
                return cleanedArray.length > 0 ? cleanedArray : undefined;
            }

            const cleanedObj = {};
            for (const key in obj) {
                const cleanedValue = clean(obj[key]);
                if (cleanedValue) cleanedObj[key] = cleanedValue;
            }

            return Object.keys(cleanedObj).length === 0 ? undefined : cleanedObj;
        }

        const result = clean(timesharePriceCalcData);

        $.ajax({
            type: "POST",
            url: timeshareMain.ajaxUrl,
            data: {
                action: 'price_calc_data',
                security: timeshareMain.security,
                timesharePriceCalcData: JSON.stringify(result)
            },
            success: function () {
                $('.timeshare-toast').css({display: 'block'});
                const timer = setTimeout(() => {
                    $('.timeshare-toast').css({display: 'none'});
                    clearTimeout(timer);
                }, 800);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.error(errorThrown);
            }
        });
    });
});
