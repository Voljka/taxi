<div class="row">
    <div class="col-md-12">
        <button class="btn btn-primary" ng-disabled="currentFine.editing" ng-click="addRecord()">Добавить</button>
        <button class="btn btn-primary" ng-disabled="currentFine.editing || !currentFine" ng-click="updateRecord()">Изменить</button>
        <button class="btn btn-warning" ng-disabled="currentFine.editing || !currentFine" ng-click="removeRecord()">Удалить</button>
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
                    <td>Дата и время<br>выписки штрафа</td>
                    <td>Гос номер авто</td>
                    <td>Сумма штрафа</td>
                    <td>Наршуение</td>
                    <td>Номер штрафа</td>
                    <td>Дата ввода<br>в программу</td>
                    <td></td>
                </tr>
            </thead>

            <tbody>
                <tr ng-repeat="record in fines" ng-class="record.selected ? 'item-selected' : ''" ng-click="select(record)">
                    <td>
                        <span ng-show="! record.editing">{{record.fined_at}}</span>
                        <span ng-show="record.editing">
                            <!-- <input type="datetime-local" ng-model="record.fined_at"> -->
                            <input type="date" ng-model="record.fine_date">
                            <input type="time" ng-model="record.fine_date">
                        </span>
                    </td>
                    <td>
                        <span ng-show="! record.editing">{{record.state_number}}</span>
                        <span ng-show="record.editing">
                            <select ng-change="changeAuto(record)" ng-model="record.auto_id">   
                                <option ng-repeat="auto in autolist" ng-value="auto.id">{{ auto.state_number }}</option>   
                            </select>                       
                        </span>
                    </td>

                    <td class="digit">
                        <span ng-show="! record.editing">{{record.fine_amount | asPrice}}</span>
                        <span ng-show="record.editing">
                            <input type="number" ng-model="record.fine_amount">
                        </span>

                     </td>
                    <td>
                        <span ng-show="! record.editing">{{record.notes}}</span>
                        <span ng-show="record.editing">
                            <input type="text" width="150px" maxlength="70" ng-model="record.notes">
                        </span>

                     </td>
                    <td>
                        <span ng-show="! record.editing">{{record.fine_number}}</span>
                        <span ng-show="record.editing">
                            <input type="number" ng-model="record.fine_number">
                        </span>
                    </td>
                    <td> {{record.inputed_at}}</td>
                    <td> 
                        <button class="btn btn-primary" ng-show="record.editing" ng-click="saveFine(record)">
                            Сохранить
                        </button> 
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>