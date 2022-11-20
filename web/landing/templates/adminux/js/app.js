if (typeof jQuery === "undefined") {
  throw new Error("AdminLTE requires jQuery");
}
$.AdminLTE = {};
$.AdminLTE.options = {
	navbarMenuHeight: "200px",
	animationSpeed: 500,
	sidebarToggleSelector: "[data-toggle='offcanvas']",
	sidebarPushMenu: true,
	enableBoxWidget: true,
	boxWidgetOptions: {
		boxWidgetIcons: {
			collapse: 'fa-minus',
			open: 'fa-plus',
			remove: 'fa-times'
		},
		boxWidgetSelectors: {
			remove: '[data-widget="remove"]',
			collapse: '[data-widget="collapse"]'
		}
	},    
	screenSizes: {
		xs: 480,
		sm: 768,
		md: 992,
		lg: 1200
	}
};
$(function () {
	"use strict";
	$("body").removeClass("hold-transition");
	if (typeof AdminLTEOptions !== "undefined") {
		$.extend(true,
		$.AdminLTE.options,
		AdminLTEOptions);
	}
	var o = $.AdminLTE.options; 
	_init();
	
	$.AdminLTE.layout.activate(); 
	$.AdminLTE.tree('.sidebar'); 
	
	if (o.sidebarPushMenu) {
		$.AdminLTE.pushMenu.activate(o.sidebarToggleSelector);
	}
	
	if (o.enableBoxWidget) {
		$.AdminLTE.boxWidget.activate();
	}
	$('.btn-group[data-toggle="btn-toggle"]').each(function () {
		var group = $(this);
		$(this).find(".btn").on('click', function (e) {
			group.find(".btn.active").removeClass("active");
			$(this).addClass("active");
			e.preventDefault();
		});
	});
});

function _init() {
	'use strict';
	$.AdminLTE.layout = {
		activate: function () {
			var _this = this;
			_this.fix();
			_this.fixSidebar();
			$(window, ".wrapper").resize(function () {
				_this.fix();
				_this.fixSidebar();
			});
		},
		fix: function () {
		/*
			var neg = $('.main-header').outerHeight() + $('.main-footer').outerHeight();
			var window_height = $(window).height();
			var sidebar_height = $(".sidebar").height(); 
			if ($("body").hasClass("fixed")) {
				$(".content-wrapper, .right-side").css('min-height', window_height - $('.main-footer').outerHeight());
			} 
			else 
			{
				var postSetWidth;
				if (window_height >= sidebar_height) {
					$(".content-wrapper").css('min-height', window_height - neg);
					postSetWidth = window_height - neg;
				} else {
					$(".content-wrapper").css('min-height', (sidebar_height + 140 ));
					postSetWidth = sidebar_height;
				}
			}
		*/ 
		},
		fixSidebar: function () {
		/*
		  if (!$("body").hasClass("fixed")) {
			if (typeof $.fn.slimScroll != 'undefined') {
			  $(".sidebar").slimScroll({destroy: true}).height("auto");
			}
			return;
		  } else if (typeof $.fn.slimScroll == 'undefined' && window.console) {
			window.console.error("Error: the fixed layout requires the slimscroll plugin!");
		  }
		  if ($.AdminLTE.options.sidebarSlimScroll) {
			if (typeof $.fn.slimScroll != 'undefined') {
			  $(".sidebar").slimScroll({destroy: true}).height("auto");
			  $(".sidebar").slimscroll({
				height: ($(window).height() - $(".main-header").height()) + "px",
				color: "rgba(0,0,0,0.2)",
				size: "3px"
			  });
			}
		}
		*/ 
		}
  };
 
  $.AdminLTE.pushMenu = {
    activate: function (toggleBtn) {
      var screenSizes = $.AdminLTE.options.screenSizes;

      $(document).on('click', toggleBtn, function (e) {
        e.preventDefault();

        if ($(window).width() > (screenSizes.sm - 1)) {
          if ($("body").hasClass('sidebar-collapse')) {
            $("body").removeClass('sidebar-collapse').trigger('expanded.pushMenu');
          } else {
            $("body").addClass('sidebar-collapse').trigger('collapsed.pushMenu');
          }
        }
        else {
          if ($("body").hasClass('sidebar-open')) {
            $("body").removeClass('sidebar-open').removeClass('sidebar-collapse').trigger('collapsed.pushMenu');
          } else {
            $("body").addClass('sidebar-open').trigger('expanded.pushMenu');
          }
        }
      });

      $(".content-wrapper").click(function () {
        if ($(window).width() <= (screenSizes.sm - 1) && $("body").hasClass("sidebar-open")) {
          $("body").removeClass('sidebar-open');
        }
      }); 
       
    },
     
    collapse: function () {
      if ($('body').hasClass('sidebar-expanded-on-hover')) {
        $('body').removeClass('sidebar-expanded-on-hover').addClass('sidebar-collapse');
      }
    }
  };

	$.AdminLTE.tree = function (menu) {
		var _this = this;
		var animationSpeed = $.AdminLTE.options.animationSpeed;
		$(document).off('click', menu + ' li a').on('click', menu + ' li a', function (e) {
			var $this = $(this);
			var checkElement = $this.next();

			if ((checkElement.is('.treeview-menu')) && (checkElement.is(':visible')) && (!$('body').hasClass('sidebar-collapse'))) {
				checkElement.slideUp(animationSpeed, function () {
					checkElement.removeClass('menu-open');
				});
				checkElement.parent("li").removeClass("active");
			}
			else if ((checkElement.is('.treeview-menu')) && (!checkElement.is(':visible'))) {
				var parent = $this.parents('ul').first();
				var ul = parent.find('ul:visible').slideUp(animationSpeed);
				ul.removeClass('menu-open');
				var parent_li = $this.parent("li");
				checkElement.slideDown(animationSpeed, function () {
					checkElement.addClass('menu-open');
					parent.find('li.active').removeClass('active');
					parent_li.addClass('active');
					_this.layout.fix();
				});
			}
			if (checkElement.is('.treeview-menu')) {
				e.preventDefault();
				}
		});
	}; 

  $.AdminLTE.boxWidget = {
    selectors: $.AdminLTE.options.boxWidgetOptions.boxWidgetSelectors,
    icons: $.AdminLTE.options.boxWidgetOptions.boxWidgetIcons,
    animationSpeed: $.AdminLTE.options.animationSpeed,
    activate: function (_box) {
      var _this = this;
      if (!_box) {
        _box = document;
      }
      $(_box).on('click', _this.selectors.collapse, function (e) {
        e.preventDefault();
        _this.collapse($(this));
      });
      $(_box).on('click', _this.selectors.remove, function (e) {
        e.preventDefault();
        _this.remove($(this));
      });
    },
    collapse: function (element) {
      var _this = this;
      var box = element.parents(".box").first();
      var box_content = box.find("> .box-body, > .box-footer, > form  >.box-body, > form > .box-footer");
      if (!box.hasClass("collapsed-box")) {
        element.children(":first")
          .removeClass(_this.icons.collapse)
          .addClass(_this.icons.open);
        box_content.slideUp(_this.animationSpeed, function () {
          box.addClass("collapsed-box");
        });
      } else {
        element.children(":first")
          .removeClass(_this.icons.open)
          .addClass(_this.icons.collapse);
        box_content.slideDown(_this.animationSpeed, function () {
          box.removeClass("collapsed-box");
        });
      }
    },
    remove: function (element) {
      var box = element.parents(".box").first();
      box.slideUp(this.animationSpeed);
    }
  };
}
/*
(function ($, window, document, undefined) {
    var pluginName = "metisMenu", defaults = { toggle: true };
    var _this = this; 
    var animationSpeed = $.AdminLTE.options.animationSpeed;    
    function Plugin(element, options) {
        this.element = element;
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    } 
    Plugin.prototype = {
        init: function () {
            var $this = $(this.element), $toggle = this.settings.toggle;
            $this.find('li.active').has('ul').children('ul').addClass('active');
            $this.find('li').not('.active').has('ul').children('ul').addClass('treeview-menu');
            $this.find('li').has('ul').children('a').on('click', function (e) {
                e.preventDefault();
                $(this).parent('li').toggleClass('active').children('ul').addClass('menu-open');
                if ($toggle) {
                    $(this).parent('li').siblings().removeClass('menu-open').children('ul').collapse('hide');
                }
                $.AdminLTE.layout.fix();
            });
            
        }
    };
    $.fn[ pluginName ] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new Plugin(this, options));
            }
        });
    };
})(jQuery, window, document);
*/
$(function() {
  //  $('#side-menu').metisMenu();
    
    var url = window.location;
    var element = $('ul.sidebar-menu a').filter(function() {
		return this.href == url || url.href.indexOf(this.href) == 0;
	}).addClass('active').parent().parent().addClass('ini').parent();
	if (element.is('li')) { element.addClass('active'); }
	
	$('#alert-close').on('click', function(){
		$('.message-info').hide();
	});
	/*
	$('#DataTable_processing').html(''+
	'<div class="progress">'+
		'<div class="progress-bar progress-bar-warning progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">'+
			'<span class="sr-onlys text-primary">Loading ...</span>'+
		'</div>'+
	'</div>');
	*/ 
});
