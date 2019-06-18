jQuery(document).ready(function($){
    $('#menu-icon').click(function(){
        jQuery(this).toggleClass('open');
        jQuery('#mySidenav').toggleClass('sidebarNav');
    });
});