< script > $(document).ready(function () {
	jQuery.validator.addMethod('username_rule', function (value, element) {
		if (/^[a-zA-Z0-9_-]+$/.test(value)) {
			return true;
		} else {
			return false;
		};
	});
	jQuery.validator.addMethod('email_rule', function (value, element) {
		if (/^([a-zA-Z0-9_\-\.]+)\+?([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/.test(value)) {
			return true;
		} else {
			return false;
		};
	});
	$('#top-signup-form').validate({
		rules: {
			'join[email]': {
				required: true,
				email_rule: true
			},
			'firstName': {
				required: true,
			},
			'lastName': {
				required: true,
			},
		},
		messages: {
			'join[email]': "Please enter a valid email address.",
			'firstName': "Please type your first name.",
			'lastName': "Please type your last name."
		}
	});
}); < /script>