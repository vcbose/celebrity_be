var r,register = {
	settings:{
		notify		:'',
		lave_plan	: 0
	},
	init:function(noty){
		r = this.settings;
		r.notify = noty;
		this.bindUiAction();
	},
	bindUiAction:function(){
		//r.notify.showNotification('hellooo','success', 'bottom right');
		$('#registion_form').on('submit', function(e){
			//e.preventDefault();
			if($('#plan').val() == '' && r.lave_plan == 0){
				r.notify.showNotification('Select Plan','error', 'bottom right');
				return false;
			}
			if($("#talent_category :selected").length > 3){
				r.notify.showNotification('Select Only Three Talents','error', 'bottom right');
				return false;
			}else{
				var files = $('#photos')[0].files;
			    if(files.length > $('#image_count').val()){
			        r.notify.showNotification('Only '+$('#image_count').val()+' image you can upload','error', 'bottom right');
			        return false;
			    }else{
					if($('.video_url').length > $('#video_count').val()){
						r.notify.showNotification('Only '+$('#video_count').val()+'  video you can upload','error', 'bottom right');
						return false;
					}else{
						return true;
					}
				}
			}

		});

		$('input[type=radio][name=register_type]').change(function() {

	        if (this.value == 'director') {
	        	
	            $('.talent,.speciality').hide();
	            $("#talent_category option:selected").prop("selected", false);
	        }
	        else if (this.value == 'talent') {
	            $('.talent').show();
	        }
	    });

		$('#talent_category').on('change', function(){

			register.talentCategory();

		});

		$('#add_more').on('click', function(e){
			e.preventDefault();
			if($('#video_count').val() == ''){
				r.notify.showNotification('Select Plan','error', 'bottom right');
				return false;
			}
			if($('.video_url').length > $('#video_count').val()-1){
				r.notify.showNotification('Only '+$('#video_count').val()+'  video you can upload','error', 'bottom right');
				return false;
			}
			$('#video_add').append('<div class="video_url" style="margin-bottom: 10px;"><input type="text" name="videos[]" id="videos" class="form-control" placeholder="https://youtu.be/YicuKTFPxX0"><a href="javascript:void(0);" class="remove" <a href="javascript:void(0);" style="position: absolute;float: left; right: 1%;background: #f51212;color: #fff;font-weight: bold;font-size: 10px;padding: 5px 10px;border-radius: 13px;">X</a ></div>');
			$('.remove').on('click', function(e){
			$(this).closest('.video_url').remove();
			});
		});

		$('#photos').on('change', function(){

			if($('#remain_image_count').val() == ''){
				r.notify.showNotification('Select Plan','error', 'bottom right');
				return false;
			}
			var files = $(this)[0].files;

		    if(files.length > $('#remain_image_count').val()){
		        r.notify.showNotification('Only '+$('#remain_image_count').val()+' image you can upload','error', 'bottom right');
		        $(this).val('');
		        return false;
		    }
		});

		$('#plan').on('change', function(){
			var url = 'http://'+location.host+'/getplandata';
			$.ajax({
				  url: url,
				  type:'POST',
				  dataType:'JSON',
				  data:{'plan_id':$(this).val()},
				  cache: false,
				  success:function(success_obj){
				  	$('#image_count').val(success_obj.Images);
				  	$('#video_count').val(success_obj.Videos);
				  }
				});

		});
	},
	talentCategory:function(){

		var select = [];
		$('#talent_category :selected').each(function(){
 			select[$(this).text()] = $(this).text();
		});

 		if(typeof select['Actor'] != 'undefined' || typeof select['Actress'] != 'undefined' || typeof select['Models'] != 'undefined'){
 			$('.speciality').show();
 		}else{
 			$('.speciality').hide();
 		}

 		if(typeof select['Director'] != 'undefined'){
 			$('#plan_div').hide();
 			$('#image_count, #video_count').val(5);
 			r.lave_plan = 1;
 		}else{
 			$('#plan_div').show();
 			$('#image_count, #video_count').val('');
 			r.lave_plan = 0;
 		}
	},
	manageRegistration:function(){
		//$('speciality').hide();
	}
}

$( document ).ready(function() {
   register.init(notification);
});