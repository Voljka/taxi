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
  <h3>Фрилансер 7/0 UBER </h3>
  <table class="table table-bordered">
    <thead>
      <tr>
        <td>ФИО</td>
        <td>Доход</td>
        <!-- <td>Нал</td> -->
        <td>Наш Интерес <br>(5%)</td>
        <td>К выдаче</td>
      </tr>
    </thead>
    <tbody ng-if="uber_free7_0.length > 0">
      <tr ng-repeat="el in uber_free7_0">
        <td> 
          {{ el.surname }}  {{el.firstname}} {{el.patronymic}}
        </td>
        <td class="digit"> {{ el.total_netto | asPrice }}</td>
        <td class="digit"> {{ el.total_interest | asPrice }}</td>
        <td class="digit"> {{ el.total_to_pay | asPrice }}</td>
      </tr>
      
    </tbody>
  </table>
</div>

<div class="row">
  <h3>Фрилансер 7/0 GETT </h3>
  <table class="table table-bordered">
    <thead>
      <tr>
        <td>ФИО</td>
        <td>Доход</td>
        <td>Интерес Get<br>(17.7%)</td>
        <td>Наш Интерес <br>(3.3%)</td>
        <td>Нал</td>
        <td>К выдаче</td>
      </tr>
    </thead>
    <tbody ng-if="get_free7_0.length > 0">
      <tr ng-repeat="el in get_free7_0">
        <td> 
          {{ el.surname }}  {{el.firstname}} {{el.patronymic}}
        </td>
          <td class="digit"> {{ el.total | asPrice }}</td>
          <td class="digit"> {{ el.total_commission | asPrice }}</td>
          <td class="digit"> {{ el.total_interest | asPrice }}</td>
          <td class="digit"> {{ el.total_cash | asPrice }}</td>
          <td class="digit"> {{ el.total_to_pay | asPrice }}</td>
      </tr>
      
    </tbody>
  </table>
</div>

<div class="row">
  <h3>Автопарки </h3>


    <table class="table table-condensed table-bordered" ng-repeat="park in parks">
      <thead>
        <tr>
          <td rowspan="2"><b>{{park[0].group_name}}</b></td>
          <td colspan="3">Uber</td>
          <td colspan="5">Gett</td>
          <td width="90px" rowspan="2">Всего,<br>к выдаче</td>
        </tr>
        <tr>
          <td width="80px">Доход</td>
          <!-- <td width="80px">Нам, 10%</td> -->
          <td width="110px">Нам</td>
          <td width="80px">К выдаче</td>
          <td width="80px">Доход</td>
          <td width="80px">Комиссия</td>
          <td width="80px">Нам,3.3%</td>
          <td width="80px">Нал</td>
          <td width="80px">К выдаче</td>
        </tr>
      </thead>
      <tboby>
        <tr ng-repeat="driv in park">
          <td>
            {{ driv.surname }}  {{driv.firstname}} {{driv.patronymic}}
          </td>
          <!-- Uber -->
<!--           <td class="digit"> {{ driv.uber_sum_result ? (driv.uber_sum_result | asPrice) : "0.00" }}</td>
          <td class="digit"> {{ ((driv.uber_sum_result - driv.uber_sum_cash) * 0.1) | asPrice }}</td>
          <td class="digit"> {{ ((driv.uber_sum_result - driv.uber_sum_cash) * 0.9) | asPrice }}</td>
 -->

          <td class="digit"> {{ driv.uber_total_netto | asPrice }}</td>

          <td class="digit"> {{ driv.uber_total_interest | asPrice }} ({{ driv.uber_park_comission * 100 }} %)</td>
          <td class="digit"> {{ driv.uber_total_to_pay | asPrice }}</td>

          <!-- Get -->
<!--           <td class="digit"> {{ driv.get_sum_fare | asPrice }}</td>
          <td class="digit"> {{ ((driv.get_sum_fare) * 0.177) | asPrice }}</td>
          <td class="digit"> {{ ((driv.get_sum_fare) * 0.033) | asPrice }}</td>
          <td class="digit"> {{ driv.get_sum_cash | asPrice }}</td>
          <td class="digit"> {{ ((driv.get_sum_fare) * 0.79 - driv.get_sum_cash) | asPrice }}</td>
 -->
          <td class="digit"> {{ driv.get_total | asPrice }}</td>
          <td class="digit"> {{ driv.get_total_commission | asPrice }}</td>
          <td class="digit"> {{ driv.get_total_interest | asPrice }}</td>
          <td class="digit"> {{ driv.get_total_cash | asPrice }}</td>
          <td class="digit"> {{ driv.get_total_to_pay | asPrice }}</td>

          <!-- Total -->
          <td class="digit"><b> {{ (driv.uber_total_to_pay + driv.get_total_to_pay) | asPrice }}</b></td>
          
        </tr>

      </tboby>
      
    </table>

    
  </div>
</div>