jQuery(window).on("elementor/frontend/init", function () {

    /*
    * Checks if in the editor, if not stop the rest from executing
    */
    if(!window.elementorFrontend.config.environmentMode.edit) {
        return;
    }

    elementor.channels.editor.on('ekit:editor:pin_click', function (panel) {

        if(panel.$el.hasClass('open')) {
            return false;
        }

        var parent = panel.$el.parents('#elementor-controls'),

            be_username = parent.find("[data-setting='pinterest_user_name']"),
            be_type = parent.find("[data-setting='pinterest_feed_type']"),
            be_board = parent.find("[data-setting='pinterest_board_name']"),

            empty_arr = [
                {"key": "pinterest_user_name", "value": "Please give a valid username."},
                {"key": "pinterest_username", "value": "Please give a valid username."},
                {"key": "pinterest_feed_type", "value": "Please give a valid app secret."},
                {"key": "pinterest_board_name", "value": "Please give a valid board name."}
            ],
            invalid_param = [];

        if(!be_username[0].value) {
            invalid_param.push('pinterest_user_name');
        }

        if(be_type[0].value != 'home') {

            if(!be_board[0].value) {
                invalid_param.push('pinterest_board_name');
            }
        }


        if(invalid_param.length > 0) {

            jQuery.each( empty_arr, function( index , value ){
                if (jQuery.inArray( value.key , invalid_param ) != -1) {
                    panel.$el.find('.elementor-control-input-wrapper').append(
                        '<div class="alert alert-danger" role="alert">'+ value.value +'</div>');
                }
                setTimeout(function(){
                    panel.$el.find('.alert').fadeOut().remove();
                }, 5000)
            });

            return false;
        }


        var form_data = {
            'username': be_username[0].value,
            'type': be_type[0].value,
            'board': be_board[0].value
        };


        jQuery.ajax({
            data: form_data,
            type: 'post',
            url: window.pinterest_config.rest_url + 'elementskit/v1/pinterest/feed/',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', pinterest_config.nonce);

                panel.$el.find('.elementor-control-input-wrapper').append(
                    '<div class="alert alert-info" role="alert">Waiting for server response...</div>');

                panel.$el.addClass('open');
            },
            success: function (data) {

                console.log('wht....', data);

                if(data.success) {

                    console.log('success....fetching data..');

                } else {

                    alert(data.msg);
                }

                panel.$el.removeClass('open');
            },
            error: function (data) {
                console.log('Error...', data);
            },
            complete: function () {
                panel.$el.find('.alert').fadeOut().remove();
            }
        });

    });
});