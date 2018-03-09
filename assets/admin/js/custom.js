var s, Notifications = {
    settings: {
        numArticles: 5,
        sendNotification: $(".send-notification"),
        postInterview: $(".post-interview"),
        subMenu: $(".submenu > a"),
        intrwForm: $('#itrw_form'),
        intrwTab: $('#intrw-tab'),
        intrwList: $('#intrw-list'),
        renderBody: $('.intrw-tbody')
    },
    init: function() {
        // kick things off
        s = this.settings;
        this.bindUIActions();
    },
    bindUIActions: function() {

        $('#profiles').dataTable();

        s.subMenu.on("click", function(e) {
            e.preventDefault();
            Notifications.subMenuBinding($(this));
        });
        // set default dates
        var start = new Date();
        // set end date to max one year period:
        var end = new Date(new Date().setYear(start.getFullYear() + 1));

        $('#intrw_on, #intrw_due').datetimepicker({
            format: 'yyyy-mm-dd hh:ii'
        });

        s.intrwForm.validate({

            submitHandler: function ( form ) {
                
                Notifications.manageNotification( s.postInterview );
                return false;

                /*s.intrwForm.submit(function(e){
                    e.preventDefault();
                    Notifications.manageNotification( s.postInterview );
                });*/
            },
            errorElement: "span",
            rules: {
                intrw_subject: {
                    required: true
                },
                intrw_on: {
                    required: true
                },
                intrw_location: {
                    required: true
                }
            }

        });

        /*List director scheduled interviews*/
        Notifications.renderInterviews();

        s.sendNotification.on("click", function() {
            Notifications.manageNotification( $(this) );
        });

        /*$('.reste-notification, .nav-tabs').on("click", function() {
            s.intrwForm[0].reset();
        });*/

        s.renderBody.on("click", "a.update-intrw", function() {
            Notifications.updateInterview( $(this) );
        });
    },
    subMenuBinding: function($this) {
        var $li = $this.parent("li");
        var $ul = $this.next("ul");
        if ($li.hasClass("open")) {
            $ul.slideUp(350);
            $li.removeClass("open");
        } else {
            $(".nav > li > ul").slideUp(350);
            $(".nav > li").removeClass("open");
            $ul.slideDown(350);
            $li.addClass("open");
        }
    },
    manageNotification: function( $this ) {

        var jData = $this.data(),
            aPost = {},
            aWhere = {},
            aFormData = {};
        var aFilter = ['map_id'];

        if(jData.length != 0) {

            $.each(jData, function(i, item) {
                if (jQuery.inArray(i, aFilter) !== -1) {
                    aWhere[i] = item;
                } else {
                    aPost[i] = item;
                }
            });
            aPost['where'] = aWhere;

            if ($this.data('map_id') == 5) {
                aPost['form_data'] = s.intrwForm.serializeArray();
            }
            $.ajax({
                type: 'POST',
                url: "http://" + location.host + "/notifications",
                data: aPost,
                dataType: 'json',
                success: function(data) {
                    if (data.status) {
                        if (data.action == 'interest') {
                            $('.send-interest').removeClass('btn-info').addClass('btn-default').html('<i class="glyphicon glyphicon-star"></i> Interest Send');
                        } else if (data.action == 'interview') {
                            $('.send-interview').removeClass('btn-info').addClass('btn-default').html('<i class="glyphicon glyphicon-star"></i> interview Scheduled');
                        }
                    }
                }
            });
        } else {
            alert('Request failed !');
        }
    },

    renderInterviews: function (){

        var aWhere = {}, from = s.intrwTab.data('from'), to = s.intrwTab.data('to');
        console.log(from);
        console.log(to);
        /*All interviews of director*/
        if (typeof(from) != 'undefined') {
            aWhere['user_id'] = from;
            aWhere['action'] = 'all';
            aWhere['selector'] = 'intrw-list-body';
            Notifications.getInterviewData( aWhere );
        } 
        /*User specific interview of director*/
        if( typeof(to) != 'undefined' ){

            aWhere['user_id'] = from;
            aWhere['to'] = to;
            aWhere['action'] = 'all';
            aWhere['selector'] = 'intrw-list-user-body';
            Notifications.getInterviewData( aWhere );
        }
    },

    updateInterview: function( $this ){

        var tab = 'new-intrw';
        $('.nav-tabs a[href="#' + tab + '"]').tab('show');

        var aWhere = {}, intrw_id = $this.data('interview');

        if (typeof(intrw_id) != 'undefined') {
            aWhere['id'] = intrw_id;
            aWhere['action'] = 'update';
            aWhere['selector'] = 'intrw-list-body';
        } else {
            alert('No iterview details found!');
        }

        Notifications.getInterviewData( aWhere );
    },

    getInterviewData: function( aWhere ) {

        if(aWhere.length !== 0){

            var action =  aWhere['action'];
            var $selector = aWhere['selector'];
            delete aWhere['action'];
            delete aWhere['selector'];

            // $('#'+$selector).html('<img src="http://celebritybe.local/assets/admin/img/loader.gif" class="ajax-loader">');

            $.ajax({
                type: 'POST',
                url: "http://" + location.host + "/get-interview",
                data: aWhere,
                dataType: 'json',
                success: function(data) {

                    if( data.length != 0 ){

                        if( action == 'all' ){

                            var row = '', i = 1;
                            $.each(data, function(key, val){
                                var status = (val.intrw_status == 1)?'active':'expierde';
                                row += '<tr class="odd gradeX"><td>'+i+'</td>'+
                                '<td>'+val.intrw_subject+'</td>'+
                                '<td>'+val.intrw_description+'</td>'+
                                '<td>'+val.intrw_on+'</td>'+
                                '<td>'+val.intrw_location+'</td>'+
                                '<td>'+val.added_on+'</td>'+
                                '<td>'+status+'</td>'+
                                '<td><a href="#" class="update-intrw" data-interview="'+val.id+'">Update</a></td>'+
                                '</tr>';
                                i++;
                            });
                            if(row != ''){
                                console.log($selector);
                                $('#'+$selector).html(row);
                            }
                        } else if ( action == 'update' ){

                            $.each(data, function(key, val){
                                if(val.length != 0){
                                    $.each(val, function(filed, filed_val){
                                        $('#'+filed).val(filed_val);
                                    });
                                }
                            });
                        }
                    }
                }
            });
        }
    }
};
(function() {
    Notifications.init();
    // SomeOtherModule.init();
})();