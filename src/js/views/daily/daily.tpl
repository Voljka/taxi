    <div class="row">
        <div class="col-md-12" ng-show="hasSelectedRow">
            <button class="btn btn-primary" ng-click="showDetails()">
                Details
            </button>
            <button class="btn btn-primary" ng-show="rowAllowedForSaving" ng-click="showPayouts()">
                Payouts
            </button>
            <button class="btn btn-primary" ng-show="rowAllowedForSaving" ng-click="saveDriverCalc()">
                Save Calc
            </button>
            <button class="btn btn-primary" ng-show="dayAllowedForSaving" ng-click="saveDayCalc()">
                Save Day
            </button>
        </div>
    </div>

    <div class="row">
        <flash-message>
            <div class="flash-div">{{ flash.text}}</div>
        </flash-message>
    </div>

    <div class="row">
      <table class="table table-bordered table-condensed table_2200px">
        <thead>
          <tr>
            <td rowspan="2">#</td>
            <td rowspan="2">Диспетчер</td>
            <td rowspan="2">Фио</td>
            <td rowspan="2">Авто</td>
            <td class="td_80px" rowspan="2">Всего,<br>нал</td>
            <td class="td_80px" rowspan="2">Всего,<br>без комис</td>
            <td class="td_80px" rowspan="2">Всего,<br>c комис</td>
            <td class="td_80px" rowspan="2">Fuel</td>
            <td class="td_80px" rowspan="2">Rental</td>
            <td class="td_80px" rowspan="2">Franchise</td>
            <td class="td_80px" rowspan="2">Fines</td>
            <td class="td_80px" rowspan="2">Debt</td>
            <td class="td_80px" rowspan="2">Wage Rule</td>
            <td class="td_80px" rowspan="2">Wage</td>
            <td class="td_80px" rowspan="2">Income</td>
            <td class="td_80px" rowspan="2">Deficit<br>Covered<br>by us</td>
            <td class="td_80px" rowspan="2">Deficit<br>Covered<br>by Driver</td>
            <td class="td_80px" rowspan="2">Payed</td>
            <td class="td_80px" rowspan="2">To Pay</td>
            <td class="td_80px" rowspan="2">Deferred<br>to debt</td>
            <td colspan="4">Uber</td>
            <td colspan="5">Gett</td>
            <td colspan="4">Yandex</td>
            <td rowspan="2">От борта</td>
          </tr>
          <tr>
            <td class="td_80px">Cash</td>
            <td class="td_80px">Misc</td>
            <td class="td_80px">Income<br>excl.%</td>
            <td class="td_80px">Income<br>incl.%</td>
            <td class="td_80px">Cash</td>
            <td class="td_80px">Misc</td>
            <td class="td_80px">Income<br>incl.%</td>
            <td class="td_80px">Income<br>excl.%</td>
            <td class="td_80px">Resid<br>on acc.</td>
            <td class="td_80px">Cash</td>
            <td class="td_80px">Non-cash</td>
            <td class="td_80px">Income<br>incl.%</td>
            <td class="td_80px">Income<br>excl.%</td>
          </tr>
        </thead>
        <tbody>
            <tr ng-repeat-start="(shift_date, data) in dailyList"></tr>

            <tr ng-repeat="driver in data" ng-class="driver.selected ? 'item-selected' : ( driver.fuel_expenses ? 'row-already-saved' : '')">
                <td ng-click="selectDriver(shift_date, driver)">{{$index + 1}}</td>
                <td>dispatcher</td>
                <td>{{driver.surname + ' ' + driver.firstname + ' ' + driver.patronymic }}</td>
                <td>{{driver.state_number}}</td>

                <td class="digit cash_column">{{driver.total_cash | asPrice}}</td>
                <td class="digit total_netto_column">{{driver.total_netto | asPrice}}</td>
                <td class="digit">{{driver.total | asPrice}}</td>

                <td class="digit">
                    <span ng-show="! driver.editingFuel">{{driver.fuel | asPrice}} 
                        <span class="glyphicon glyphicon-pencil edit-btn" ng-click="editFuel(shift_date, driver)" ng-show="! (daysBetween(lastReport, shift_date).toFixed(0) > 1) && ! driver.fuel_expenses">
                        </span>
                    </span>
                    <input ng-keypress="checkEnter($event, driver)" ng-show="driver.editingFuel" class="numberInput" type="number" ng-model="driver.fuel">
                </td>
                <td class="digit">{{driver.rental | asPrice}}</td>
                <td class="digit">{{driver.franchise | asPrice}} <span class="glyphicon glyphicon-remove-circle cancel-btn" ng-click="removeFranchise(shift_date, driver)" ng-show="! (daysBetween(lastReport, shift_date).toFixed(0) > 1)"></span></td>
                <td class="digit">{{driver.fine | asPrice}} <span class="glyphicon glyphicon-remove-circle cancel-btn" ng-click="removeFine(shift_date, driver)" ng-show="! (daysBetween(lastReport, shift_date).toFixed(0) > 1) && ! driver.fuel_expenses"></span></td>
                <td class="digit">{{driver.debt | asPrice}} <span class="glyphicon glyphicon-remove-circle cancel-btn" ng-click="removeDebt(shift_date, driver)" ng-show="! (daysBetween(lastReport, shift_date).toFixed(0) > 1) && ! driver.fuel_expenses"></span></td>

                <td class="">
                    <select ng-change="changeRule(driver)" ng-model="driver.rule_default_id" ng-init="driver.rule_default_id" ng-disabled="driver.fuel_expenses">   
                        <option value="1">default</option>
                        <option value="2">60/40</option>
                        <option value="3">50/50</option>
                        <option value="4">40/60</option>
                    </select>                    
                </td>
                <td class="digit wage_column">{{driver.wage | asPrice}}</td>
                <td class="digit income_column">{{driver.income | asPrice}}</td>
                <td class="digit">
                    <span ng-show="! driver.editingCover">{{driver.covered | asPrice}} 
                        <span class="glyphicon glyphicon-pencil edit-btn" ng-click="editCover(shift_date, driver)" ng-show="! (daysBetween(lastReport, shift_date).toFixed(0) > 1) && ! driver.fuel_expenses">
                        </span>
                    </span>
                    <input ng-keypress="checkEnter($event, driver)" ng-show="driver.editingCover" class="numberInput" type="number" ng-model="driver.covered">
                </td>
                <td class="digit">{{driver.covered_company_deficit | asPrice}}</td>

                <td class="digit">{{driver.total_payouts | asPrice}}</td>
                <td class="digit to-pay-column">{{ (driver.wage - driver.total_payouts + driver.covered + driver.income_deficit + driver.deferred_debt )| asPrice}}</td>
                <td class="digit">{{driver.deferred_debt | asPrice}}</td>

                <td class="digit">{{driver.uber_sum_cash | asPrice}}</td>
                <td class="digit">{{driver.uber_correction_result | asPrice}}</td>
                <td class="digit">{{driver.uber_total_netto | asPrice}}</td>
                <td class="digit">{{driver.uber_total | asPrice}}</td>

                <td class="digit">{{driver.gett_sum_cash | asPrice}}</td>
                <td class="digit">{{driver.gett_correction_result | asPrice}}</td>
                <td class="digit">{{driver.gett_sum_fare | asPrice}}</td>
                <td class="digit">{{driver.gett_total | asPrice}}</td>
                <td class="digit">{{driver.gett_total_netto | asPrice}}</td>

                <td class="digit"> <input class="numberInput" type="number" ng-model="driver.yandex_cash" ng-disabled="daysBetween(lastReport, shift_date).toFixed(0) > 1 || driver.fuel_expenses" ng-blur="recalcWage(driver)"></td>
                <td class="digit"> <input class="numberInput" type="number" ng-model="driver.yandex_non_cash" ng-disabled="daysBetween(lastReport, shift_date).toFixed(0) > 1 || driver.fuel_expenses" ng-blur="recalcWage(driver)"></td>
                <td class="digit">{{driver.yandex_total | asPrice}}</td>
                <td class="digit">{{driver.yandex_total_netto | asPrice}}</td>

                <td class="digit"><input class="numberInput" type="number" ng-model="driver.from_hand_amount" ng-disabled="daysBetween(lastReport, shift_date).toFixed(0) > 1 || driver.fuel_expenses" ng-blur="recalcWage(driver)"></td>

            </tr>
            <tr ng-repeat-end class="success">
                <td colspan="25">Total for a day {{shift_date}}</td>
            </tr>
            
        </tbody>
      </table>
    </div>
    
    <div class="cover" ng-show="isShowingDetails || isShowingPayouts || isShowingDebtWindow">
    </div>

    <div class="cover-modal" ng-if="isShowingPayouts" >
        <div id="modal-payouts">

            <div class="row">
                <div class="col md-12">
                    <center>
                        {{currentDriver.surname + ' ' + currentDriver.firstname + ' ' + currentDriver.patronymic }}
                    </center>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <center>
                        {{shiftDate}}
                    </center>    
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <center>
                        <span ng-show="currentDriver.bank_name=='!!!! Наличными !!!'">
                            Cash !!!
                        </span>
                    </center>    
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <center>
                        <span ng-show="currentDriver.bank_name!=='!!!! Наличными !!!'">
                            Card Number: {{currentDriver.card_number}}
                        </span>
                    </center>    
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <center>
                        <span ng-show="currentDriver.bank_name!=='!!!! Наличными !!!'">
                            Bank: {{currentDriver.bank_name}}
                        </span>
                    </center>    
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <center>
                        <span ng-show="currentDriver.beneficiar.length > 1">
                            Beneficiar: {{currentDriver.beneficiar}}
                        </span>
                    </center>    
                </div>
            </div>

            <div class="row">
                <table class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th>Payed at</th>
                            <th>Charged</th>
                            <th>Payed</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td class="digit">
                                {{(currentDriver.wage) | asPrice}}
                            </td>
                            <td></td>
                        </tr>
                        <tr ng-repeat="payout in payouts" ng-click="selectPayout(payout)" ng-class="payout.selected ? 'item-selected' : ''">
                            <td>{{payout.payed_at}}</td>
                            <td></td>
                            <td class="digit">
                                <span ng-show="! payout.editing">
                                    {{payout.amount | asPrice}}
                                    <span class="glyphicon glyphicon-pencil edit-btn" ng-click="updatePayout(payout)">
                                    </span>
                                    <span class="glyphicon glyphicon-remove-circle cancel-btn" ng-click="deletePayout(payout)">
                                    </span>
                                </span>

                                <input ng-keypress="checkPayoutEnter($event, payout)" ng-show="payout.editing" class="numberInput" type="number" ng-model="payout.amount">
                            </td>
                        </tr>

                        <tr class="payouts-total">
                            <td>TOTAL</td>
                            <td class="digit">
                                {{(currentDriver.wage) | asPrice}}
                            </td>
                            <td class="digit">{{totalPayouts | asPrice}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row">
                Residual to Pay : <b>{{ residualToPay | asPrice }}</b>
            </div>

            <div class="row">
                <flash-message>
                    <div class="flash-div">{{ flash.text}}</div>
                </flash-message>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <center>
                        <button class="btn btn-primary" ng-click="addPayout()" ng-show="residualToPay > 0 && ! isPayoutEditing && currentDriver.wage > 0">
                            Add
                        </button>
                    </center>
                </div>
                <div class="col-md-4">
                    <center>
                        <button class="btn btn-primary" ng-click="closePayouts()" ng-show="! isPayoutEditing">
                            Close
                        </button>
                    </center>
                </div>
            </div>
        </div>
    </div>

    <div class="cover-modal" ng-if="isShowingDebtWindow" >
        <div id="modal-debt">
            <div class="row">
                <div class="col md-12">
                    <center>
                        {{currentDriver.surname + ' ' + currentDriver.firstname + ' ' + currentDriver.patronymic }}
                    </center>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <center>
                        {{shiftDate}}
                    </center>    
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <center>
                        <span>
                            Residual from current debt: {{ newDebt.debtLeftToPay }}
                        </span>
                    </center>    
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <center>
                        <span>
                            New deficit: {{ newDebt.additionAmount }}
                        </span>
                    </center>    
                </div>
            </div>

            <br>

            <div class="row" ng-show="currentDriver.last_debt && newDebt.debtLeftToPay > 0">
                <div class="col-md-12">
                    <p><center>Current Debt conditions:</center></p>
                    <p ng-show="currentDriver.last_debt.min_daily_wage > 0"> Min Daily Wage: {{currentDriver.last_debt.min_daily_wage}}</p>
                    <p ng-show="currentDriver.last_debt.is_total_income_as_fine == 1"> All wage charged as the debt payment </p>
                    <p ng-show="currentDriver.last_debt.is_total_income_as_fine != 1 && currentDriver.last_debt.min_daily_wage == 0 ">Iteration Sum: {{currentDriver.last_debt.iteration_sum}} </p>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-md-12">
                    <center>
                        <span>
                            New debt conditions: 
                        </span>
                    </center>    
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <p><b>Charge rule</b></p>

                    <p><input ng-model="obj.debtRule" type="radio" ng-value="1"> Iteration Charge </p>
                    <p><input ng-model="obj.debtRule" type="radio" ng-value="2" > Min Wage </p>
                    <p><input ng-model="obj.debtRule" type="radio" ng-value="3" > Whole Wage to Charge </p>
                    <p><input ng-model="obj.debtRule" type="radio" ng-value="4" ng-disabled="! (currentDriver.wage > 0 && currentDriver.wage > -newDebt.additionAmount)"> Cover by current wage </p>
                </div>
                <div class="col-md-6">
                    <p> Iteration Sum: <input ng-model="obj.newDebtIterationSum" type="number" ng-disabled="! (obj.debtRule==1)"> </p>
                    <p> Min Wage: <input ng-model="obj.debtMinWage" type="number" ng-disabled="! (obj.debtRule==2)"> </p>
                    <p> Decrease debt by current wage <input ng-model="obj.debtDecreasingByWage" type="checkbox" ng-disabled="! (currentDriver.wage > 0 )"> </p>

                </div>
            </div>

            <div class="row">
                <flash-message>
                    <div class="flash-div">{{ flash.text}}</div>
                </flash-message>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <center>
                        <button class="btn btn-primary" ng-click="saveNewDebtRule()">
                            Save Debt Rule
                        </button>
                        <button class="btn btn-primary" ng-click="closeDebtWindow()">
                            Close
                        </button>
                    </center>
                </div>
            </div>
        </div>
    </div>


<!--    <div class="cover-modal" ng-if="isShowingDetails" >
        <div id="modal-details">
            <div class="row">
                <table class="table table-condesed table-bordered table-details">
                    <tr>
                        <th>Shift</th>
                        <th>Fullname</th>
                        <th>Auto number</th>
                    </tr>
                    <tr>
                        <td>{{shiftDate}}</td>
                        <td>{{currentDriver.surname + ' ' + currentDriver.firstname + ' ' + currentDriver.patronymic }}</td>
                        <td>{{currentDriver.state_number}}</td>
                    </tr>
                </table>
            </div>
            <div class="row">
                <table class="table table-condesed table-bordered table-details">
                    <tr>
                        <th rowspan="2" class="table-caption">Uber</th>
                        <th>Cash</th>
                        <th>Misc</th>
                        <th>Brutto</th>
                        <th>Netto</th>
                    </tr>
                    <tr>
                        <td>{{currentDriver.uber_sum_cash | asPrice}}</td>
                        <td>{{currentDriver.uber_correction_result | asPrice}}</td>
                        <td>{{currentDriver.uber_total | asPrice}}</td>
                        <td>{{currentDriver.uber_total_netto | asPrice}}</td>
                    </tr>
                </table>
            </div>

            <div class="row">
                <table class="table table-condesed table-bordered table-details">
                    <tr>
                        <th rowspan="2" class="table-caption">Gett</th>
                        <th>Cash</th>
                        <th>Misc</th>
                        <th>Brutto</th>
                        <th>Netto</th>
                    </tr>
                    <tr>
                        <td>{{currentDriver.gett_sum_cash | asPrice}}</td>
                        <td>{{currentDriver.gett_correction_result | asPrice}}</td>
                        <td>{{currentDriver.gett_total | asPrice}}</td>
                        <td>{{currentDriver.gett_total_netto | asPrice}}</td>
                    </tr>
                </table>
            </div>

            <div class="row">
                <table class="table table-condesed table-bordered table-details">
                    <tr>
                        <th rowspan="2" class="table-caption">Yandex</th>
                        <th>Cash</th>
                        <th>Non-cash</th>
                        <th>Brutto</th>
                        <th>Netto</th>
                        <th class="table-caption">From board</th>
                    </tr>
                    <tr>
                        <td>{{currentDriver.yandex_cash | asPrice}}</td>
                        <td>{{currentDriver.yandex_non_cash | asPrice}}</td>
                        <td>{{currentDriver.yandex_total | asPrice}}</td>
                        <td>{{currentDriver.yandex_total_netto | asPrice}}</td>
                        <td>{{currentDriver.from_hand_amount | asPrice}}</td>
                    </tr>
                </table>
            </div>

            <div class="row">
                <table class="table table-condesed table-bordered table-details">
                    <tr>
                        <th rowspan="2" class="table-caption">Total</th>
                        <th>Cash</th>
                        <th>Total brutto</th>
                        <th>Total netto</th>
                    </tr>
                    <tr>
                        <td>{{currentDriver.total_cash | asPrice}}</td>
                        <td>{{currentDriver.total | asPrice}}</td>
                        <td>{{currentDriver.total_netto | asPrice}}</td>
                    </tr>
                </table>
            </div>

            <div class="row">
                <table class="table table-condesed table-bordered table-details">
                    <tr>
                        <th rowspan="2" class="table-caption">Withdrawals</th>
                        <th>Fuel</th>
                        <th>Franchise</th>
                        <th>Debt</th>
                        <th>Fines</th>
                        <th>Rental</th>
                    </tr>
                    <tr>
                        <td>{{currentDriver.fuel | asPrice}}</td>
                        <td>{{currentDriver.franchise | asPrice}}</td>
                        <td>{{currentDriver.debt | asPrice}}</td>
                        <td>{{currentDriver.fine | asPrice}}</td>
                        <td>{{currentDriver.rental | asPrice}}</td>
                    </tr>
                </table>
            </div>

            <div class="row">
                <table class="table table-condesed table-bordered table-details">
                    <tr>
                        <th rowspan="2" class="table-caption">Result</th>
                        <th>Wage</th>
                        <th>Covered</th>
                        <th>Payed</th>
                        <th>Residual<br>to pay</th>
                        <th>Income</th>
                    </tr>
                    <tr>
                        <td>{{currentDriver.wage | asPrice}}</td>
                        <td>{{currentDriver.covered | asPrice}}</td>
                        <td>{{currentDriver.total_payouts | asPrice}}</td>
                        <td>{{(currentDriver.wage - currentDriver.total_payouts)| asPrice}}</td>
                        <td>{{currentDriver.income | asPrice}}</td>
                    </tr>
                </table>
            </div>

            <div class="row">
                <button class="btn btn-primary" ng-click="closeDetails()">
                    Close
                </button>
            </div>
        </div>
    </div>
 -->