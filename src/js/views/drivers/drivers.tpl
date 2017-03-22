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
	</div>
</div>

<div class="row">
	<flash-message>
		<div class="flash-div">{{ flash.text}}</div>
	</flash-message>
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