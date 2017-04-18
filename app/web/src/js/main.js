
$( document ).ready(function() {
	$(".autosubmit").change(function() {
	    $(this).closest('form').submit();
	});
	
	
	$('a[data-confirm]').click(function(ev) {
		var href = $(this).attr('href');
		var title = $(this).attr('data-confirm');
		if(confirm(title)){
			 window.location.href = href;
			 return false;
		}
		return false;
	});
	
	
});