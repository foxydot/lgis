jQuery(document).ready(function($) {	
    /*$('*:first-child').addClass('first-child');
    $('*:last-child').addClass('last-child');
    $('*:nth-child(even)').addClass('even');
    $('*:nth-child(odd)').addClass('odd');
	
	/*var numwidgets = $('#footer-widgets div.widget').length;
	$('#footer-widgets').addClass('cols-'+numwidgets);
	$.each(['show', 'hide'], function (i, ev) {
        var el = $.fn[ev];
        $.fn[ev] = function () {
          this.trigger(ev);
          return el.apply(this, arguments);
        };
      });*/
     
     $('.custom-menu-area .sub-menu .menu-item a').each(function(){
         var len = $(this).html().length;
         if(len > 20){
             $(this).css('line-height','1.25');
         }
     });
	
    $('a[href*=#]:not([href=#],.carousel-control)').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      var my_offset;
      if($('#floating_nav').length > 0){
          my_offset = 140;
      } else {
          my_offset = 0;
      }
      console.log(my_offset);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html,body').animate({
          scrollTop: target.offset().top - my_offset
        }, 1000);
        return false;
      }
    }
  });
	//special for lifestyle
	$('.ftr-menu ul.menu>li').after(function(){
		if(!$(this).hasClass('last-child') && $(this).hasClass('menu-item') && $(this).css('display')!='none'){
			return '<li class="separator">|</li>';
		}
	});

});