function onDateChange() {
    const form = $(this).data('form');
    const range = $(this).data('range');
    const season = $(`.season-rate[data-form=${form}]`).val();
    const parent_id = $(this).parent().parent().attr('id') || 0;

    if (!timesharePriceCalcData[form]) timesharePriceCalcData[form] = {}
    if (!timesharePriceCalcData[form][season]) timesharePriceCalcData[form][season] = {}
    if (!timesharePriceCalcData[form][season]['date_range']) timesharePriceCalcData[form][season]['date_range'] = []
    if (!timesharePriceCalcData[form][season]['date_range'][parent_id]) timesharePriceCalcData[form][season]['date_range'][parent_id] = {
        from: '',
        to: ''
    }

    timesharePriceCalcData[form][season]['date_range'][parent_id][range] = $(this).val();
}

function addDate(form) {
    return `
    <div class="range-block">
      <div class="season-date-range">
        <input type="date" class="date-range" data-form=${ form } data-range="from">
        <input type="date" class="date-range" data-form=${ form } data-range="to">
      </div>
      <button class="remove-date" data-form="more_six">-</button>
    </div>
  `;
}

function onDateRemove() {
    const form = $(this).parent().parent().data('form');
    const season = $(`.season-rate[data-form=${form}]`).val();
    const container = $(`.range-blocks[data-form=${form}]`);

    $(this).parent().remove();
    timesharePriceCalcData[form][season]['date_range'].splice(+$(this).parent().attr('id'), 1);

    $(container).children().each(function (i) {
        $(this).attr('id', i);
    });
}
