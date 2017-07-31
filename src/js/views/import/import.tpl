<h3>
	Импорт данных {{ partner }} 
</h3>

<div class="row">

  <form name="form">
    <div 
      class="btn btn-primary col-md-4" 
      ngf-select ng-model="file" 
      name="file" 
      ngf-pattern="'{{ext}}'"
      ngf-accept="'{{ext}}'" 
      ngf-max-size="2MB" 
      ngf-min-height="100"
      ngf-resize="{width: 100, height: 100}"
      ng-show="partner == 'Gett' || partner == 'Wheely'" 
    >
      Выберите файл
    </div>  

    <div class="col-md-4" ng-show="partner == 'Uber'">
      <p><input ng-model="currentUberReport" type="radio" ng-value="1"> Загрузить текущую ведомость </p>
      <p><input ng-model="currentUberReport" type="radio" ng-value="0" > Предыдущую ведомсть</p>
    </div>

    <div class="col-md-4">
      <button class="btn btn-primary" ng-click="sendFile(this)">Загрузить</button>
    </div>

  </form>

</div>

<div ng-show="isLoading" class="row"> 
  <div class="col-md-12">
    <center>
      <h1>
        Идет загрузка данных с Uber
      </h1>
    </center>  
  </div>

  
</div>

<div class="row">
  <flash-message>
    <div class="flash-div">{{ flash.text}}</div>
  </flash-message>
</div>  
