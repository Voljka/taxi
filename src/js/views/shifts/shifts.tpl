<div class="row">
    <div class="col-md-6">
        <input type="date" ng-model="shiftDate">
    </div>

    <div class="col-md-6">
        <button class="btn btn-primary" ng-click="showShift()">Показать данные смены</button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <button class="btn btn-primary" ng-disabled="currentShift.editing" ng-click="addRecord()">Добавить</button>
        <button class="btn btn-primary" ng-disabled="currentShift.editing || !currentShift" ng-click="updateRecord()">Изменить</button>
        <button class="btn btn-warning" ng-disabled="currentShift.editing || !currentShift" ng-click="removeRecord()">Удалить</button>
    </div>
</div>

<div class="row">
    <flash-message>
        <div class="flash-div">{{ flash.text}}</div>
    </flash-message>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <td>Водитель</td>
                    <td>Модель авто</td>
                    <td>Гос номер авто</td>
                    <td>Группа</td>
                    <td></td>
                </tr>
            </thead>

            <tbody>
                <tr ng-repeat="driver in shifts" ng-class="driver.selected ? 'item-selected' : ''" ng-click="select(driver)">
                    <td>
                        <span ng-show="! driver.editing">
                            {{driver.surname + ' ' + driver.firstname + ' ' + driver.patronymic}}
                        </span>
                        <span ng-show="driver.editing">
                            <select ng-change="changeDriver(driver)" ng-model="driver.driver_id">   
                                <option ng-repeat="sh in cabdrivers" ng-value="sh.id">{{sh.surname + ' ' + sh.firstname + ' ' + sh.patronymic}}</option>   
                            </select>                       
                        </span>

                    </td>
                    <td>{{driver.model}}</td>
                    <td>
                        <span ng-show="! driver.editing">{{driver.state_number}}</span>
                        <span ng-show="driver.editing">
                            <select ng-change="changeAuto(driver)" ng-model="driver.auto_id">   
                                <option ng-repeat="auto in autolist" ng-value="auto.id">{{ auto.state_number }}</option>   
                            </select>                       
                        </span>
                    </td>

                    <td>{{driver.group_name}}</td>
                    <td> 
                        <button class="btn btn-primary" ng-show="driver.editing" ng-click="saveShift(driver)">
                            Сохранить
                        </button> 
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>