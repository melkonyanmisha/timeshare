function onChangeWeekday() {
  const form = $(this).data('form');
  const week = $(this).data('week');
  const season = $(`.season-rate[data-form=${ form }]`).val();

  if (!data[form]) data[form] = {}
  if (!data[form][season]) data[form][season] = {}

  const parent_id = $(this).parent().parent().parent().attr('id') || 0;

  if (!data[form][season]['weeks']) data[form][season]['weeks'] = [];
  if (!data[form][season]['weeks'][parent_id]) data[form][season]['weeks'][parent_id] = {}
  data[form][season]['weeks'][parent_id][week] = $(this).prop('checked');
}

const addWeek = (form) => {
  return `
    <tr data-form="${ form }">
      <td class="weekday"><div><input type="checkbox" class="weekday-input" data-form="${ form }" data-week="monday"></div></td>
      <td class="weekday"><div><input type="checkbox" class="weekday-input" data-form="${ form }" data-week="tuesday"></div></td>
      <td class="weekday"><div><input type="checkbox" class="weekday-input" data-form="${ form }" data-week="wednesday"></div></td>
      <td class="weekday"><div><input type="checkbox" class="weekday-input" data-form="${ form }" data-week="thursday"></div></td>
      <td class="weekday"><div><input type="checkbox" class="weekday-input" data-form="${ form }" data-week="friday"></div></td>
      <td class="weekday"><div><input type="checkbox" class="weekday-input" data-form="${ form }" data-week="saturday"></div></td>
      <td class="weekday"><div><input type="checkbox" class="weekday-input" data-form="${ form }" data-week="sunday"></div></td>
      <td class="weekday"><div><input type="number" class="daily-percent" min="0" value="0" data-form="${ form }"></div></td>
    </tr>
  `;
}

function removeWeek(e) {
  if (e.button === 1 && $(this).attr('id')) {
    const form = $(this).data('form');
    const season = $(`.season-rate[data-form=${ form }]`).val();

    $(this).remove();
    data[form][season]['weeks'].splice(+$(this).attr('id'), 1);

    const container = $(`.week[data-form=${ form }]`);
    for (let i = 1; i < $(container).children().length; i++) {
      $($(container).children()[i]).attr('id', i);
    }
  }
}

function onDailyPercentChange() {
  const form = $(this).data('form');
  const season = $(`.season-rate[data-form=${ form }]`).val();

  if (!data[form]) data[form] = {}
  if (!data[form][season]) data[form][season] = {}

  const parent_id = $(this).parent().parent().parent().attr('id') || 0;

  if (!data[form][season]['weeks']) data[form][season]['weeks'] = [];
  if (!data[form][season]['weeks'][parent_id]) data[form][season]['weeks'][parent_id] = {}

  data[form][season]['weeks'][parent_id]['daily_percent'] = $(this).val();
}
