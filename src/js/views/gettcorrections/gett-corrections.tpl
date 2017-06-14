<div class="row">
    <div class="col-md-6">
        <button class="btn btn-primary" ng-disabled="currentCorrection.editing" ng-click="addRecord()">Добавить</button>
        <button class="btn btn-primary" ng-disabled="currentCorrection.editing || !currentCorrection" ng-click="updateRecord()">Изменить</button>
        <button class="btn btn-warning" ng-disabled="currentCorrection.editing || !currentCorrection" ng-click="removeRecord()">Удалить</button>

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
                    <td>#</td>
                    <td>ФИО</td>
                    <td>ID поездки</td>
                    <td>Размер<br>корректировки</td>
                    <td>Примечания</td>
                    <td>Добавлено<br>в базу</td>
                    <td></td>
                </tr>
            </thead>

            <tbody>
                <tr ng-repeat="record in corrections" ng-class="record.selected ? 'item-selected' : ''">
                    <td ng-click="select(record)"> {{$index + 1}} </td>
                    <td>
                        <span ng-show="! record.editing">
                            {{record.surname + ' ' + record.firstname + ' ' + record.patronymic}}
                        </span>
                        <span ng-show="record.editing">
                            <input class="form-control" maxlength="30" type="text" ng-model="filters.surnameLocal" ng-change="useLocalFilter()" placeholder="Фильтр по фамилии">

                            <select ng-change="changeDriver(record)" ng-model="record.driver_id">   
                                <option ng-repeat="sh in drivers" ng-value="sh.id">{{sh.surname + ' ' + sh.firstname + ' ' + sh.patronymic}}</option>   
                            </select>                       
                        </span>
                    </td>
                    <td>
                        <span ng-show="! record.editing">{{record.trip_id}}</span>
                        <span ng-show="record.editing">
                            <input type="text" maxlength="30" ng-model="record.trip_id">
                        </span>
                    </td>

                    <td class="digit">
                        <span ng-show="! record.editing">{{record.amount | asPrice}}</span>
                        <span ng-show="record.editing">
                            <input type="number" ng-model="record.amount">
                        </span>
                    </td>

                    <td>
                        <span ng-show="! record.editing">{{record.notes}}</span>
                        <span ng-show="record.editing">
                            <input type="text" maxlength="100" ng-model="record.notes">
                        </span>
                    </td>

                    <td>
                        <span>{{record.recognized_at}}</span>
                    </td>


                    <td> 
                        <button class="btn btn-primary" ng-show="record.editing && drivers.length > 0" ng-click="saveCorrection(record)">
                            Сохранить
                        </button> 
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>