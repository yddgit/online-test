$(document).ready(function() {
    //TODO something
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