$( document ).ready(function() {
	$(".autosubmit").change(function() {
	    $(this).closest('form').submit();
	});
});


// DIOVELLO