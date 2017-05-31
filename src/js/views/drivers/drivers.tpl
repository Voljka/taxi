<!-- <h1>
	Drivers in Template
</h1>
 -->
<div class="row">
	<div class="col-md-6">
		<input ng-model="textFilter" type="text" ng-change="useFilter()" placeholder="Фильтр">
		<br>
		<button class="btn btn-info" ng-click="add()">Добавить водителя</button>
		<button class="btn btn-info" ng-if="currentDriver" ng-click="edit()">Изменить водителя</button>
		<button class="btn btn-info" ng-if="currentDriver" ng-click="calcRetirement()">Расчет при увольнении</button>
	</div>
</div>

<div class="driver-div">
	<table class="table table-bordered">
		<thead>
			<tr>
				<td>Фамилия</td>
				<td>Имя</td>
				<td>Отчество</td>
				<td width="110px">Группа</td>
<!-- 				<td>Email</td> -->
				<td width="120px">Телефоны</td>
				<td>Номер карты</td>
				<td>Актив</td>
				<td>Аренда</td>
			</tr>
		</thead>
		<tbody>
			<tr ng-class="driver.selected ? 'item-selected' : ''" ng-repeat="driver in filteredObjects" ng-click="select(driver)">
				<td> {{ driver.surname }}</td>
				<td> {{ driver.firstname }}</td>
				<td> {{ driver.patronymic }}</td>
				<td> {{ driver.type_name }}</td>
<!-- 				<td> {{ driver.email }}</td> -->
				<td> 
					<span ng-if="driver.phone != 0">
						{{ driver.phone | ruPhone }}
					</span>
					<span ng-if="driver.phone2 != 0">
						<br>{{ driver.phone2 | ruPhone }}
					</span>
				</td>
				<td> {{ driver.card_number !=0 ? driver.card_number : "Cash" }}</td>
				<td> {{ driver.active == 1 ? "Да" : "Нет"  }}</td>
				<td> {{ driver.rent == 1 ? "Да" : "Нет"  }}</td>
			</tr>
			
		</tbody>
	</table>
</div>

<div class="cover" ng-show="isShowingRetirement">
</div>

<div class="cover-modal" ng-if="isShowingRetirement" >
    <div id="modal-retirement">

        <div class="row">
            <div class="col-md-12">
                <center>
                    Расчет при увольнении
                </center>    
            </div>
        </div>
        <div class="row">
            <div class="col md-12">
                <center>
                    {{currentDriver.surname + ' ' + currentDriver.firstname + ' ' + currentDriver.patronymic }}
                </center>
            </div>
        </div>

        <div class="row">
            <div class="col md-12">
                <center>
                	<table class="table table-bordered">
                		<thead>
                			<tr>
                				<th>Статьи</th>
                				<th class="digit">Начислено</th>
                				<th class="digit">Оплачено</th>
                				<th class="digit">Остаток</th>
                			</tr>
                		</thead>
                		<tfoot>
                			<tr>
                				<th>ИТОГО</th>
                				<th class="digit">{{rd.total_charged | asPrice}}</th>
                				<th class="digit">{{rd.total_paid | asPrice}}</th>
                				<th class="digit">{{rd.total_balance | asPrice}}</th>
                			</tr>
                		</tfoot>
                		<tbody>
                			<tr>
                				<td>Франшиза</td>
                				<td class="digit">{{}}</td>
                				<td class="digit">{{rd.franchise_paid | asPrice}}</td>
                				<td class="digit">{{rd.franchise_balance | asPrice}}</td>
                			</tr>

                			<tr>
                				<td>Долги</td>
                				<td class="digit">{{rd.debt_charged | asPrice}}</td>
                				<td class="digit">{{rd.debt_paid | asPrice}}</td>
                				<td class="digit">{{rd.debt_balance | asPrice}}</td>
                			</tr>

                			<tr>
                				<td>Штрафы</td>
                				<td class="digit">{{rd.fine_charged | asPrice}}</td>
                				<td class="digit">{{rd.fine_paid | asPrice}}</td>
                				<td class="digit">{{rd.fine_balance | asPrice}}</td>
                			</tr>
                		</tbody>
                	</table>
                </center>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <center>
                    <button class="btn btn-primary" ng-click="closeRetirement()">
                        Закрыть
                    </button>
                </center>
            </div>
        </div>
    </div>
</div>