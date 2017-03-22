<div class="panel panel-info">
	<div class="panel-heading">Карточка водителя</div>	
	<div class="panel panel-body">
		<div class="row">
			<div class="col-md-4">
				<div class="input-group">
					<span class="input-group-addon">Фамилия </span>
					<input class="form-control" maxlength="30" type="text" ng-model="surname">
				</div>
			</div>
			<div class="col-md-4">
				<div class="input-group">
					<span class="input-group-addon">Имя </span>
					<input class="form-control" maxlength="30" type="text" ng-model="firstname">
				</div>
			</div>
			<div class="col-md-4">
				<div class="input-group">
					<span class="input-group-addon">Отчество </span>
					<input class="form-control" maxlength="30" type="text" ng-model="patronymic">
				</div>
			</div>
		</div>
		<br>

		<div class="row">
			<div class="col-md-4">
				<div class="input-group">
					<span class="input-group-addon">Email </span>
					<input class="form-control" maxlength="50" type="text" ng-model="mail">
				</div>
			</div>
			<div class="col-md-4">
				<div class="input-group">
					<span class="input-group-addon">Телефон </span>
					<span class="input-group-addon">8 </span>
					<input class="form-control" ui-br-phone-number type="text" ng-model="phone">
				</div>
			</div>
			<div class="col-md-4">
				<div class="input-group">
					<span class="input-group-addon">Телефон 2</span>
					<span class="input-group-addon">8 </span>
					<input class="form-control" ui-br-phone-number type="text" ng-model="phone2">
				</div>
			</div>
		</div>
		<br>

		<div class="row">
			<div class="col-md-4">
				<div class="input-group">
					<span class="input-group-addon">Номер карты </span>
					<input class="form-control" ng-disabled="isCash" type="text" ng-model="cardNumber" ng-model-options="{allowInvalid:true}" ui-credit-card>
				</div>
			</div>
			<div class="col-md-4">
				<div class="input-group">
					<span class="input-group-addon">Банк </span>
					<select ng-change="changeBank()" class="form-control" ng-model="driverBank" ng-init="driverBank=currentBank">   
						<option ng-repeat="bank in banks" ng-value="bank.id">{{ bank.name }}</option>	
					</select>
				</div>
			</div>
			<div class="col-md-4">
				<div class="input-group">
					<span class="input-group-addon">Другой владелец карты </span>
					<input class="form-control" ng-disabled="isCash" type="text" ng-model="beneficiar">
				</div>
			</div>
		</div>
		<br>

		<div class="row">
			<div class="col-md-4">
				<div class="input-group">
					<span class="input-group-addon">Группа </span>
					<select class="form-control" ng-model="driverGroup" ng-init="driverGroup=currentGroup">   
						<option ng-repeat="group in groups" ng-value="group.id">{{ group.name }}</option>	
					</select>
				</div>
			</div>
			<div class="col-md-4">
				<div class="input-group">
					<span class="input-group-addon">Дата выхода </span>
					<input class="form-control" type="date" ng-model="firstDay">
				</div>
			</div>
			<div class="col-md-2">
				<div class="input-group">
					<span class="input-group-addon">Активный </span>
					<input class="form-control" type="checkbox" ng-model="active">
				</div>
			</div>
			<div class="col-md-2">
				<div class="input-group">
					<span class="input-group-addon">Аренда </span>
					<input class="form-control" type="checkbox" ng-model="rent">
				</div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-12">
				<div class="input-group">
					<span class="input-group-addon">Примечания </span>
					<input class="form-control" type="text" ng-model="notes">
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<flash-message>
		<div class="flash-div">{{ flash.text}}</div>
	</flash-message>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<center>
			<button class="btn btn-primary" ng-click="save()">Сохранить</button> 
			<button class="btn btn-warning" ng-click="backToList()">Отмена</button>
		</center>
	</div>
</div>