jQuery(document).ready(function($) {
    var numwidgets = $('#homepage-widgets section.widget').length;
    $('#homepage-widgets').addClass('cols-'+numwidgets);
    var cols = 12/numwidgets;
    $('#homepage-widgets section.widget').addClass('col-sm-'+cols);
    $('#homepage-widgets section.widget').addClass('col-xs-12');
    
    new WOW().init();
    
    $('.prsnl-bg').css('top',function(){
        return '-' + $(this).height() + 'px';
    }).css('margin-bottom',function(){
        return '-' + $(this).height() + 'px';
    });
    $('#personal-insurance').css('padding-bottom',function(){
        return $('.prsnl-bg').height() + 'px';
    });
});