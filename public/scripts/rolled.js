(function ($) {
	'use strict';


    $('[ui-jp], [data-ui-jp]').uiJp();
    $('body').uiInclude();
    $('[data-toggle="tooltip"]').tooltip();

    init();
    function init(){
      $('[data-toggle="tooltip"]').tooltip();
    }

    // pjax
    $(document).on('pjaxStart', function() {
        $('#aside').modal('hide');
        $('body').removeClass('modal-open').find('.modal-backdrop').remove();
        $('.navbar-toggleable-sm').collapse('hide');
    });

    if ($.support.pjax) {
      $.pjax.defaults.maxCacheLength = 0;
      var container = $('.pjax-container');
      $(document).on('click', 'a[data-pjax], [data-pjax] a, #aside a, .item a', function(event) {
        if($(".pjax-container").length == 0 || $(this).hasClass('no-ajax')){
          return;
        }
        $.pjax.click(event, {container: container, timeout: 6000, fragment: '.pjax-container'});
      });

      $(document).on('pjax:start', function() {
        $( document ).trigger( "pjaxStart" );
      });
      // fix js
      $(document).on('pjax:end', function(event) {

        $(event.target).find('[ui-jp], [data-ui-jp]').uiJp();
        $(event.target).uiInclude();

        $( document ).trigger( "pjaxEnd" );

        init();
      });
    }

    // blockui
    if ($.blockUI) {
      $(document).on('click', '#subnav .navside a, #subnav .item-title', function() {
          $('#list').block({
            message: '<i class="fa fa-lg fa-refresh fa-spin"></i>' ,
            css: {
              border: 'none',
              backgroundColor: 'transparent',
              color: '#fff',
              padding: '30px',
              width: '100%'
            },
            timeout: 1000
          });
      });

      $(document).on('click', '#list .item-title', function() {
          $('#detail').block({
            message: '<i class="fa fa-lg fa-refresh fa-spin"></i>' ,
            css: {
              border: 'none',
              backgroundColor: 'transparent',
              color: '#fff',
              padding: '30px',
              width: '100%'
            },
            timeout: 1000
          });
      });
    }

    // UI DEVICE
    // Checks for ie
    if ( !!navigator.userAgent.match(/MSIE/i) || !!navigator.userAgent.match(/Trident.*rv:11\./) ){
    	$('body').addClass('ie');
    }

    // Checks for iOs, Android, Blackberry, Opera Mini, and Windows mobile devices
    var ua = window['navigator']['userAgent'] || window['navigator']['vendor'] || window['opera'];
    if( (/iPhone|iPod|iPad|Silk|Android|BlackBerry|Opera Mini|IEMobile/).test(ua) ){
        $('body').addClass('smart');
    }

    // UI FORM
    $(document).on('blur', 'input, textarea', function(e){
		$(this).val() ? $(this).addClass('has-value') : $(this).removeClass('has-value');
    });

    // UI INCLUDE
    var promise = false,
	deferred = $.Deferred();
	$.fn.uiInclude = function(){
		if(!promise){
			promise = deferred.promise();
		}
		//console.log('start: includes');

		compile(this);

		function compile(node){
			node.find('[ui-include], [data-ui-include]').each(function(){
				var that = $(this),
					url  = that.attr('ui-include') || that.attr('data-ui-include');
				promise = promise.then(
					function(){
						//console.log('start: compile '+ url);
						var request = $.ajax({
							url: eval(url),
							method: "GET",
							dataType: "text"
						});
						//console.log('start: loading '+ url);
						var chained = request.then(
							function(text){
								//console.log('done: loading '+ url);
								var ui = that.replaceWithPush( text );
				    			ui.find('[ui-jp], [data-ui-jp]').uiJp();
								ui.find('[ui-include], [data-ui-include]').length && compile(ui);
							}
						);
						return chained;
					}
				);
			});
		}

		deferred.resolve();
		return promise;
	}

	$.fn.replaceWithPush = function(o) {
	    var $o = $(o);
	    this.replaceWith($o);
	    return $o;
    }

    // UI JP
    $.fn.uiJp = function(){

        return this.each(function()
        {
        	var self = $(this);
        	var opts = self.attr('ui-options') || self.attr('data-ui-options');
        	var attr = self.attr('ui-jp') || self.attr('data-ui-jp');
			var options = opts && eval('[' + opts + ']');
			if (options && $.isPlainObject(options[0])) {
				options[0] = $.extend({}, JP_CONFIG[attr], options[0]);
			}

			if(self[attr]){
				self[attr].apply(self, options);
			}else{
				uiLoad.load(MODULE_CONFIG[attr]).then( function(){
					self[attr].apply(self, options);
				});
			}
        });
    }

    // UI LIST
    $(document).on('click', '[data-ui-list] .list-item', function (e) {
        var $this = $(this)
            , $active
            , $ul = $this.closest('[data-ui-list]')
            , $class = $ul.attr('data-ui-list')
            , $target = $ul.attr('data-ui-list-target')
            , $targetClass = $ul.attr('data-ui-list-target-class')
            ;

        $active = $this.siblings('.'+$class.split(' ').join('.'));
        $this.addClass($class);
        $active.removeClass($class);

        $target && $($target).addClass($targetClass);

    });

    // UI MODAL
    $(document).on('click', '[ui-modal], [data-ui-modal]', function (e) {
        var $target = $(this).attr('ui-target') || $(this).attr('data-ui-target') || $(this).attr('data-target');
        $($target).on('hidden.bs.modal', function () {
		  $($target).attr('style','');
		})
    });

    //resize
    $(window).on('resize', function () {
    	var $w = $(window).width()
    	    ,$lg = 1200
    	    ,$md = 991
    	    ,$sm = 768
    	    ;
    	if($w > $lg){
    		$('.aside-lg').modal('hide');
    	}
    	if($w > $md){
    		$('#aside').modal('hide');
    		$('.aside-md, .aside-sm').modal('hide');
    	}
    	if($w > $sm){
    		$('.aside-sm').modal('hide');
    	}
    });

    // UI NAV
    $(document).on('click', '[ui-nav] a, [data-ui-nav] a', function (e) {
        var $this = $(e.target), $active, $li, $lis;
        $this.is('a') || ($this = $this.closest('a'));

        $li = $this.parent();
        $lis = $li.parents('li');

        $active = $li.closest( "nav" ).find('.active');

        $lis.addClass('active');
        ( $this.next().is('ul') && $li.toggleClass('active') ) || $li.addClass('active');

        $active.not($lis).not($li).removeClass('active');

        if($this.attr('href') && $this.attr('href') !=''){
        $(document).trigger('Nav:changed');
        }

        if($('#aside').hasClass('show')){
            $('#aside').modal('toggle');
            $('#aside').removeClass('show');
            $('.modal-backdrop.fade.show').remove();
        }else{
            $('#aside').modal('toggle');
            $('.modal-backdrop.fade.show').remove();
        }
    });

    // init the active class when page reload\
    $('[ui-nav] a, [data-ui-nav] a').filter( function() {
            return location.href.indexOf( $(this).attr('href') ) != -1;
    }).parents('li').addClass( 'active' ).siblings().removeClass('active');

    // UI SCREENFULL
    uiLoad.load('libs/screenfull/dist/screenfull.min.js');
	$(document).on('click', '[ui-fullscreen], [data-ui-fullscreen]', function (e) {
		e.preventDefault();
		if (screenfull.enabled) {
		  screenfull.toggle();
		}
    });

    // UI SCROLL TO
    $.extend( jQuery.easing,{
	    def: 'easeOutQuad',
	    easeInOutExpo: function (x, t, b, c, d) {
	        if (t==0) return b;
	        if (t==d) return b+c;
	        if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
	        return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
	    }
	});

	$(document).on('click', '[ui-scroll-to], [data-ui-scroll-to]', function (e) {
		e.preventDefault();
		var target = $($(this).attr('href')) || $('#'+$(this).attr('data-ui-scroll-to'));
		target && $('html,body').animate({
          scrollTop: target.offset().top
        }, 600, 'easeInOutExpo');
    });

    // UI TABURL
    $.fn.taburl = function(){

		var plugin  = this;

        plugin.each(function()
        {
        	update();
        	$(document).on("Nav:changed", function(){
        		setTimeout(update, 50);
        	});
        	function update(){
        		$('[data-toggle="tab"]').filter( function() {
				   return location.href.indexOf( $(this).attr('data-target') ) != -1;
				}).trigger('click');
        	}
        });

        return plugin;
    }

    // UI TOGGLE CLASS
    $(document).on('click', '[ui-toggle-class], [data-ui-toggle-class]', function (e) {
        e.preventDefault();
        var $self = $(this);
        var attr = $self.attr('data-ui-toggle-class') || $self.attr('ui-toggle-class');
        var target = $self.attr('data-ui-target') || $self.attr('ui-target');
		var classes = ( attr && attr.split(',')) || '',
			targets = (target && target.split(',')) || Array($self),
			key = 0;
		$.each(classes, function( index, value ) {
			var target = $( targets[(targets.length && key)] ),
                current = target.attr('data-ui-class'),
                _class = classes[index];

            (current != _class) && target.removeClass( target.attr('data-ui-class') );
			target.toggleClass(classes[index]);
			target.attr('data-ui-class', _class);
			key ++;
		});
		$self.toggleClass('active');

    });

})(jQuery);
