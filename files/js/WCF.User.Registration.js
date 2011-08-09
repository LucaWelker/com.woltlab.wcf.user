/**
 * Namespace for registration functions.
 */
WCF.User.Registration = function() {}

/**
 * Validates the username.
 */
WCF.User.Registration.ValidateUsername = function(element, options) { this.init(element, options); };
WCF.User.Registration.ValidateUsername.prototype = {
	init: function(element, options) {
		this.element = element;
		this.options = $.extend(true, {
			minlength: 3,
			maxlength: 25
		}, options);
		
		this.element.bind('blur', $.proxy(this._blur, this));
	}, 
	
	_blur: function(event) {
		var username = this.element.val();
		if (!username) {
			return this.showError(WCF.Language.get('wcf.global.error.empty'));
		}
		if (username.length < this.options.minlength || username.length > this.options.maxlength) {
			return this.showError(WCF.Language.get('wcf.user.error.username.notValid'));
		}
		
		this.proxy = new WCF.Action.Proxy({
			autoSend: true,
			success: $.proxy(this._success, this),
			data: {
				actionName: 'validateUsername',
				className: 'wcf\\data\\user\\UserRegistrationAction',
				parameters: {
					username: username
				}
			}
		});
	},
	
	_success: function(data, textStatus, jqXHR) {
		if (data.returnValues.isValid) {
			this.showSuccess();
		}
		else {
			this.showError(WCF.Language.get('wcf.user.error.username.'+data.returnValues.error));
		}
	},
	
	showError: function(message) {
		this.element.parent().prev().addClass('formError').removeClass('formSuccess');
		
		if (this.element.parent().find('small.innerError').length) {
			this.element.parent().find('small.innerError').text(message);
		}
		else {
			var innerError = $('<small></small>');
			innerError.text(message);
			innerError.addClass('innerError');
			this.element.after(innerError);
		}
	},
	
	showSuccess: function() {
		this.element.parent().prev().addClass('formSuccess').removeClass('formError');
		this.element.next('small.innerError').remove();
	}
};

/**
 * Validates the email address.
 */
WCF.User.Registration.ValidateEmailAddress = function(element, confirmElement) { this.init(element, confirmElement); };
WCF.User.Registration.ValidateEmailAddress.prototype = {
	init: function(element, confirmElement) {
		this.element = element;
		this.confirmElement = confirmElement;
		this.element.bind('blur', $.proxy(this._blur, this));
		this.confirmElement.bind('blur', $.proxy(this._blurConfirm, this));
	},
	
	_blur: function(event) {
		var email = this.element.val();
		if (!email) {
			return this.showError(this.element, WCF.Language.get('wcf.global.error.empty'));
		}
		var confirmEmail = this.confirmElement.val();
		if (confirmEmail != '' && email != confirmEmail) {
			return this.showError(this.confirmElement, WCF.Language.get('wcf.user.error.confirmEmail.notEqual'));
		}
			
		this.proxy = new WCF.Action.Proxy({
			autoSend: true,
			success: $.proxy(this._success, this),
			data: {
				actionName: 'validateEmailAddress',
				className: 'wcf\\data\\user\\UserRegistrationAction',
				parameters: {
					email: email
				}
			}
		});
	},
	
	_blurConfirm: function(event) {
		var confirmEmail = this.confirmElement.val();
		if (!confirmEmail) {
			return this.showError(this.confirmElement, WCF.Language.get('wcf.global.error.empty'));
		}
		
		this._blur(event);
	},
	
	_success: function(data, textStatus, jqXHR) {
		if (data.returnValues.isValid) {
			this.showSuccess(this.element);
			if (this.confirmElement.val()) {
				this.showSuccess(this.confirmElement);
			}
		}
		else {
			this.showError(this.element, WCF.Language.get('wcf.user.error.email.'+data.returnValues.error));
		}
	},
	
	showError: function(element, message) {
		element.parent().prev().addClass('formError').removeClass('formSuccess');
		
		if (element.parent().find('small.innerError').length) {
			element.parent().find('small.innerError').text(message);
		}
		else {
			var innerError = $('<small></small>');
			innerError.text(message);
			innerError.addClass('innerError');
			element.after(innerError);
		}
	},
	
	showSuccess: function(element) {
		element.parent().prev().addClass('formSuccess').removeClass('formError');
		element.next('small.innerError').remove();
	}
};

/**
 * Validates the password.
 */
WCF.User.Registration.ValidatePassword = function(element, confirmElement) { this.init(element, confirmElement); };
WCF.User.Registration.ValidatePassword.prototype = {
	init: function(element, confirmElement) {
		this.element = element;
		this.confirmElement = confirmElement;
		this.element.bind('blur', $.proxy(this._blur, this));
		this.confirmElement.bind('blur', $.proxy(this._blurConfirm, this));
	},
	
	_blur: function(event) {
		var password = this.element.val();
		if (!password) {
			return this.showError(this.element, WCF.Language.get('wcf.global.error.empty'));
		}
		var confirmPassword = this.confirmElement.val();
		if (confirmPassword != '' && password != confirmPassword) {
			return this.showError(this.confirmElement, WCF.Language.get('wcf.user.error.confirmPassword.notEqual'));
		}
			
		this.proxy = new WCF.Action.Proxy({
			autoSend: true,
			success: $.proxy(this._success, this),
			data: {
				actionName: 'validatePassword',
				className: 'wcf\\data\\user\\UserRegistrationAction',
				parameters: {
					password: password
				}
			}
		});
	},
	
	_blurConfirm: function(event) {
		var confirmPassword = this.confirmElement.val();
		if (!confirmPassword) {
			return this.showError(this.confirmElement, WCF.Language.get('wcf.global.error.empty'));
		}
		
		this._blur(event);
	},
	
	_success: function(data, textStatus, jqXHR) {
		if (data.returnValues.isValid) {
			this.showSuccess(this.element);
			if (this.confirmElement.val()) {
				this.showSuccess(this.confirmElement);
			}
		}
		else {
			this.showError(this.element, WCF.Language.get('wcf.user.error.password.'+data.returnValues.error));
		}
	},
	
	showError: function(element, message) {
		element.parent().prev().addClass('formError').removeClass('formSuccess');
		
		if (element.parent().find('small.innerError').length) {
			element.parent().find('small.innerError').text(message);
		}
		else {
			var innerError = $('<small></small>');
			innerError.text(message);
			innerError.addClass('innerError');
			element.after(innerError);
		}
	},
	
	showSuccess: function(element) {
		element.parent().prev().addClass('formSuccess').removeClass('formError');
		element.next('small.innerError').remove();
	}
};