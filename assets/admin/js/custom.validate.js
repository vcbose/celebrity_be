$('#registion_form').validate({
    errorElement: "span",
    rules: {
    first_name:{
    	required:true
    },
    password:{
        required:true
    },
    confirm_password:{
        required:true,
        // equalTo:'#password'
    },
    user_name:{
        required:true,
        remote: {
                url:'http://'+location.host+'/checkUserName',
                type: "post",
                data: {
                  username: function() {
                    return $("#user_name").val();
                  }
               }
        }
    },
    dob:{
    	required:true
    },
    gender:{
    	required:true
    },
    state:{
    	required:true
    },
    city:{
    	required:true
    },
    address:{
    	required:true
    },
    mobile_num:{
    	required:true,
    	number:true,
    	maxlength:10
    },
    talent_category:{
    	required:true
    },
    email:{
    	required:true,
    	email:true
    },
    plan:{
       required:true, 
    }
   }
 });