var n,notification = {
	settings:{

	},
	init:function(){
		n = this.settings;
		this.bindUiAction();
	},
	bindUiAction:function(){
	},
	showNotification:function(msg, status, position){
		//$.notify("Access granted", "success");
		$.notify.defaults({ className: status });
		$.notify(msg,{ globalPosition:position}, status);		
	}
}

$( document ).ready(function() {
   notification.init();
});