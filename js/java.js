function numOnly(event) {
	var key = event.which || event.keyCode;
	return ((key >= 48 && key <= 57) || (key == 8) || (key == 9));
}
function allExecpt(event){
    var key = event.which || event.keyCode;
	return (!(key == 39));
}

function numAndDotOnly(event) {
	var key = event.which || event.keyCode;
	return ((key >= 48 && key <= 57) || (key == 46)|| (key == 8) || (key == 9));
}
$( 'ul.nav.nav-tabs  a' ).click( function ( e ) {
    e.preventDefault();
    $( this ).tab( 'show' );
} );
$('.carousel').carousel({
    interval: 5000 //changes the speed
});

(function($) {
      fakewaffle.responsiveTabs(['xs', 'sm']);
})(jQuery);

$(document).ready(function() {
    $("#datepicker-1").datepicker();
});