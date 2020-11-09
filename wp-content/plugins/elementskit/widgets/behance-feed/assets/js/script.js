jQuery(window).on("elementor/frontend/init", function () {

    /*
    * Checks if in the editor, if not stop the rest from executing
    */
    if(!window.elementorFrontend.config.environmentMode.edit) {
        return;
    }

    elementor.channels.editor.on('ekit:editor:be_del_cache_click', function (panel) {

        if(panel.$el.hasClass('open')) {
            return false;
        }

        var parent = panel.$el.parents('#elementor-controls'),

            be_username = parent.find("[data-setting='behance_user_name']"),


            empty_arr = [
                {"key": "behance_user_name", "value": "Please give a valid username."}
            ],
            invalid_param = [];

        if(!be_username[0].value) {
            invalid_param.push('behance_user_name');
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
            'username': be_username[0].value
        };


        jQuery.ajax({
            data: form_data,
            type: 'post',
            url: window.behance_config.rest_url + 'elementskit/v1/behance/del_cache/',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', behance_config.nonce);

                panel.$el.find('.elementor-control-input-wrapper').append(
                    '<div class="alert alert-info" role="alert">Waiting for server response...</div>');

                panel.$el.addClass('open');
            },
            success: function (data) {

                if(data.success) {

                    be_username.val(form_data.username);
                    be_username.trigger('input');

                    alert(data.msg);

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

jQuery(document).ready(function () {

    jQuery(document).on('click', '.load_more_b_feed', function (ev) {
        ev.preventDefault();

        var def_fetch = 5;
        var $this = jQuery(this);

        var elm = $this.closest('div.ekit-feed-items-wrapper').find('.dno');

        elm.each(function (idx, el) {

            if(def_fetch) {

                def_fetch--;

                jQuery(el).removeClass('dno').show();
            }
        });

        if(!elm.length) {
            $this.closest('div').remove();
        }
    });
});