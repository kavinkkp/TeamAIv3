jQuery(document).ready(function ($) {
    "use strict";

    function EkitAdminonHashChange() {
        var hash = window.location.hash;
        if (hash) {
            // using ES6 template string syntax
            $(`${hash}-tab`).trigger('click');
        }
    }

    EkitAdminonHashChange();

    // admin dashboard accordion
    $('.ekit-admin-single-accordion').on('click', '.ekit-admin-single-accordion--heading', function () {
        $(this).next().slideToggle()
            .parent().toggleClass('active').siblings().removeClass('active').find('.ekit-admin-single-accordion--body').slideUp();
    });
    $('.ekit-admin-single-accordion:first-child .ekit-admin-single-accordion--heading').trigger('click');

    // video popup
    $('.ekit-admin-video-tutorial-item').on('click', 'a', function (e) {
        var video_id = $(this).data('video_id');
        if (video_id) {
            e.preventDefault();
            $('.ekti-admin-video-tutorial-popup').toggleClass('show').find('.ekti-admin-video-tutorial-iframe').html('<iframe width="700" height="400" src="https://www.youtube.com/embed/' + video_id + '?autoplay=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
        }
    })
    $('.ekti-admin-video-tutorial-close').on('click', function () {
        $(this).parents('.ekti-admin-video-tutorial-popup').removeClass('show').find('.ekti-admin-video-tutorial-iframe').html('');
    });

    // adding class prev and next elements 
    $('.ekit-admin-nav-link').on('click', function (e) {
        if (!$(this).hasClass('ekit-admin-nav-hidden')) {
            $(this).parents('.attr-nav-tabs').find('a').removeClass('top').removeClass('bottom');
            $(this).parents('li').prev().find('a').addClass('top');
            $(this).parents('li').next().find('a').addClass('bottom');
        } else {
            e.preventDefault();
        }
    });


    if ($('.ekit-admin-section-header').length > 0) {
        var stickyOffset = $('.ekit-admin-section-header').offset().top;
        $(window).scroll(function () {
            var sticky = $('.ekit-admin-section-header'),
                scroll = $(window).scrollTop();

            if (scroll >= stickyOffset) {
                sticky.addClass('fixed').css({
                    'width': jQuery('#v-elementskit-tabContent').width()
                });
            } else {
                sticky.removeClass('fixed').css(
                    {
                        'width': 'auto'
                    }
                );
            }
            ;
        });
    }


    $('#ekit-admin-settings-form').on('submit', function (e) {
        var form = $(this);
        var btn = form.find('.ekit-admin-settings-form-submit');
        var formdata = form.serialize();
        form.addClass('is-loading');
        btn.attr("disabled", true);
        btn.find('.ekit-admin-save-icon').hide();

        $.post(ajaxurl + '?action=ekit_admin_action', formdata, function (data) {
            console.log(data);
            form.removeClass('is-loading');
            btn.removeAttr("disabled");
            btn.find('.ekit-admin-save-icon').fadeIn();
            show_header_footer_menu();
        });


        e.preventDefault();
    });


    $('#ekit-admin-license-form').on('submit', function (e) {
        var form = $(this);
        var btn = form.find('.ekit-admin-license-form-submit');
        var formdata = form.serialize();
        var result = form.find('.elementskit-license-form-result .attr-alert');
        form.addClass('is-loading');
        btn.find('.ekit-admin-save-icon').hide();
        // btn.attr("disabled", true);

        $.post(ajaxurl + '?action=ekit_admin_license', formdata, function (data) {
            form.removeClass('is-loading');
            btn.removeAttr("disabled");
            btn.find('.ekit-admin-save-icon').fadeIn();

            result
                .attr('class', 'attr-alert attr-alert-' + data.status)
                .html(data.message);
            if (data.validate == 1) {
                setTimeout((function () {
                    window.location.reload();
                }), 2000);
            }
        }, 'json');

        e.preventDefault();
    });

    // only for header footer module
    function show_header_footer_menu() {
        var checked = $('#ekit-admin-switch__module__list____header-footer').prop('checked');
        var menu_html = $('#elementskit-template-admin-menu').html();
        var menu_parent = $('#toplevel_page_elementskit .wp-submenu');
        var menu_item = menu_parent.find('a[href="edit.php?post_type=elementskit_template"]');

        if (checked == true) {
            if (menu_item.length > 0 || menu_parent.attr('item-added') == 'y') {
                menu_item.parent().show();
            } else {
                menu_parent.find('li.wp-first-item').after(menu_html);
                menu_parent.attr('item-added', 'y');
            }
        } else {
            menu_item.parent().hide();
        }
    };
    // zoom check connection
    $('.ekit-zoom-connection').on('click', function (e) {
        e.preventDefault();
        jQuery.ajax({
            data: {},
            type: 'post',
            url: window.rest_config.rest_url + 'elementskit/v1/zoom-meeting/hosts/',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', window.rest_config.nonce);
            },
            success: function (response) {
                alert(response.message);
            }
        });
    });

    /**
     * Remove cache and regenerate cache from api
     * and store it and also show the latest feed.
     */
    $('#ekit_instagram_refresh_feed_btn').click(function (event) {
        event.preventDefault();
        var endpoint = elementskit.resturl + 'widget/instagram-feed/refresh_feed';
        var username = $('#ekit-admin-option-textuser__data__instragram____username__').val();

        $.get("https://www.instagram.com/" + username + "/?__a=1", function (content) {
            console.log(content);
            $.ajax({
                type: 'POST',
                url: endpoint,
                data: {
                    content: content,
                },
                success: function (data) {
                    alert('Instagram data refreshed');
                },
                error: function (data) {
                    alert('Instagram data refreshed');
                }
            })
        });



    });


}); // end ready function