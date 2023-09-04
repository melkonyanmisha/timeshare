<?php

function handle_price_calc_data()
{
    check_ajax_referer('calc-security-nonce', 'security');

    if ( ! empty($_POST['timesharePriceCalcData'])) {
        update_option(TIMESHARE_PRICE_CALC_DATA, stripslashes($_POST['timesharePriceCalcData']));
    }

    wp_send_json_success(stripslashes($_POST['timesharePriceCalcData']));
}

add_action('wp_ajax_nopriv_price_calc_data', 'handle_price_calc_data');
add_action('wp_ajax_price_calc_data', 'handle_price_calc_data');

/**
 * @param array $timeshare_users
 *
 * @return void
 */
function render_timeshare_user_price_calc(array $timeshare_users): void
{
    ob_start();
    ?>
    <div id="timeshare-user-price-calculation-section">
        <div class="container">
            <div class="forms">
                <div class="form">
                    <div class="seasons-dates-range">
                        <h2>More than 6 months</h2>
                        <div class="range-blocks" data-form="more_six">
                            <div class="range-block">
                                <div class="season-date-range">
                                    <input type="date" class="date-range" data-form="more_six" data-range="from">
                                    <input type="date" class="date-range" data-form="more_six" data-range="to">
                                </div>
                                <button class="add-date" data-form="more_six">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="season-sets">
                        <select class="season-rate" id="season-rate-form-one" data-form="more_six">
                            <option value="">Select Season</option>
                            <option value="low">Low</option>
                            <option value="normal">Normal</option>
                            <option value="hot">Hot</option>
                            <option value="very_hot">Very Hot</option>
                            <option value="special">Special</option>
                        </select>
                    </div>

                    <div class="discount-by-date">
                        <select class="discount-mode" data-form="more_six">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                        </select>

                        <input type="number" class="weekly-percent" min="0" placeholder="Weekly percent"
                               data-form="more_six">
                        <input type="number" class="discount-sets" min="1" value="1" data-form="more_six">
                    </div>

                    <div class="weekdays">
                        <table>
                            <thead>
                            <tr>
                                <th>Mon</th>
                                <th>Tue</th>
                                <th>Wed</th>
                                <th>Thu</th>
                                <th>Fri</th>
                                <th>Sat</th>
                                <th>Sun</th>
                                <th>%</th>
                            </tr>
                            </thead>
                            <tbody class="week" data-form="more_six">
                            <tr data-form="more_six">
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="more_six"
                                                data-week="monday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="more_six"
                                                data-week="tuesday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="more_six"
                                                data-week="wednesday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="more_six"
                                                data-week="thursday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="more_six"
                                                data-week="friday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="more_six"
                                                data-week="saturday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="more_six"
                                                data-week="sunday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="number" class="daily-percent" min="0" value="0"
                                                data-form="more_six"></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form">
                    <div class="seasons-dates-range">
                        <h2>4-6 months before</h2>
                        <div class="range-blocks" data-form="four_six_before">
                            <div class="range-block">
                                <div class="season-date-range">
                                    <input type="date" class="date-range" data-form="four_six_before" data-range="from">
                                    <input type="date" class="date-range" data-form="four_six_before" data-range="to">
                                </div>
                                <button class="add-date" data-form="four_six_before">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="season-sets">
                        <select class="season-rate" id="season-rate-form-two" data-form="four_six_before">
                            <option value="">Select Season</option>
                            <option value="low">Low</option>
                            <option value="normal">Normal</option>
                            <option value="hot">Hot</option>
                            <option value="very_hot">Very Hot</option>
                            <option value="special">Special</option>
                        </select>
                    </div>

                    <div class="discount-by-date">
                        <select class="discount-mode" data-form="four_six_before">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                        </select>

                        <input type="number" class="weekly-percent" min="0" placeholder="Weekly percent"
                               data-form="four_six_before">
                        <input type="number" class="discount-sets" min="1" value="1" data-form="four_six_before">
                    </div>

                    <div class="weekdays">
                        <table>
                            <thead>
                            <tr>
                                <th>Mon</th>
                                <th>Tue</th>
                                <th>Wed</th>
                                <th>Thu</th>
                                <th>Fri</th>
                                <th>Sat</th>
                                <th>Sun</th>
                                <th>%</th>
                            </tr>
                            </thead>
                            <tbody class="week" data-form="four_six_before">
                            <tr data-form="four_six_before">
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="four_six_before"
                                                data-week="monday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="four_six_before"
                                                data-week="tuesday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="four_six_before"
                                                data-week="wednesday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="four_six_before"
                                                data-week="thursday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="four_six_before"
                                                data-week="friday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="four_six_before"
                                                data-week="saturday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="four_six_before"
                                                data-week="sunday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="number" class="daily-percent" min="0" value="0"
                                                data-form="four_six_before"></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form">
                    <div class="seasons-dates-range">
                        <h2>2-4 months before</h2>
                        <div class="range-blocks" data-form="two_four_before">
                            <div class="range-block">
                                <div class="season-date-range">
                                    <input type="date" class="date-range" data-form="two_four_before" data-range="from">
                                    <input type="date" class="date-range" data-form="two_four_before" data-range="to">
                                </div>
                                <button class="add-date" data-form="two_four_before">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="season-sets">
                        <select class="season-rate" id="season-rate-form-three" data-form="two_four_before">
                            <option value="">Select Season</option>
                            <option value="low">Low</option>
                            <option value="normal">Normal</option>
                            <option value="hot">Hot</option>
                            <option value="very_hot">Very Hot</option>
                            <option value="special">Special</option>
                        </select>
                    </div>

                    <div class="discount-by-date">
                        <select class="discount-mode" data-form="two_four_before">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                        </select>

                        <input type="number" class="weekly-percent" min="0" placeholder="Weekly percent"
                               data-form="two_four_before">
                        <input type="number" class="discount-sets" min="1" value="1" data-form="two_four_before">
                    </div>

                    <div class="weekdays">
                        <table>
                            <thead>
                            <tr>
                                <th>Mon</th>
                                <th>Tue</th>
                                <th>Wed</th>
                                <th>Thu</th>
                                <th>Fri</th>
                                <th>Sat</th>
                                <th>Sun</th>
                                <th>%</th>
                            </tr>
                            </thead>
                            <tbody class="week" data-form="two_four_before">
                            <tr data-form="two_four_before">
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="two_four_before"
                                                data-week="monday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="two_four_before"
                                                data-week="tuesday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="two_four_before"
                                                data-week="wednesday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="two_four_before"
                                                data-week="thursday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="two_four_before"
                                                data-week="friday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="two_four_before"
                                                data-week="saturday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="two_four_before"
                                                data-week="sunday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="number" class="daily-percent" min="0" value="0"
                                                data-form="two_four_before"></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form">
                    <div class="seasons-dates-range">
                        <h2>Less than 2 months</h2>
                        <div class="range-blocks" data-form="less_two">
                            <div class="range-block">
                                <div class="season-date-range">
                                    <input type="date" class="date-range" data-form="less_two" data-range="from">
                                    <input type="date" class="date-range" data-form="less_two" data-range="to">
                                </div>
                                <button class="add-date" data-form="less_two">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="season-sets">
                        <select class="season-rate" id="season-rate-form-four" data-form="less_two">
                            <option value="">Select Season</option>
                            <option value="all">All</option>
                            <option value="low">Low</option>
                            <option value="normal">Normal</option>
                            <option value="hot">Hot</option>
                            <option value="very_hot">Very Hot</option>
                            <option value="special">Special</option>
                        </select>
                        <input type="number" class="yearly-percent" min="0" placeholder="Yearly percent" data-form="less_two">
                    </div>

                    <div class="discount-by-date">
                        <select class="discount-mode" data-form="less_two">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                        </select>

                        <input type="number" class="weekly-percent" min="0" placeholder="Weekly percent"
                               data-form="less_two">
                        <input type="number" class="discount-sets" min="1" value="1" data-form="less_two">
                    </div>

                    <div class="weekdays">
                        <table>
                            <thead>
                            <tr>
                                <th>Mon</th>
                                <th>Tue</th>
                                <th>Wed</th>
                                <th>Thu</th>
                                <th>Fri</th>
                                <th>Sat</th>
                                <th>Sun</th>
                                <th>%</th>
                            </tr>
                            </thead>
                            <tbody class="week" data-form="less_two">
                            <tr data-form="less_two">
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="less_two"
                                                data-week="monday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="less_two"
                                                data-week="tuesday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="less_two"
                                                data-week="wednesday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="less_two"
                                                data-week="thursday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="less_two"
                                                data-week="friday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="less_two"
                                                data-week="saturday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="checkbox" class="weekday-input" data-form="less_two"
                                                data-week="sunday"></div>
                                </td>
                                <td class="weekday">
                                    <div><input type="number" class="daily-percent" min="0" value="0"
                                                data-form="less_two"></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="save-button">
                <button>Save</button>
            </div>

            <div class="timeshare-toast">
                <span>Successfully saved!</span>
            </div>
        </div>
    </div>
    <?php
    // Get the captured output and clear the buffer
    $output = ob_get_clean();

    // Display the modified output
    echo $output;
}