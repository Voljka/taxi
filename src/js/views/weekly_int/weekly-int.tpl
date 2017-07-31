<div class="row">
  <div class="col-md-4">
    <div class="input-group">
      <span class="input-group-addon">Дата начала периода </span>
      <input class="form-control" ng-model="start" type="date" />
    </div>
  </div>
  <div class="col-md-4">
    <div class="input-group">
      <span class="input-group-addon">Дата окончания периода </span>
      <input class="form-control" ng-model="end" type="date" />
    </div>
  </div>
  <div class="col-md-4">
    <button class="btn btn-primary" ng-click="makeSummary()">Сформировать</button>
  </div> 
</div>

<div class="row">
  <button class=" btn btn-primary" ng-click="saveFreelancerData()" ng-if="selectedFreelancer">Сохранить расчет</utton>

  <button class=" btn btn-primary" ng-click="showPayoutsToDriver(false)" ng-if="selectedFreelancer">Вылпаты водителю</button>

</div>

<div class="row">
  <h3>Фрилансеры 7/0 </h3>
  <table class="table table-bordered freelance-table" id="table-freelancers">
    <thead>
      <tr>
        <td rowspan="2">ФИО</td>
        <td width="80px" rowspan="2">Всего,<br>начислено</td>
        <td width="80px" rowspan="2">Комиссия<br>банка</td>
        <td width="80px" rowspan="2">Всего,<br>к выдаче</td>
        <td width="80px" rowspan="2">Выплачено<br>водителю</td>
        <td width="80px" rowspan="2">Долг</td>
        <td width="80px" rowspan="2">Остаток<br>к оплате</td>
        <td width="150px" rowspan="2">Карта</td>

        <td colspan="3" class="yandex_column">Yandex</td>
        <td colspan="5" class="uber_column" >Uber</td>
        <td colspan="6" class="gett_column">Gett</td>
        <td colspan="6" class="wheely_column">Wheely</td>

      </tr>
      <tr>
        <td width="80px" class="yandex_column">Запросы</td>
        <td width="80px" class="yandex_column">Оплачено</td>
        <td width="80px" class="yandex_column">К оплате</td>

        <td width="80px" class="uber_column">Доход</td>
        <td width="80px" class="uber_column">в т.ч.<br>Корр-вки</td>
        <td width="80px" class="uber_column">Cash</td>
        <td width="80px" class="uber_column">Нам</td>
        <td width="80px" class="uber_column">К выдаче</td>

        <td width="80px" class="gett_column">Доход</td>
        <td width="80px" class="gett_column">Интерес Get<br>(17.7%)</td>
        <td width="80px" class="gett_column">Наш Интерес <br>(3.3%)</td>
        <td width="80px" class="gett_column">Нал</td>
        <td width="80px" class="gett_column">Корр-вки</td>
        <td width="80px" class="gett_column">К выдаче</td>

        <td width="80px" class="wheely_column">Тариф</td>
        <td width="80px" class="wheely_column">Комиссия</td>
        <td width="80px" class="wheely_column">Чаевые и<br>пароковка</td>
        <td width="80px" class="wheely_column">Штрафы</td>
        <td width="80px" class="wheely_column">Нам</td>
        <td width="80px" class="wheely_column">К выдаче</td>
      </tr>    
    </thead>
    <tfoot>
      <tr class="tfoot-centered">
        <td rowspan="2">ФИО</td>
        <td width="80px" rowspan="2">Всего,<br>начислено</td>
        <td width="80px" rowspan="2">Комиссия<br>банка</td>
        <td width="80px" rowspan="2">Всего,<br>к выдаче</td>
        <td width="80px" rowspan="2">Выплачено<br>водителю</td>
        <td width="80px" rowspan="2">Долг</td>
        <td width="80px" rowspan="2">Остаток<br>к оплате</td>
        <td width="150px" rowspan="2">Карта</td>

        <td colspan="3" class="yandex_column">Yandex</td>
        <td colspan="5" class="uber_column" >Uber</td>
        <td colspan="6" class="gett_column">Gett</td>
        <td colspan="6" class="wheely_column">Wheely</td>

      </tr>
      <tr class="tfoot-centered">
        <td width="80px" class="yandex_column">Запросы</td>
        <td width="80px" class="yandex_column">Оплачено</td>
        <td width="80px" class="yandex_column">К оплате</td>

        <td width="80px" class="uber_column">Доход</td>
        <td width="80px" class="uber_column">в т.ч.<br>Корр-вки</td>
        <td width="80px" class="uber_column">Cash</td>
        <td width="80px" class="uber_column">Нам</td>
        <td width="80px" class="uber_column">К выдаче</td>

        <td width="80px" class="gett_column">Доход</td>
        <td width="80px" class="gett_column">Интерес Get<br>(17.7%)</td>
        <td width="80px" class="gett_column">Наш Интерес <br>(3.3%)</td>
        <td width="80px" class="gett_column">Нал</td>
        <td width="80px" class="gett_column">Корр-вки</td>
        <td width="80px" class="gett_column">К выдаче</td>

        <td width="80px" class="wheely_column">Тариф</td>
        <td width="80px" class="wheely_column">Комиссия</td>
        <td width="80px" class="wheely_column">Чаевые и<br>пароковка</td>
        <td width="80px" class="wheely_column">Штрафы</td>
        <td width="80px" class="wheely_column">Нам</td>
        <td width="80px" class="wheely_column">К выдаче</td>
      </tr>    
      <tr class="total-row">
        <td colspan="3">ИТОГО :</td>
        <td class="digit">{{sumBy(free7_0,'total_to_pay') | asPrice2 }}</td>
        <td></td>
        <td></td>
        <td class="digit">{{sumBy(free7_0,'residual_to_pay') | asPrice2 }}</td>
        <td></td>
      </tr>
    </tfoot>
    <tbody ng-if="free7_0.length > 0">
      <tr ng-repeat="el in free7_0" ng-class="el.selected ? 'item-selected' : ''">
        <td ng-click="selectFreeDriver(el)"> 
          {{ el.surname }}  {{el.firstname}} {{el.patronymic}}
        </td>

        <td class="digit"><b>{{ el.total_payable | asPrice2 }}</b></td>
        <td class="digit"><b>{{ el.bank_comission | asPrice2 }}</b></td>
        <td class="digit"><b>{{ el.total_to_pay | asPrice2 }} </b></td>
        <td class="digit"><b>{{ el.payed_to_driver | asPrice2 }} </b></td>
        <td class="digit"> 
            <input type="number" ng-model="el.debt" class="numberInput" ng-change="recalcDriverTotals(el)">
        </td>
        <td class="digit"><b>{{ el.residual_to_pay | asPrice2 }} </b></td>
        <td class="digit"><b>{{ (el.card_number == "0" ? "" : el.card_number) }}<br>{{ el.beneficiar }}</b></td>

        <!-- <td class="digit yandex_column">{{ el.yandex_asks | asPrice2 }}</td> -->
        <td class="digit yandex_column"> 
            <input type="number" ng-model="el.yandex_asks" class="numberInput" ng-change="recalcDriverTotals(el)">
        </td>
        <td class="digit yandex_column">{{ el.yandex_paid | asPrice2 }}</td>
        <td class="digit yandex_column">{{ el.yandex_residual | asPrice2 }}</td>

        <td class="digit uber_column">{{ el.uber_total_netto | asPrice2 }}</td>
        <td class="digit uber_column">{{ el.uber_correction | asPrice2 }}</td>
        <td class="digit uber_column">{{ el.uber_sum_cash | asPrice2 }}</td>
        <td class="digit uber_column">{{ el.uber_total_interest | asPrice2 }}</td>
        <td class="digit uber_column">{{ el.uber_total_to_pay | asPrice2 }}</td>


        <td class="digit gett_column">{{ el.gett_total | asPrice2 }}</td>
        <td class="digit gett_column">{{ el.gett_total_commission | asPrice2 }}</td>
        <td class="digit gett_column">{{ el.gett_total_interest | asPrice2 }}</td>
        <td class="digit gett_column">{{ el.gett_total_cash | asPrice2 }}</td>
        <td class="digit gett_column">{{ el.gett_correction | asPrice2 }}</td>
        <td class="digit gett_column">{{ el.gett_total_to_pay | asPrice2 }}</td>

<!--         <td class="digit yandex_column"> 
          <span ng-if="el.selected">
            <input type="number" ng-model="el.yandex_non_cash" ng-change="recalcDriverTotals(el)">
          </span>
          <span ng-if="! el.selected">
            {{ el.yandex_non_cash | asPrice2 }}
          </span>
        </td> -->

        <td class="digit wheely_column">{{ el.wheely_sum_fare | asPrice2 }}</td>
        <td class="digit wheely_column">{{ el.wheely_sum_comission | asPrice2 }}</td>
        <td class="digit wheely_column">{{ el.wheely_sum_boost | asPrice2 }}</td>
        <td class="digit wheely_column">{{ el.wheely_sum_fines | asPrice2 }}</td>
        <td class="digit wheely_column">{{ el.wheely_interest | asPrice2 }}</td>
        <td class="digit wheely_column">{{ el.wheely_to_pay | asPrice2 }}</td>

      </tr>
    </tbody>
  </table>
</div>

<div class="row">
  <h3>Автопарки </h3>


    <table class="table table-condensed table-bordered table-autoparks" id="table{{park[0].group_id}}" ng-repeat="park in parks">
      <thead>
        <tr ng-if="selectedParkDriver || park[0].is_park_driver_direct_paid=='0'">
          <td colspan="31" style="text-align: left">
            <button class=" btn btn-primary" ng-click="saveParkDriverData(park)" ng-if="park[0].is_park_driver_direct_paid=='0'">Сохранить расчет</button>
            <button class=" btn btn-primary" ng-click="showPayoutsToPark(park)" ng-if="park[0].is_park_driver_direct_paid=='0'">Выплаты парку</button>
            <button class=" btn btn-primary" ng-click="showPayoutsToDriver(true)" ng-if="selectedParkDriver && park[0].is_park_driver_direct_paid == '1'">Выплаты водителю</button>

          </td>
        </tr>
        <tr>
          <td rowspan="2"><b>{{park[0].group_name}}</b>
            <br>
            <button class=" btn btn-primary" ng-click="parkToXLS('table'+park[0].group_id)">Сохранить в XLS</button>            
          </td>
          <td width="80px" rowspan="2">Заработок<br>водителя</td>
          <td width="90px" rowspan="2">Откат,<br>2%</td>
          <td width="80px" rowspan="2">Всего,<br>начислено</td>
          <td width="80px" rowspan="2">Комиссия<br>банка</td>
          <td width="80px" rowspan="2">Всего,<br>к выдаче</td>
          <td width="80px" rowspan="2">Выплачено<br></td>
          <td width="80px" rowspan="2">Долг</td>
          <td width="80px" rowspan="2">Остаток<br>к оплате</td>

          <td width="150px" rowspan="2">Карта</td>

          <td colspan="3" class="yandex_column">Yandex</td>
          <td colspan="5" class="uber_column">Uber</td>
          <td colspan="6" class="gett_column">Gett</td>
          <td colspan="6" class="wheely_column">Wheely</td>

        </tr>
        <tr>
          <td width="80px" class="yandex_column">Запросы</td>
          <td width="80px" class="yandex_column">Оплачено</td>
          <td width="80px" class="yandex_column">К оплате</td>

          <td width="110px" class="uber_column">Доход</td>
          <td width="80px" class="uber_column">в т.ч.<br>Корр-вки</td>
          <td width="100px" class="uber_column">Cash</td>
          <td width="110px" class="uber_column">Нам</td>
          <td width="110px" class="uber_column">К выдаче</td>

          <td width="110px" class="gett_column">Доход</td>
          <td width="80px" class="gett_column">Комиссия</td>
          <td width="80px" class="gett_column">Нам,3.3%</td>
          <td width="100px" class="gett_column">Нал</td>
          <td width="80px" class="gett_column">Корр-вки</td>
          <td width="110px" class="gett_column">К выдаче</td>

          <td width="80px" class="wheely_column">Тариф</td>
          <td width="80px" class="wheely_column">Комиссия</td>
          <td width="80px" class="wheely_column">Чаевые и<br>пароковка</td>
          <td width="80px" class="wheely_column">Штрафы</td>
          <td width="80px" class="wheely_column">Нам</td>
          <td width="80px" class="wheely_column">К выдаче</td>        

        </tr>
      </thead>

      <tfoot>

        <tr class="tfoot-centered">
          <td rowspan="2"><b>{{park[0].group_name}}</b>
            <br>
            <button class=" btn btn-primary" ng-click="parkToXLS('table'+park[0].group_id)">Сохранить в XLS</button>            
          </td>
          <td width="80px" rowspan="2">Заработок<br>водителя</td>
          <td width="90px" rowspan="2">Откат,<br>2%</td>
          <td width="80px" rowspan="2">Всего,<br>начислено</td>
          <td width="80px" rowspan="2">Комиссия<br>банка</td>
          <td width="80px" rowspan="2">Всего,<br>к выдаче</td>
          <td width="80px" rowspan="2">Выплачено<br></td>
          <td width="80px" rowspan="2">Долг</td>
          <td width="80px" rowspan="2">Остаток<br>к оплате</td>

          <td width="150px" rowspan="2">Карта</td>

          <td colspan="3" class="yandex_column">Yandex</td>
          <td colspan="5" class="uber_column">Uber</td>
          <td colspan="6" class="gett_column">Gett</td>
          <td colspan="6" class="wheely_column">Wheely</td>

        </tr>
        <tr class="tfoot-centered">
          <td width="80px" class="yandex_column">Запросы</td>
          <td width="80px" class="yandex_column">Оплачено</td>
          <td width="80px" class="yandex_column">К оплате</td>

          <td width="110px" class="uber_column">Доход</td>
          <td width="80px" class="uber_column">в т.ч.<br>Корр-вки</td>
          <td width="100px" class="uber_column">Cash</td>
          <td width="110px" class="uber_column">Нам</td>
          <td width="110px" class="uber_column">К выдаче</td>

          <td width="110px" class="gett_column">Доход</td>
          <td width="80px" class="gett_column">Комиссия</td>
          <td width="80px" class="gett_column">Нам,3.3%</td>
          <td width="100px" class="gett_column">Нал</td>
          <td width="80px" class="gett_column">Корр-вки</td>
          <td width="110px" class="gett_column">К выдаче</td>

          <td width="80px" class="wheely_column">Тариф</td>
          <td width="80px" class="wheely_column">Комиссия</td>
          <td width="80px" class="wheely_column">Чаевые и<br>пароковка</td>
          <td width="80px" class="wheely_column">Штрафы</td>
          <td width="80px" class="wheely_column">Нам</td>
          <td width="80px" class="wheely_column">К выдаче</td>        
        </tr>  
        <tr class="total-row" ng-click="selectParkRow(park)">
          <td>ИТОГО :</td>
          <td class="digit">{{sumBy(park,'total_without_payback') | asPrice2 }}</td>
          
          <td class="digit" ng-if="park[0].group_id == '9'">{{(sumBy(park,'payback') + rafael_payback) | asPrice2 }}</td>
          <td class="digit" ng-if="park[0].group_id != '9'">{{sumBy(park,'payback') | asPrice2 }}</td>

          <td class="digit" ng-if="park[0].group_id == '9'">{{(sumBy(park,'total_payable') + rafael_payback) | asPrice2 }}</td>
          <td class="digit" ng-if="park[0].group_id != '9'">{{sumBy(park,'total_payable') | asPrice2 }}</td>
          <td></td>

          <td class="digit" ng-if="park[0].group_id == '9'">{{(sumBy(park,'total_to_pay') + (rafael_payback * (1 - park[0].bank_rate))) | asPrice2 }}</td>
          <td class="digit" ng-if="park[0].group_id != '9'">{{sumBy(park,'total_to_pay') | asPrice2 }}</td>

          <td class="digit" ng-if="park[0].group_id == '110'">{{sumBy(park,'payed_to_driver') | asPrice2 }}</td>
          <td class="digit" ng-if="park[0].group_id != '110'">{{park[0].park_paid | asPrice2 }}</td>
          
          <td></td>

          <td class="digit" ng-if="park[0].group_id == '110'">{{sumBy(park,'total_to_pay') - sumBy(park,'payed_to_driver') - sumBy(park,'debt') | asPrice2 }}</td>
          <td class="digit" ng-if="park[0].group_id == '9'">{{(sumBy(park,'total_to_pay') - park[0].park_paid + (rafael_payback * (1 - park[0].bank_rate))  - sumBy(park,'debt')) | asPrice2 }}</td>
          <td class="digit" ng-if="park[0].group_id != '9' && park[0].group_id != '110'">{{sumBy(park,'total_to_pay') - park[0].park_paid  - sumBy(park,'debt') | asPrice2 }}</td>

          <td></td>
        </tr>        
      </tfoot>

      <tboby>
        <tr ng-repeat="driv in park" ng-class="driv.selected ? 'item-selected' : ''">
          <td ng-if="driv.is_park_driver_direct_paid == '1'" ng-click="selectParkDriver(driv, park)">
            {{ driv.surname }} {{driv.firstname}} {{driv.patronymic}}
          </td>
          <td ng-if="driv.is_park_driver_direct_paid != '1'">
            {{ driv.surname }} {{driv.firstname}} {{driv.patronymic}}
          </td>

          <td class="digit"><b>{{ driv.total_without_payback | asPrice2 }}</b></td>
          <td class="digit"><b>{{ driv.payback | asPrice2 }}</b></td>

          <td class="digit"><b>{{ driv.total_payable | asPrice2 }} </b></td>
          <td class="digit"><b>{{ driv.bank_comission | asPrice2 }} </b></td>
          <td class="digit"><b>{{ driv.total_to_pay | asPrice2 }} </b></td>

          <td class="digit" ng-if="driv.group_id != '9' && driv.group_id != '109'"><b>{{ driv.payed_to_driver | asPrice2 }} </b></td>
          <td class="digit" ng-if="driv.group_id == '9' || driv.group_id == '109'"><b>{{  }} </b></td>

          <td class="digit"> 
              <input type="number" ng-model="driv.debt" class="numberInput" ng-change="recalcDriverTotals(driv)">
          </td>

          <td class="digit" ng-if="driv.group_id != '9' && driv.group_id != '109'"><b>{{ driv.residual_to_pay | asPrice2 }} </b></td>
          <td class="digit" ng-if="driv.group_id == '9' || driv.group_id == '109'"><b>{{  }} </b></td>

          <td class="digit"><b>{{ (driv.card_number == "0" ? "" : driv.card_number) }}<br>{{ driv.beneficiar }}</b></td>

          <td class="digit yandex_column"> 
              <input type="number" class="numberInput" ng-model="driv.yandex_asks" ng-change="recalcDriverTotals(driv)">
          </td>

          <!-- <td class="digit yandex_column">{{ driv.yandex_asks | asPrice2 }}</td> -->
          <td class="digit yandex_column">{{ driv.yandex_paid | asPrice2 }}</td>
          <td class="digit yandex_column">{{ driv.yandex_residual | asPrice2 }}</td>

          <td class="digit uber_column">{{ driv.uber_total_netto | asPrice2 }}</td>
          <td class="digit uber_column">{{ driv.uber_correction | asPrice2 }}</td>
          <td class="digit uber_column">{{ driv.uber_sum_cash | asPrice2 }}</td>

          <td class="digit uber_column">{{ driv.uber_total_interest | asPrice2 }} ({{ driv.uber_park_comission * 100 }}%)</td>
          <td class="digit uber_column">{{ driv.uber_total_to_pay | asPrice2 }}</td>

          <td class="digit gett_column">{{ driv.gett_total | asPrice2 }}</td>
          <td class="digit gett_column">{{ driv.gett_total_commission | asPrice2 }}</td>
          <td class="digit gett_column">{{ driv.gett_total_interest | asPrice2 }}</td>
          <td class="digit gett_column">{{ driv.gett_total_cash | asPrice2 }}</td>
          <td class="digit gett_column">{{ driv.gett_correction | asPrice2 }}</td>
          <td class="digit gett_column">{{ driv.gett_total_to_pay | asPrice2 }}</td>

<!--           <td class="digit yandex_column"> 
            <span ng-if="driv.selected">
              <input type="number" ng-model="driv.yandex_non_cash" ng-change="recalcDriverTotals(driv)">
            </span>
            <span ng-if="! driv.selected">
              {{ driv.yandex_non_cash | asPrice2 }}
            </span>
          </td> -->

          <td class="digit wheely_column">{{ driv.wheely_sum_fare | asPrice2 }}</td>
          <td class="digit wheely_column">{{ driv.wheely_sum_comission | asPrice2 }}</td>
          <td class="digit wheely_column">{{ driv.wheely_sum_boost | asPrice2 }}</td>
          <td class="digit wheely_column">{{ driv.wheely_sum_fines | asPrice2 }}</td>
          <td class="digit wheely_column">{{ driv.wheely_interest | asPrice2 }}</td>
          <td class="digit wheely_column">{{ driv.wheely_to_pay | asPrice2 }}</td>

        </tr>
        <tr ng-if="park[0].group_id=='9'">
          <td>Откаты по водителям Рафаэля</td>
          <td></td>
          <td class="digit">{{rafael_payback | asPrice2}}</td>
          <td colspan="6"></td>
        </tr>
      </tboby>
    </table>

  </div>
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
                    <span ng-show="currentDriver.bank_name!=='!!!! Наличными !!!'">
                        Номер карты: {{currentDriver.card_number}}
                    </span>
                </center>    
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <center>
                    <span ng-show="currentDriver.beneficiar.length > 1">
                        Владелец карты: {{currentDriver.beneficiar}}
                    </span>
                </center>    
            </div>
        </div>

        <div class="row">
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Дата оплаты</th>
                        <th>Начислено</th>
                        <th>Оплачено</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td class="digit">
                            {{(totalCharged) | asPriceOrNull}}
                        </td>
                        <td></td>
                    </tr>
                    <tr ng-repeat="payout in payouts" ng-click="selectPayout(payout)" ng-class="payout.selected ? 'item-selected' : ''">
                        <td>{{payout.paid_at}}</td>
                        <td></td>
                        <td class="digit">
                            <span ng-show="! payout.editing">
                                {{payout.amount | asPriceOrNull}}
                                <span class="glyphicon glyphicon-pencil edit-btn" ng-click="updatePayout(payout)">
                                </span>
                                <span class="glyphicon glyphicon-remove-circle cancel-btn" ng-click="deletePayout(payout)">
                                </span>
                            </span>

                            <input ng-keypress="checkPayoutEnter($event, payout)" ng-show="payout.editing" class="numberInput" type="number" ng-model="payout.amount">
                        </td>
                    </tr>

                    <tr class="payouts-total">
                        <td>ИТОГО</td>
                        <td class="digit">
                            {{(totalCharged) | asPriceOrNull}}
                        </td>
                        <td class="digit">{{totalPayouts | asPriceOrNull}}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="row">
            Остаток к оплате : <b>{{ residualToPay | asPriceOrNull }}</b>
        </div>

        <div class="row">
            <flash-message>
                <div class="flash-div">{{ flash.text}}</div>
            </flash-message>
        </div>

        <div class="row">
            <div class="col-md-8">
                <center>
                    <button class="btn btn-primary" ng-click="addPayout()" ng-show="residualToPay > 0 && ! isPayoutEditing && totalCharged > 0">
                        Добавить
                    </button>
                </center>
            </div>
            <div class="col-md-4">
                <center>
                    <button class="btn btn-primary" ng-click="closePayouts()" ng-show="! isPayoutEditing">
                        Закрыть
                    </button>
                </center>
            </div>
        </div>
    </div>
</div>
