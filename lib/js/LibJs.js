$(document).ready(function(){
	$('#goIndex').on('click', function(){
		document.location = 'index';
	});
	$('#login').on('click', function(){
		document.location = 'login';
	});
	$('#logout').on('click', function(){
		$.post('cgi/login/Logout',function(){
			document.location = 'index';
		});
	});
});
