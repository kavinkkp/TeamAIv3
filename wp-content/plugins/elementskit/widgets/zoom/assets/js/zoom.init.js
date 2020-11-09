jQuery(window).on("elementor/frontend/init", function () {
    /*
    * Checks if in the editor, if not stop the rest from executing
    */
    if ( !window.elementorFrontend.config.environmentMode.edit ) {
        return;
    }

    elementor.channels.editor.on('ekit:editor:create', function (panel) {

        if(panel.$el.hasClass('open')){ return false; }

        var parent = panel.$el.parents('#elementor-controls'),

            cache_fld = parent.find("[data-setting='meeting_cache']"),
            user_id = parent.find("[data-setting='user_id']").val(),
            start_time = parent.find("[data-setting='start_time']").val(),
            timezone = parent.find("[data-setting='timezone']").val(),
            duration = parent.find("[data-setting='duration']").val(),
            password = parent.find("[data-setting='password']").val(),
            empty_arr = [ 
                {"key":"user_id",    "value" : "Please select meeting hosts."} ,
                {"key":"start_time", "value" : "Please select start time."} ,
                {"key":"password",   "value" : "Password length can't be more than 10."},
                {"key":"timezone",   "value" : "Please select timezone."} 
            ],
            invalid_param = [];     

            if ( !user_id ) {
                invalid_param.push( 'user_id' );
            }
            if ( !start_time ) {
                invalid_param.push( 'start_time' );
            }
            if ( !timezone ) {
                invalid_param.push( 'timezone' );
            }
            if ( password.length > 10 ) {
                invalid_param.push( 'password' );
            }
            if (invalid_param.length > 0) {
                jQuery.each( empty_arr, function( index , value ){
                    if (jQuery.inArray( value.key , invalid_param ) != -1) {
                        panel.$el.find('.elementor-control-input-wrapper').append(
                            '<div class="alert alert-danger" role="alert">'+ value.value +'</div>');
                    }
                    setTimeout(function(){
                        panel.$el.find('.alert').fadeOut().remove();
                    }, 2000)
                })
                return false;
            }

        var form_data = {
            'user_id': user_id,
            'start_time': start_time,
            'timezone': timezone,
            'duration': duration,
            'password': password
        };

        jQuery.ajax({
            data: form_data,
            type: 'post',
            url: window.zoom_js.rest_url + 'elementskit/v1/zoom-meeting/create/',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', zoom_js.nonce);
                panel.$el.addClass('open');
            },
            success: function (response) {
                if(response.success) {
                    cache_fld.val(response.fetched);
                    cache_fld.trigger('input');
                    panel.$el.find('.elementor-control-input-wrapper').append('<div class="alert alert-success" role="alert">'+ response.message +'</div>');
                } else {
                    panel.$el.find('.elementor-control-input-wrapper').append('<div class="alert alert-danger" role="alert">'+ response.message +'</div>');
                }
                panel.$el.removeClass('open');
                setTimeout(function(){
                    panel.$el.find('.alert').fadeOut().remove();
                }, 2000)
            }
        });
    });
});

jQuery(document).ready(function ($) {

    jQuery(document).on('submit', '.ekit-zoom-protected-form', function (ev) {
        ev.preventDefault();
        var self = $( this ),
            form_data = self.serializeArray(),
            password_field = self.find('.ekit-zoom-password-field'),
            post_id = '',
            widget_id = '',
            password = '';

        form_data.forEach(function(item){
            if(item.name == 'zoom-password'){
                password = item.value
            } else if(item.name == 'zoom-post-id'){
                post_id = item.value
            } else if(item.name == 'zoom-widget-id'){
                widget_id = item.value
            }
        });

        $('.error').remove();
        if(!password.trim()){
            password_field.after('<p class="error">Password field can not be empty.</p>');
            return false;
        }

        var form_data = {
            'post_id': post_id,
            'widget_id': widget_id,
            'password': password
        };

        jQuery.ajax({
            data: form_data,
            type: 'post',
            url: window.zoom_js.rest_url + 'elementskit/v1/zoom-meeting/password_verify/',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', zoom_js.nonce);
            },
            success: function (response) {
                if(response.success) {
                    self.append('<div class="attr-alert attr-alert-success" role="alert">'+ response.message +'</div>');
                    self.parents('.ekit-zoom-protected').next().fadeIn().end().hide();
                } else {
                    self.append('<div class="attr-alert attr-alert-danger" role="alert">'+ response.message +'</div>');
                }
                password_field.val('');
                setTimeout(function(){
                    self.find('.attr-alert').remove();
                }, 2000);
            }
        });
    });

});
