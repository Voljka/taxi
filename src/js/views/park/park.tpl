<div class="row">
    <div class="col-md-6">
        <button class="btn btn-primary" ng-disabled="currentAuto.editing" ng-click="addRecord()">Добавить</button>
        <button class="btn btn-primary" ng-disabled="currentAuto.editing || !currentAuto" ng-click="updateRecord()">Изменить</button>
        <button class="btn btn-warning" ng-disabled="currentAuto.editing || !currentAuto" ng-click="removeRecord()">Удалить</button>

    </div>
    <div class="col-md-6">
        <button class="btn btn-warning" ng-disabled="currentAuto.editing || !currentAuto || ! currentAuto.is_rented" ng-click="setRentCost()">Установить стоимость аренды</button>
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
                    <td>Гос номер машины</td>
                    <td>Модель</td>
                    <td>Цвет</td>
                    <td>СТС</td>
                    <td>Лицензия</td>
                    <td>Лицензия<br>заканчивается</td>
                    <td>ОСАГО</td>
                    <td>ОСАГО<br>заканчивается</td>
                    <td>Год<br>выпуска</td>
                    <td>Арендован</td>
                    <td>Аренда за смену</td>
                    <td>Аренда за неделю</td>
                    <td></td>
                </tr>
            </thead>

            <tbody>
                <tr ng-repeat="record in autolist" ng-class="record.selected ? 'item-selected' : ''" ng-click="select(record)">
                    <td> {{$index + 1}} </td>
                    <td>
                        <span ng-show="! record.editing">{{record.state_number}}</span>
                        <span ng-show="record.editing">
                            <input type="text" maxlength="15" ng-model="record.state_number">
                        </span>
                    </td>

                    <td>
                        <span ng-show="! record.editing">{{record.model}}</span>
                        <span ng-show="record.editing">
                            <input type="text" maxlength="30" ng-model="record.model">
                        </span>
                    </td>

                    <td>
                        <span ng-show="! record.editing">{{record.color}}</span>
                        <span ng-show="record.editing">
                            <input type="text" maxlength="30" ng-model="record.color">
                        </span>
                    </td>

                    <td>
                        <span ng-show="! record.editing">{{record.sts}}</span>
                        <span ng-show="record.editing">
                            <input type="text" maxlength="30" ng-model="record.sts">
                        </span>
                    </td>

                    <td>
                        <span ng-show="! record.editing">{{record.license}}</span>
                        <span ng-show="record.editing">
                            <input type="text" maxlength="30" ng-model="record.license">
                        </span>
                    </td>

                    <td>
                        <span ng-show="! record.editing">{{record.license_deadline | formatRu}}</span>
                        <span ng-show="record.editing">
                            <input type="date" ng-model="record.license_deadline_input">
                        </span>
                    </td>

                    <td>
                        <span ng-show="! record.editing">{{record.osago}}</span>
                        <span ng-show="record.editing">
                            <input type="text" maxlength="20" ng-model="record.osago">
                        </span>
                    </td>

                    <td>
                        <span ng-show="! record.editing">{{record.osago_deadline | formatRu}}</span>
                        <span ng-show="record.editing">
                            <input type="date" ng-model="record.osago_deadline_input">
                        </span>
                    </td>

                    <td>
                        <span ng-show="! record.editing">{{record.year_created}}</span>
                        <span ng-show="record.editing">
                            <input type="number" ng-model="record.year_created">
                        </span>
                    </td>

                    <td>
                        <span ng-show="! record.editing">{{record.is_rented ? "Да" : "Нет"}}</span>
                        <span ng-show="record.editing">
                            <input type="checkbox" ng-model="record.is_rented">
                        </span>
                    </td>

                    <td class="digit">
                        <span >{{record.cost_daily | asPrice }}</span>
                    </td>

                    <td class="digit">
                        <span >{{record.cost_weekly | asPrice }}</span>
                    </td>

                    <td> 
                        <button class="btn btn-primary" ng-show="record.editing" ng-click="saveAuto(record)">
                            Сохранить
                        </button> 
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <flash-message>
        <div class="flash-div">{{ flash.text}}</div>
    </flash-message>
</div>

<div class="cover" ng-show="isShowingCosts">
</div>

<div class="cover-modal" ng-if="isShowingCosts" >
    <div id="modal-rental_costs">
        <h3><center>Установка стоимости аренды <center></h3>

        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">Текущая аренда за смену </span>
                    <input class="form-control" type="number" ng-model="obj.lastDaily">
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">Текущая аренда в неделю</span>
                    <input class="form-control" type="number" ng-model="obj.lastWeekly">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">Устанавливаемая аренда за смену </span>
                    <input class="form-control" type="number" ng-model="obj.newDaily">
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">Устанавливаемая аренда в неделю</span>
                    <input class="form-control" type="number" ng-model="obj.newWeekly">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="input-group">
                    <span class="input-group-addon">Дата начала действия стоимостей </span>
                    <input class="form-control" type="date" ng-model="obj.start_date">
                </div>
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
                    <button class="btn btn-primary" ng-click="saveNewCosts()">Сохранить</button>
                    <button class="btn btn-primary" ng-click="closeCosts()">Закрыть</button>
                </center>
            </div>
        </div>
    </div>
</div>
