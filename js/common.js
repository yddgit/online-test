$(document).ready(function() {
	$("form").validationEngine('attach');
});

function formCheck(form) {
	$(form).validationEngine('detach');
	if (!$(form).validationEngine('validate')) {
		$(form).validationEngine('attach');
		return false;
	} else {
		return true;
	}
}

function showConfirm() {
	if(!formCheck($("#testForm"))) {
		return false;
	}
	$('#testFormConfirm').modal({backdrop:'static'});
}
