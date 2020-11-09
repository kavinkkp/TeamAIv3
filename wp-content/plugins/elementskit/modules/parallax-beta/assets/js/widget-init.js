(function ($, elementor) {
    "use strict";
    

	var ElementskitLite = {
		init: function () {
			elementor.hooks.addAction('frontend/element_ready/global', function($scope){
				new EkitStickyHandler({ $element: $scope });
			});
		}
	};
	$(window).on('elementor/frontend/init', ElementskitLite.init);

	var ElementsKitModule = elementorModules.frontend.handlers.Base;

	var EkitStickyHandler = ElementsKitModule.extend({

		isTrue: function isTrue(key, val){
            if(this.getElementSettings(key) != false && this.getElementSettings(key) == val){
				return true;
			}
			return false;
		},

		shouldRun: function shouldRun(val){
            if(this.isTrue('ekit_we_effect_on', val)){
				return true;
			}

			return false;
		},

		active: function active() {
            if(this.shouldRun('mousemove')){
                // this.mousemove();
            }
            if(this.shouldRun('onscroll')){
                // this.onscroll();
            }
		},

		deactivate: function deactivate(forceUnbind) {
            // if(forceUnbind || !this.getElementSettings('ekit_we_effect_on') || this.getElementSettings('ekit_we_effect_on') != 'tilt' || this.isTrue('ekit_we_on_test_mode', 'yes')){
            //     this.$element.find('.elementor-widget-container').tilt().tilt.destroy.call(this.$element.find('.elementor-widget-container'));
            // }
            // if(forceUnbind || !this.getElementSettings('ekit_we_effect_on') || this.getElementSettings('ekit_we_effect_on') != 'mousemove' || this.isTrue('ekit_we_on_test_mode', 'yes')){
            //     this.$element.parents('.elementor-section').first().off('mousemove.elementskitwidgethovereffect');
            // }
            // if(forceUnbind || !this.getElementSettings('ekit_we_effect_on') || this.getElementSettings('ekit_we_effect_on') != 'onscroll' || this.isTrue('ekit_we_on_test_mode', 'yes')){
            //     $(window).off('scroll.magicianscrolleffect' + this.getID());
            // }
		},

		onElementChange: function onElementChange(settingKey) {
            // if(settingKey.includes('ekit_we_')){
            //     if(settingKey.includes('_on')){
            //         this.deactivate(false);
            //     }
            //     if(settingKey.includes('we_scroll_')){
            //         this.deactivate(true);
            //     }
            //     this.active();
			// }
			
			console.log(this.getElementSettings('ekit_we_scrolleffect_rotate_speed'));
		},

		onInit: function onInit() {
			ElementsKitModule.prototype.onInit.apply(this, arguments);
			// this.active();
		},

		onDestroy: function onDestroy() {
			ElementsKitModule.prototype.onDestroy.apply(this, arguments);
			// this.deactivate(true);
        },
        

		// animation

        mousemove: function mousemove(){
            var content = this.$element.find('.elementor-widget-container');
            var container = this.$element.parents('.elementor-section').first();
			var speed = this.getElementSettings('ekit_we_mousemove_parallax_speed');
            container.on('mousemove.elementskitwidgethovereffect', function (e) {
				var relX = e.pageX - container.offset().left;
				var relY = e.pageY - container.offset().top;

				TweenMax.to(content, 1, {
					x: (relX - container.width() / 2)  / container.width() * (speed),
					y: (relY - container.height() / 2) / container.height() * (speed),
					ease: Power2.ease
				});
            });
		},
		
		onscroll: function onscroll(){
			var content = this.$element.find('.elementor-widget-container');

			content.magician({
				type: 'scroll',
				uniqueKey: this.getID(),
				offsetTop: parseInt(this.getElementSettings('ekit_we_scroll_offsettop')),
				offsetBottom: parseInt(this.getElementSettings('ekit_we_scroll_offsetbottom')),
				duration: parseInt(this.getElementSettings('ekit_we_scroll_smoothness')),
				animation: {
					[this.getElementSettings('ekit_we_scroll_animation')]: this.getElementSettings('ekit_we_scroll_animation_value')
				}
			});
		}
	});
}(jQuery, window.elementorFrontend));