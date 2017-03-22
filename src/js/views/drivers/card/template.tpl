<div class="panel panel-info">
	<div class="panel-heading">Каточка клиента</div>	
	<div class="panel panel-body">
		<div class="row">
			<div class="col-md-12">
				<div class="input-group">
					<span class="input-group-addon">Название клиента </span>
					<input class="form-control" type="text" ng-model="name">
				</div>
			</div>
		</div>
		<br>

		<div class="row">
			<div class="col-md-4">
				<div class="input-group">
					<span class="input-group-addon">Район </span>
					<select class="form-control" ng-model="regionList" ng-init="regionList=currentRegion">   
						<option ng-repeat="region in regions" ng-value="region.id">{{ region.name }}</option>	
					</select>
				</div>
			</div>
			<div class="col-md-4">
				<div class="input-group">
					<span class="input-group-addon">Тип ТТ </span>
					<select class="form-control" ng-model="typeList" ng-init="typeList=currentType">   
						<option ng-repeat="type in tttypeList" ng-value="type.id">{{ type.name }}</option>	
					</select>
				</div>
			</div>
			<div class="col-md-4">
				<div class="input-group">
					<span class="input-group-addon">Менеджер </span>
					<select class="form-control" ng-model="managerList" ng-init="managerList=currentManager">   
						<option ng-repeat="manager in workers" ng-value="manager.id">{{ manager.lastname }}</option>	
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="input-group">
					<span class="input-group-addon">Адрес </span>
					<input class="form-control" type=text ng-model="place">
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="input-group">
					<span class="input-group-addon">Представители и тел </span>
					<input class="form-control" type=text ng-model="representatives">
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="input-group">
					<span class="input-group-addon">Примечания </span>
					<input class="form-control" type=text ng-model="notes">
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-5">
				<div class="input-group">
					<span class="input-group-addon">Email </span>
					<input class="form-control" type=text ng-model="mail">
				</div>
			</div>
			<div class="col-md-5">
				<div class="input-group">
					<span class="input-group-addon">Контактное лицо </span>
					<input class="form-control" type=text ng-model="person">
				</div>
			</div>
			<div class="col-md-2">
				<div class="input-group">
					<span class="input-group-addon">VIP </span>
					<input class="form-control" type="checkbox" ng-model="vip">
				</div>
			</div>

		</div>

	</div>

</div>

<div class="panel panel-default">
	<div class="panel-body">
		<center>
			<button class="btn btn-primary" ng-click="save()">{{ submitCaption }}</button> 
			<button class="btn btn-warning" ng-click="backToList()">Отмена</button>
		</center>
	</div>
</div>







<!-- <button class="btn btn-primary" ng-click="save()">{{ submitCaption }}</button> 
<button class="btn btn-warning" ng-click="backToList()">Cancel</button>
 -->
<!-- <button class="btn btn-primary" ng-click="saveCommodity()">{{ submitCaption }}</button> -->
