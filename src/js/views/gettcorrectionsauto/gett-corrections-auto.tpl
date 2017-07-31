<h3>
	Импорт корректировок GETT 
</h3>

<div class="row">

  <form name="form">
    <div 
      class="btn btn-primary col-md-4" 
      ngf-select ng-model="file" 
      name="file" 
      ngf-pattern="'.csv'"
      ngf-accept="'.csv'" 
      ngf-max-size="2MB" 
      ngf-min-height="100"
      ngf-resize="{width: 100, height: 100}"
    >
      Выберите файл
    </div>  

    <div class="col-md-4">
      <button class="btn btn-primary" ng-click="sendFile(this)">Загрузить</button>
    </div>
  </form>
</div>

<div class="row">
  <flash-message>
    <div class="flash-div">{{ flash.text}}</div>
  </flash-message>
</div>  
