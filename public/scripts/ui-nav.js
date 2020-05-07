jQuery(document).ready(function($) {
    var alterClass = function() {
        var ww = document.body.clientWidth;
        if (ww < 700) {
            $('.side-nav-close').addClass('nav-closer');
        } else if (ww >= 701) {
            $('.side-nav-close').removeClass('nav-closer');
        };
    };
    $(window).resize(function(){
        alterClass();
    });
    //Fire it when the page first loads:
    alterClass();
});


(function ($) {
  "use strict";

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

  });

  $(document).on('click', '.nav-closer', function(e){
    if($('#aside').hasClass('show')){
        console.log('action');
        $('#aside').modal('toggle');
        $('#aside').removeClass('show');
        $('.modal-backdrop.fade.show').remove();
    }else{
        $('#aside').modal('toggle');
        $('.modal-backdrop.fade.show').remove();
    }
  })

  // init the active class when page reload\
  $('[ui-nav] a, [data-ui-nav] a').filter( function() {
        return location.href.indexOf( $(this).attr('href') ) != -1;
  }).parents('li').addClass( 'active' ).siblings().removeClass('active');

})(jQuery);
