function onDateChange() {
  const form = $(this).data('form');
  const range = $(this).data('range');
  const season = $(`.season-rate[data-form=${ form }]`).val();

  const parent_id = $(this).parent().parent().attr('id') || 0;

  if (!data[form]) data[form] = {}
  if (!data[form][season]) data[form][season] = {}
  if (!data[form][season]['date_range']) data[form][season]['date_range'] = []
  if (!data[form][season]['date_range'][parent_id]) data[form][season]['date_range'][parent_id] = { from: '', to: '' }

  data[form][season]['date_range'][parent_id][range] = $(this).val();
  console.log(data)
}

function addDate() {
  return `
    <div class="range-block">
      <div class="season-date-range">
        <input type="date" class="date-range" data-form="more_six" data-range="from">
        <input type="date" class="date-range" data-form="more_six" data-range="to">
      </div>
      <button class="remove-date" data-form="more_six">-</button>
    </div>
  `;
}

function onDateRemove() {
  const form = $(this).parent().parent().data('form');
  const season = $(`.season-rate[data-form=${ form }]`).val();
  const container = $(`.range-blocks[data-form=${ form }]`);

  $(this).parent().remove();
  data[form][season]['date_range'].splice(+$(this).parent().attr('id'), 1);

  $(container).children().each(function (i) {
    $(this).attr('id', i);
  });
}
