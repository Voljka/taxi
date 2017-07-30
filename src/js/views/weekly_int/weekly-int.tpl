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
  <button class=" btn btn-primary" ng-click="saveFreelancerData()" ng-if="selectedFreelancer">Сохранить расчет F</button>

  <button class=" btn btn-primary" ng-click="showDriverPayouts()" ng-if="selectedFreelancer">Вылпаты водителю</button>

</div>

<div class="row">
  <h3>Фрилансеры 7/0 </h3>
  <table class="table table-bordered freelance-table" id="table-freelancers">
    <thead>
      <tr>
        <td rowspan="2">ФИО</td>
        <td colspan="4" class="uber_column" >Uber</td>
        <td colspan="5" class="gett_column">Gett</td>
        <td colspan="3" class="yandex_column">Yandex</td>
        <td colspan="6" class="wheely_column">Wheely</td>

        <td width="80px" rowspan="2">Всего,<br>начислено</td>
        <td width="80px" rowspan="2">Комиссия<br>банка</td>
        <td width="80px" rowspan="2">Всего,<br>к выдаче</td>
        <td width="80px" rowspan="2">Выплачено<br>водителю</td>
        <td width="80px" rowspan="2">Остаток<br>к оплате</td>
        <td width="150px" rowspan="2">Карта</td>
      </tr>
      <tr>
        <td width="80px" class="uber_column">Доход</td>
        <td width="80px" class="uber_column">Cash</td>
        <td width="80px" class="uber_column">Нам</td>
        <td width="80px" class="uber_column">К выдаче</td>

        <td width="80px" class="gett_column">Доход</td>
        <td width="80px" class="gett_column">Интерес Get<br>(17.7%)</td>
        <td width="80px" class="gett_column">Наш Интерес <br>(3.3%)</td>
        <td width="80px" class="gett_column">Нал</td>
        <td width="80px" class="gett_column">К выдаче</td>

        <td width="80px" class="yandex_column">Запросы</td>
        <td width="80px" class="yandex_column">Оплачено</td>
        <td width="80px" class="yandex_column">К оплате</td>

        <td width="80px" class="wheely_column">Тариф</td>
        <td width="80px" class="wheely_column">Комиссия</td>
        <td width="80px" class="wheely_column">Чаевые и<br>пароковка</td>
        <td width="80px" class="wheely_column">Штрафы</td>
        <td width="80px" class="wheely_column">Нам</td>
        <td width="80px" class="wheely_column">К выдаче</td>
      </tr>    
    </thead>
    <tfoot>
      <tr>
        <td rowspan="2">ФИО</td>
        <td colspan="4" class="uber_column" >Uber</td>
        <td colspan="5" class="gett_column">Gett</td>
        <td colspan="3" class="yandex_column">Yandex</td>
        <td colspan="6" class="wheely_column">Wheely</td>

        <td width="80px" rowspan="2">Всего,<br>начислено</td>
        <td width="80px" rowspan="2">Комиссия<br>банка</td>
        <td width="80px" rowspan="2">Всего,<br>к выдаче</td>
        <td width="80px" rowspan="2">Выплачено<br>водителю</td>
        <td width="80px" rowspan="2">Остаток<br>к оплате</td>
        <td width="150px" rowspan="2">Карта</td>
      </tr>
      <tr>
        <td width="110px" class="uber_column">Доход</td>
        <td width="100px" class="uber_column">Cash</td>
        <td width="80px" class="uber_column">Нам</td>
        <td width="110px" class="uber_column">К выдаче</td>

        <td width="110px" class="gett_column">Доход</td>
        <td width="80px" class="gett_column">Интерес Get<br>(17.7%)</td>
        <td width="80px" class="gett_column">Наш Интерес <br>(3.3%)</td>
        <td width="100px" class="gett_column">Нал</td>
        <td width="110px" class="gett_column">К выдаче</td>

        <td width="80px" class="yandex_column">Запросы</td>
        <td width="80px" class="yandex_column">Оплачено</td>
        <td width="80px" class="yandex_column">К оплате</td>

        <td width="80px" class="wheely_column">Тариф</td>
        <td width="80px" class="wheely_column">Комиссия</td>
        <td width="80px" class="wheely_column">Чаевые и<br>пароковка</td>
        <td width="80px" class="wheely_column">Штрафы</td>
        <td width="80px" class="wheely_column">Нам</td>
        <td width="80px" class="wheely_column">К выдаче</td>        
      </tr>          
      <tr class="total-row">
        <td colspan="21"></td>
        <td class="digit">{{sumBy(free7_0,'total_to_pay') | asPrice }}</td>
        <td></td>
        <td class="digit">{{sumBy(free7_0,'residual_to_pay') | asPrice }}</td>
        <td></td>
      </tr>
    </tfoot>
    <tbody ng-if="free7_0.length > 0">
      <tr ng-repeat="el in free7_0" ng-class="el.selected ? 'item-selected' : ''">
        <td ng-click="selectFreeDriver(el)"> 
          {{ el.surname }}  {{el.firstname}} {{el.patronymic}}
        </td>
        <td class="digit uber_column"> {{ el.uber_total_netto | asPrice }}</td>
        <td class="digit uber_column"> {{ el.uber_sum_cash | asPrice }}</td>
        <td class="digit uber_column"> {{ el.uber_total_interest | asPrice }}</td>
        <td class="digit uber_column"> {{ el.uber_total_to_pay | asPrice }}</td>


        <td class="digit gett_column"> {{ el.gett_total | asPrice }}</td>
        <td class="digit gett_column"> {{ el.gett_total_commission | asPrice }}</td>
        <td class="digit gett_column"> {{ el.gett_total_interest | asPrice }}</td>
        <td class="digit gett_column"> {{ el.gett_total_cash | asPrice }}</td>
        <td class="digit gett_column"> {{ el.gett_total_to_pay | asPrice }}</td>

<!--         <td class="digit yandex_column"> 
          <span ng-if="el.selected">
            <input type="number" ng-model="el.yandex_non_cash" ng-change="recalcDriverTotals(el)">
          </span>
          <span ng-if="! el.selected">
            {{ el.yandex_non_cash | asPrice }}
          </span>
        </td>

        <td class="digit yandex_column"> 
          <span ng-if="el.selected">
            <input type="number" ng-model="el.yandex_cash" ng-change="recalcDriverTotals(el)">
          </span>
          <span ng-if="! el.selected">
            {{ el.yandex_cash | asPrice }}
          </span>
        </td>
 -->
        <td class="digit yandex_column"> {{ el.yandex_asks | asPrice }}</td>
        <td class="digit yandex_column"> {{ el.yandex_paid | asPrice }}</td>
        <td class="digit yandex_column"> {{ el.yandex_residual | asPrice }}</td>

        <td class="digit wheely_column"> {{ el.wheely_sum_fare | asPrice }}</td>
        <td class="digit wheely_column"> {{ el.wheely_sum_comission | asPrice }}</td>
        <td class="digit wheely_column"> {{ el.wheely_sum_boost | asPrice }}</td>
        <td class="digit wheely_column"> {{ el.wheely_sum_fines | asPrice }}</td>
        <td class="digit wheely_column"> {{ el.wheely_interest | asPrice }}</td>
        <td class="digit wheely_column"> {{ el.wheely_to_pay | asPrice }}</td>

        <td class="digit"><b> {{ el.total_payable | asPrice }}</b></td>
        <td class="digit"><b> {{ el.bank_comission | asPrice }}</b></td>
        <td class="digit"><b> {{ el.total_to_pay | asPrice }} </b></td>
        <td class="digit"><b> {{ el.payed_to_driver | asPrice }} </b></td>
        <td class="digit"><b> {{ el.residual_to_pay | asPrice }} </b></td>
        <td class="digit"><b> {{ (el.card_number == "0" ? "" : el.card_number) }} <br> {{ el.beneficiar }} </b></td>

      </tr>
      
    </tbody>
  </table>
</div>

<div class="row">
  <button class=" btn btn-primary" ng-click="saveParkDriverData()" ng-if="selectedParkDriver">Сохранить расчет P</button>
  <button class=" btn btn-primary" ng-click="showDriverPaybacks()" ng-if="selectedParkDriver && withPaybacks">Выплаты откатов</button>
  <button class=" btn btn-primary" ng-click="showParkPayouts()" ng-if="selectedParkDriver">Выплаты парку</button>

</div>


<div class="row">
  <h3>Автопарки </h3>

    

    <table class="table table-condensed table-bordered table-autoparks" id="table{{park[0].group_id}}" ng-repeat="park in parks">
      <thead>
        <tr>
          <td rowspan="2"><b>{{park[0].group_name}}</b>
            <br>
            <button class=" btn btn-primary" ng-click="parkToXLS('table'+park[0].group_id)">Сохранить в XLS</button>            
          </td>
          <td colspan="4" class="uber_column">Uber</td>
          <td colspan="5" class="gett_column">Gett</td>
          <td colspan="3" class="yandex_column">Yandex</td>

          <td width="80px" rowspan="2">Заработок<br>водителя</td>
          <td width="90px" rowspan="2">Откат,<br>2%</td>
          <td width="80px" rowspan="2">Всего,<br>начислено</td>
          <td width="80px" rowspan="2">Комиссия<br>банка</td>
          <td width="80px" rowspan="2">Всего,<br>к выдаче</td>
          <td width="80px" rowspan="2">Выплачено<br></td>
          <td width="80px" rowspan="2">Остаток<br>к оплате</td>

          <td width="150px" rowspan="2">Карта</td>
        </tr>
        <tr>
          <td width="110px" class="uber_column">Доход</td>
          <td width="100px" class="uber_column">Cash</td>
          <td width="110px" class="uber_column">Нам</td>
          <td width="110px" class="uber_column">К выдаче</td>

          <td width="110px" class="gett_column">Доход</td>
          <td width="80px" class="gett_column">Комиссия</td>
          <td width="80px" class="gett_column">Нам,3.3%</td>
          <td width="100px" class="gett_column">Нал</td>
          <td width="110px" class="gett_column">К выдаче</td>

          <td width="80px" class="yandex_column">Запросы</td>
          <td width="80px" class="yandex_column">Оплачено</td>
          <td width="80px" class="yandex_column">К оплате</td>

        </tr>
      </thead>

      <tfoot>

        <tr>
          <td rowspan="2"><b>{{park[0].group_name}}</b></td>
          <td colspan="4" class="uber_column">Uber</td>
          <td colspan="5" class="gett_column">Gett</td>
          <td colspan="3" class="yandex_column">Yandex</td>

          <td width="80px" rowspan="2">Заработок<br>водителя</td>
          <td width="90px" rowspan="2">Откат,<br>2%</td>
          <td width="80px" rowspan="2">Всего,<br>начислено</td>
          <td width="80px" rowspan="2">Комиссия<br>банка</td>
          <td width="80px" rowspan="2">Всего,<br>к выдаче</td>
          <td width="80px" rowspan="2">Выплачено<br></td>
          <td width="80px" rowspan="2">Остаток<br>к оплате</td>

          <td width="150px" rowspan="2">Карта</td>
        </tr>
        <tr>
          <td width="110px" class="uber_column">Доход</td>
          <td width="100px" class="uber_column">Cash</td>
          <td width="110px" class="uber_column">Нам</td>
          <td width="110px" class="uber_column">К выдаче</td>

          <td width="110px" class="gett_column">Доход</td>
          <td width="80px" class="gett_column">Комиссия</td>
          <td width="80px" class="gett_column">Нам,3.3%</td>
          <td width="100px" class="gett_column">Нал</td>
          <td width="110px" class="gett_column">К выдаче</td>

          <td width="80px" class="yandex_column">Запросы</td>
          <td width="80px" class="yandex_column">Оплачено</td>
          <td width="80px" class="yandex_column">К оплате</td>

        </tr>  
        <tr class="total-row">
          <td colspan="13"></td>
          <td class="digit">{{sumBy(park,'total_without_payback') | asPrice }}</td>
          <td></td>
          <td class="digit">{{sumBy(park,'total_payable') | asPrice }}</td>
          <td></td>
          <td class="digit">{{sumBy(park,'total_to_pay') | asPrice }}</td>
          <td></td>
          <td class="digit">{{sumBy(park,'residual_to_pay') | asPrice }}</td>
          <td></td>
        </tr>        
      </tfoot>

      <tboby>
        <tr ng-repeat="driv in park" ng-class="driv.selected ? 'item-selected' : ''">
          <td ng-click="selectParkDriver(driv, park)">
            {{ driv.surname }} {{driv.firstname}} {{driv.patronymic}}
          </td>
          <td class="digit uber_column">{{ driv.uber_total_netto | asPrice }}</td>
          <td class="digit uber_column">{{ driv.uber_sum_cash | asPrice }}</td>

          <td class="digit uber_column">{{ driv.uber_total_interest | asPrice }}<br>({{ driv.uber_park_comission * 100 }} %)</td>
          <td class="digit uber_column">{{ driv.uber_total_to_pay | asPrice }}</td>

          <td class="digit gett_column">{{ driv.gett_total | asPrice }}</td>
          <td class="digit gett_column">{{ driv.gett_total_commission | asPrice }}</td>
          <td class="digit gett_column">{{ driv.gett_total_interest | asPrice }}</td>
          <td class="digit gett_column">{{ driv.gett_total_cash | asPrice }}</td>
          <td class="digit gett_column">{{ driv.gett_total_to_pay | asPrice }}</td>

<!--           <td class="digit yandex_column"> 
            <span ng-if="driv.selected">
              <input type="number" ng-model="driv.yandex_non_cash" ng-change="recalcDriverTotals(driv)">
            </span>
            <span ng-if="! driv.selected">
              {{ driv.yandex_non_cash | asPrice }}
            </span>
          </td>

          <td class="digit yandex_column"> 
            <span ng-if="driv.selected">
              <input type="number" ng-model="driv.yandex_cash" ng-change="recalcDriverTotals(driv)">
            </span>
            <span ng-if="! driv.selected">
              {{ driv.yandex_cash | asPrice }}
            </span>
          </td> -->
          <td class="digit yandex_column">{{ driv.yandex_asks | asPrice }}</td>
          <td class="digit yandex_column">{{ driv.yandex_paid | asPrice }}</td>
          <td class="digit yandex_column">{{ driv.yandex_residual | asPrice }}</td>

          <td class="digit"><b>{{ driv.total_without_payback | asPrice }}</b></td>
          <td class="digit"><b>{{ driv.payback | asPrice }}</b></td>

          <td class="digit"><b>{{ driv.total_payable | asPrice }} </b></td>
          <td class="digit"><b>{{ driv.bank_comission | asPrice }} </b></td>
          <td class="digit"><b>{{ driv.total_to_pay | asPrice }} </b></td>

          <td class="digit"><b>{{ driv.payed_to_driver | asPrice }} </b></td>
          <td class="digit"><b>{{ driv.residual_to_pay | asPrice }} </b></td>

          <td class="digit"><b>{{ (driv.card_number == "0" ? "" : driv.card_number) }}<br>{{ driv.beneficiar }}</b></td>
        </tr>
      </tboby>
    </table>
  </div>
</div>
