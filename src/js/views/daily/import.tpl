<h3>
	Import {{ partner }} data in Template
</h3>

<form name="form">
  	<div 
  		class="btn btn-primary" 
  		ngf-select ng-model="file" 
  		name="file" 
  		ngf-pattern="'.csv'"
    	ngf-accept="'.csv'" 
    	ngf-max-size="2MB" 
    	ngf-min-height="100"
    	ngf-resize="{width: 100, height: 100}"
    >
    	Select
    </div> 	
	<button ng-click="sendFile(this)">Upload</button>
</form>

<div class="row">
	<flash-message>
		<div class="flash-div">{{ flash.text}}</div>
	</flash-message>
</div>