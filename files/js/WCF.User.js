/**
 * User-related classes.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2013 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */

/**
 * Quick login box
 * 
 * @param	boolean		isQuickLogin
 */
WCF.User.Login = Class.extend({
	/**
	 * dialog overlay
	 * @var	jQuery
	 */
	_dialog: null,
	
	/**
	 * login button
	 * @var	jQuery
	 */
	_loginSubmitButton: null,
	
	/**
	 * password input
	 * @var	jQuery
	 */
	_password: null,
	
	/**
	 * password input container
	 * @var	jQuery
	 */
	_passwordContainer: null,
	
	/**
	 * cookie input
	 * @var	jQuery
	 */
	_useCookies: null,
	
	/**
	 * cookie input container
	 * @var	jQuery
	 */
	_useCookiesContainer: null,
	
	/**
	 * Initializes the quick login box
	 * 
	 * @param	boolean		isQuickLogin
	 */
	init: function(isQuickLogin) {
		this._loginSubmitButton = $('#loginSubmitButton');
		this._password = $('#password'),
		this._passwordContainer = this._password.parents('dl');
		this._useCookies = $('#useCookies');
		this._useCookiesContainer = this._useCookies.parents('dl');
		
		var $loginForm = $('#loginForm');
		$loginForm.find('input[name=action]').change($.proxy(this._change, this));
		
		if (isQuickLogin) {
			var self = this;
			$('.loginLink').click(function() {
				if (self._dialog === null) {
					self._dialog = $('#loginForm').wcfDialog({
						title: WCF.Language.get('wcf.user.login')
					});
				}
				else {
					self._dialog.wcfDialog('open');
				}
				
				return false;
			});
		}
	},
	
	/**
	 * Handle toggle between login and register.
	 * 
	 * @param	object		event
	 */
	_change: function(event) {
		if ($(event.currentTarget).val() === 'register') {
			this._setState(false, WCF.Language.get('wcf.user.button.register'));
		}
		else {
			this._setState(true, WCF.Language.get('wcf.user.button.login'));
		}
	},
	
	/**
	 * Sets form states.
	 * 
	 * @param	boolean		enable
	 * @param	string		buttonTitle
	 */
	_setState: function(enable, buttonTitle) {
		if (enable) {
			this._password.enable();
			this._passwordContainer.removeClass('disabled');
			this._useCookies.enable();
			this._useCookiesContainer.removeClass('disabled');
		}
		else {
			this._password.disable();
			this._passwordContainer.addClass('disabled');
			this._useCookies.disable();
			this._useCookiesContainer.addClass('disabled');
		}
		
		this._loginSubmitButton.val(buttonTitle);
	}
});

/**
 * UserProfile namespace
 */
WCF.User.Profile = {};

/**
 * Shows the activity point list for users.
 */
WCF.User.Profile.ActivityPointList = {
	/**
	 * list of cached templates
	 * @var	object
	 */
	_cache: { },
	
	/**
	 * dialog overlay
	 * @var	jQuery
	 */
	_dialog: null,
	
	/**
	 * initialization state
	 * @var	boolean
	 */
	_didInit: false,
	
	/**
	 * action proxy
	 * @var	WCF.Action.Proxy
	 */
	_proxy: null,
	
	/**
	 * Initializes the WCF.User.Profile.ActivityPointList class.
	 */
	init: function() {
		if (this._didInit) {
			return;
		}
		
		this._cache = { };
		this._dialog = null;
		this._proxy = new WCF.Action.Proxy({
			success: $.proxy(this._success, this)
		});
		
		this._init();
		
		WCF.DOMNodeInsertedHandler.addCallback('WCF.User.Profile.ActivityPointList', $.proxy(this._init, this));
		
		this._didInit = true;
	},
	
	/**
	 * Initializes display for activity points.
	 */
	_init: function() {
		$('.activityPointsDisplay').removeClass('activityPointsDisplay').click($.proxy(this._click, this));
	},
	
	/**
	 * Shows or loads the activity point for selected user.
	 * 
	 * @param	object		event
	 */
	_click: function(event) {
		var $userID = $(event.currentTarget).data('userID');
		
		if (this._cache[$userID] === undefined) {
			this._proxy.setOption('data', {
				actionName: 'getDetailedActivityPointList',
				className: 'wcf\\data\\user\\UserProfileAction',
				objectIDs: [ $userID ]
			});
			this._proxy.sendRequest();
		}
		else {
			this._show($userID);
		}
	},
	
	/**
	 * Displays activity points for given user.
	 * 
	 * @param	integer		userID
	 */
	_show: function(userID) {
		if (this._dialog === null) {
			this._dialog = $('<div>' + this._cache[userID] + '</div>').hide().appendTo(document.body);
			this._dialog.wcfDialog({
				title: WCF.Language.get('wcf.user.activityPoint')
			});
		}
		else {
			this._dialog.html(this._cache[userID]);
			this._dialog.wcfDialog('open');
		}
	},
	
	/**
	 * Handles successful AJAX requests.
	 * 
	 * @param	object		data
	 * @param	string		textStatus
	 * @param	jQuery		jqXHR
	 */
	_success: function(data, textStatus, jqXHR) {
		this._cache[data.returnValues.userID] = data.returnValues.template;
		this._show(data.returnValues.userID);
	}
};

/**
 * Provides methods to follow an user.
 *
 * @param	integer		userID
 * @param	boolean		following
 */
WCF.User.Profile.Follow = Class.extend({
	/**
	 * follow button
	 * @var	jQuery
	 */
	_button: null,
	
	/**
	 * true if following current user
	 * @var	boolean
	 */
	_following: false,
	
	/**
	 * action proxy object
	 * @var	WCF.Action.Proxy
	 */
	_proxy: null,
	
	/**
	 * user id
	 * @var	integer
	 */
	_userID: 0,
	
	/**
	 * Creates a new follow object.
	 * 
	 * @param	integer		userID
	 * @param	boolean		following
	 */
	init: function (userID, following) {
		this._following = following;
		this._userID = userID;
		this._proxy = new WCF.Action.Proxy({
			success: $.proxy(this._success, this)
		});
		
		this._createButton();
		this._showButton();
	},
	
	/**
	 * Creates the (un-)follow button
	 */
	_createButton: function () {
		this._button = $('<li id="followUser"><a class="button">'+WCF.Language.get('wcf.user.button.follow')+'</a></li>').prependTo($('#profileButtonContainer'));
		this._button.click($.proxy(this._execute, this));
	},
	
	/**
	 * Follows or unfollows an user.
	 */
	_execute: function () {
		var $actionName = (this._following) ? 'unfollow' : 'follow';
		this._proxy.setOption('data', {
			'actionName': $actionName,
			'className': 'wcf\\data\\user\\follow\\UserFollowAction',
			'parameters': {
				data: {
					userID: this._userID
				}
			}
		});
		this._proxy.sendRequest();
	},
	
	/**
	 * Displays current follow state.
	 */
	_showButton: function () {
		var $label = WCF.Language.get('wcf.user.button.follow');
		if (this._following) {
			$label = WCF.Language.get('wcf.user.button.unfollow');
		}
		
		// update label
		this._button.find('.button').text($label);
		
		if (this._following) this._button.find('.button').addClass('active');
		else this._button.find('.button').removeClass('active');
	},
	
	/**
	 * Update object state on success.
	 * 
	 * @param	object		data
	 * @param	string		textStatus
	 * @param	jQuery		jqXHR
	 */
	_success: function (data, textStatus, jqXHR) {
		this._following = data.returnValues.following;
		this._showButton();
	}
});

/**
 * Provides methods to manage ignored users.
 * 
 * @param	integer		userID
 * @param	boolean		isIgnoredUser
 */
WCF.User.Profile.IgnoreUser = Class.extend({
	/**
	 * ignore button
	 * @var	jQuery
	 */
	_button: null,
	
	/**
	 * ignore state
	 * @var	boolean
	 */
	_isIgnoredUser: false,
	
	/**
	 * ajax proxy object
	 * @var	WCF.Action.Proxy
	 */
	_proxy: null,
	
	/**
	 * target user id
	 * @var	integer
	 */
	_userID: 0,
	
	/**
	 * Initializes methods to manage an ignored user.
	 * 
	 * @param	integer		userID
	 * @param	boolean		isIgnoredUser
	 */
	init: function(userID, isIgnoredUser) {
		this._userID = userID;
		this._isIgnoredUser = isIgnoredUser;
		
		// initialize proxy
		this._proxy = new WCF.Action.Proxy({
			success: $.proxy(this._success, this)
		});
		
		// handle button
		this._updateButton();
		this._button.click($.proxy(this._click, this));
	},
	
	/**
	 * Handle clicks, might cause 'ignore' or 'unignore' to be triggered.
	 */
	_click: function() {
		var $action = (this._isIgnoredUser) ? 'unignore' : 'ignore';
		
		this._proxy.setOption('data', {
			actionName: $action,
			className: 'wcf\\data\\user\\ignore\\UserIgnoreAction',
			parameters: {
				data: {
					ignoreUserID: this._userID
				}
			}
		});
		
		this._proxy.sendRequest();
	},
	
	/**
	 * Updates button label and function upon successful request.
	 * 
	 * @param	object		data
	 * @param	string		textStatus
	 * @param	jQuery		jqXHR
	 */
	_success: function(data, textStatus, jqXHR) {
		this._isIgnoredUser = data.returnValues.isIgnoredUser;
		this._updateButton();
	},
	
	/**
	 * Updates button label and inserts it if not exists.
	 */
	_updateButton: function() {
		if (this._button === null) {
			this._button = $('<li id="ignoreUser"><a class="button"></a></li>').prependTo($('#profileButtonContainer'));
		}
		
		this._button.find('.button').text(WCF.Language.get('wcf.user.button.' + (this._isIgnoredUser ? 'un' : '') + 'ignore'));
		if (this._isIgnoredUser) this._button.find('.button').addClass('active');
		else this._button.find('.button').removeClass('active');
	}
});

/**
 * Provides methods to load tab menu content upon request.
 */
WCF.User.Profile.TabMenu = Class.extend({
	/**
	 * list of containers
	 * @var	object
	 */
	_hasContent: { },
	
	/**
	 * profile content
	 * @var	jQuery
	 */
	_profileContent: null,
	
	/**
	 * action proxy
	 * @var	WCF.Action.Proxy
	 */
	_proxy: null,
	
	/**
	 * target user id
	 * @var	integer
	 */
	_userID: 0,
	
	/**
	 * Initializes the tab menu loader.
	 * 
	 * @param	integer		userID
	 */
	init: function(userID) {
		this._profileContent = $('#profileContent');
		this._userID = userID;
		
		var $activeMenuItem = this._profileContent.data('active');
		var $enableProxy = false;
		
		// fetch content state
		this._profileContent.find('div.tabMenuContent').each($.proxy(function(index, container) {
			var $containerID = $(container).wcfIdentify();
			
			if ($activeMenuItem === $containerID) {
				this._hasContent[$containerID] = true;
			}
			else {
				this._hasContent[$containerID] = false;
				$enableProxy = true;
			}
		}, this));
		
		// enable loader if at least one container is empty
		if ($enableProxy) {
			this._proxy = new WCF.Action.Proxy({
				success: $.proxy(this._success, this)
			});
			
			this._profileContent.bind('wcftabsselect', $.proxy(this._loadContent, this));
		}
	},
	
	/**
	 * Prepares to load content once tabs are being switched.
	 * 
	 * @param	object		event
	 * @param	object		ui
	 */
	_loadContent: function(event, ui) {
		var $panel = $(ui.panel);
		var $containerID = $panel.attr('id');
		
		if (!this._hasContent[$containerID]) {
			this._proxy.setOption('data', {
				actionName: 'getContent',
				className: 'wcf\\data\\user\\profile\\menu\\item\\UserProfileMenuItemAction',
				parameters: {
					data: {
						containerID: $containerID,
						menuItem: $panel.data('menuItem'),
						userID: this._userID
					}
				}
			});
			this._proxy.sendRequest();
		}
	},
	
	/**
	 * Shows previously requested content.
	 * 
	 * @param	object		data
	 * @param	string		textStatus
	 * @param	jQuery		jqXHR
	 */
	_success: function(data, textStatus, jqXHR) {
		var $containerID = data.returnValues.containerID;
		this._hasContent[$containerID] = true;
		
		// insert content
		var $content = this._profileContent.find('#' + $containerID);
		$('<div>' + data.returnValues.template + '</div>').hide().appendTo($content);
		
		// slide in content
		$content.children('div').wcfBlindIn();
	}
});

/**
 * User profile inline editor.
 * 
 * @param	integer		userID
 * @param	boolean		editOnInit
 */
WCF.User.Profile.Editor = Class.extend({
	/**
	 * current action
	 * @var	string
	 */
	_actionName: '',
	
	/**
	 * list of interface buttons
	 * @var	object
	 */
	_buttons: { },
	
	/**
	 * cached tab content
	 * @var	string
	 */
	_cachedTemplate: '',
	
	/**
	 * action proxy
	 * @var	WCF.Action.Proxy
	 */
	_proxy: null,
	
	/**
	 * tab object
	 * @var	jQuery
	 */
	_tab: null,
	
	/**
	 * target user id
	 * @var	integer
	 */
	_userID: 0,
	
	/**
	 * Initializes the WCF.User.Profile.Editor object.
	 * 
	 * @param	integer		userID
	 * @param	boolean		editOnInit
	 */
	init: function(userID, editOnInit) {
		this._actionName = '';
		this._cachedTemplate = '';
		this._tab = $('#about');
		this._userID = userID;
		this._proxy = new WCF.Action.Proxy({
			success: $.proxy(this._success, this)
		});
		
		this._initButtons();
		
		// begin editing on page load
		if (editOnInit) {
			this._beginEdit();
		}
	},
	
	/**
	 * Initializes interface buttons.
	 */
	_initButtons: function() {
		var $buttonContainer = $('#profileButtonContainer');
		
		// create buttons
		this._buttons = {
			beginEdit: $('<li><a class="button"><span class="icon icon24 icon-pencil" /> <span>' + WCF.Language.get('wcf.user.editProfile') + '</span></a></li>').click($.proxy(this._beginEdit, this)).appendTo($buttonContainer)
		};
	},
	
	/**
	 * Begins editing.
	 */
	_beginEdit: function() {
		this._actionName = 'beginEdit';
		this._buttons.beginEdit.hide();
		$('#profileContent').wcfTabs('select', 'about');
		
		// load form
		this._proxy.setOption('data', {
			actionName: 'beginEdit',
			className: 'wcf\\data\\user\\UserProfileAction',
			objectIDs: [ this._userID ]
		});
		this._proxy.sendRequest();
	},
	
	/**
	 * Saves input values.
	 */
	_save: function() {
		this._actionName = 'save';
		
		// collect values
		var $regExp = /values\[([a-zA-Z0-9._-]+)\]/;
		var $values = { };
		this._tab.find('input, textarea').each(function(index, element) {
			var $element = $(element);
			
			if ($element.getTagName() === 'input') {
				var $type = $element.attr('type');
				
				if (($type === 'radio' || $type === 'checkbox') && !$element.prop('checked')) {
					return;
				}
			}
			
			var $name = $element.attr('name');
			if ($regExp.test($name)) {
				$values[RegExp.$1] = $element.val();
			}
		});
		
		this._proxy.setOption('data', {
			actionName: 'save',
			className: 'wcf\\data\\user\\UserProfileAction',
			objectIDs: [ this._userID ],
			parameters: {
				values: $values
			}
		});
		this._proxy.sendRequest();
	},
	
	/**
	 * Restores back to default view.
	 */
	_restore: function() {
		this._actionName = 'restore';
		this._buttons.beginEdit.show();
		
		this._destroyCKEditor();
		
		this._tab.html(this._cachedTemplate).children().css({ height: 'auto' });
	},
	
	/**
	 * Handles successful AJAX requests.
	 * 
	 * @param	object		data
	 * @param	string		textStatus
	 * @param	jQuery		jqXHR
	 */
	_success: function(data, textStatus, jqXHR) {
		switch (this._actionName) {
			case 'beginEdit':
				this._prepareEdit(data);
			break;
			
			case 'save':
				// save was successful, show parsed template
				if (data.returnValues.success) {
					this._cachedTemplate = data.returnValues.template;
					this._restore();
				}
				else {
					this._prepareEdit(data, true);
				}
			break;
		}
	},
	
	/**
	 * Prepares editing mode.
	 * 
	 * @param	object		data
	 * @param	boolean		disableCache
	 */
	_prepareEdit: function(data, disableCache) {
		this._destroyCKEditor();
		
		// update template
		var self = this;
		this._tab.html(function(index, oldHTML) {
			if (disableCache !== true) {
				self._cachedTemplate = oldHTML;
			}
			
			return data.returnValues.template;
		});
		
		// bind event listener
		this._tab.find('.formSubmit > button[data-type=save]').click($.proxy(this._save, this));
		this._tab.find('.formSubmit > button[data-type=restore]').click($.proxy(this._restore, this));
		this._tab.find('input').keyup(function(event) {
			if (event.which === 13) { // Enter
				self._save();
				
				event.preventDefault();
				return false;
			}
		});
	},
	
	/**
	 * Destroys all CKEditor instances within current tab.
	 */
	_destroyCKEditor: function() {
		// destroy all CKEditor instances
		this._tab.find('textarea + .cke').each(function(index, container) {
			var $instanceName = $(container).attr('id').replace(/cke_/, '');
			if (CKEDITOR.instances[$instanceName]) {
				CKEDITOR.instances[$instanceName].destroy();
			}
		});
	}
});


/**
 * Namespace for registration functions.
 */
WCF.User.Registration = {};

/**
 * Validates the password.
 * 
 * @param	jQuery		element
 * @param	jQuery		confirmElement
 * @param	object		options
 */
WCF.User.Registration.Validation = Class.extend({
	/**
	 * action name
	 * @var	string
	 */
	_actionName: '',
	
	/**
	 * class name
	 * @var	string
	 */
	_className: '',
	
	/**
	 * confirmation input element
	 * @var	jQuery
	 */
	_confirmElement: null,
	
	/**
	 * input element
	 * @var	jQuery
	 */
	_element: null,
	
	/**
	 * list of error messages
	 * @var	object
	 */
	_errorMessages: { },
	
	/**
	 * list of additional options
	 * @var	object
	 */
	_options: { },
	
	/**
	 * AJAX proxy
	 * @var	WCF.Action.Proxy
	 */
	_proxy: null,
	
	/**
	 * Initializes the validation.
	 * 
	 * @param	jQuery		element
	 * @param	jQuery		confirmElement
	 * @param	object		options
	 */
	init: function(element, confirmElement, options) {
		this._element = element;
		this._element.blur($.proxy(this._blur, this));
		this._confirmElement = confirmElement || null;
		
		if (this._confirmElement !== null) {
			this._confirmElement.blur($.proxy(this._blurConfirm, this));
		}
		
		options = options || { };
		this._setOptions(options);
		
		this._proxy = new WCF.Action.Proxy({
			success: $.proxy(this._success, this),
			showLoadingOverlay: false
		});
		
		this._setErrorMessages();
	},
	
	/**
	 * Sets additional options
	 */
	_setOptions: function(options) { },
	
	/**
	 * Sets error messages.
	 */
	_setErrorMessages: function() {
		this._errorMessages = {
			ajaxError: '',
			notEqual: ''
		};
	},
	
	/**
	 * Validates once focus on input is lost.
	 * 
	 * @param	object		event
	 */
	_blur: function(event) {
		var $value = this._element.val();
		if (!$value) {
			return this._showError(this._element, WCF.Language.get('wcf.global.form.error.empty'));
		}
		
		if (this._confirmElement !== null) {
			var $confirmValue = this._confirmElement.val();
			if ($confirmValue != '' && $value != $confirmValue) {
				return this._showError(this._confirmElement, this._errorMessages.notEqual);
			}
		}
		
		if (!this._validateOptions()) {
			return;
		}
		
		this._proxy.setOption('data', {
			actionName: this._actionName,
			className: this._className,
			parameters: this._getParameters()
		});
		this._proxy.sendRequest();
	},
	
	/**
	 * Returns a list of parameters.
	 * 
	 * @return	object
	 */
	_getParameters: function() {
		return { };
	},
	
	/**
	 * Validates input by options.
	 * 
	 * @return	boolean
	 */
	_validateOptions: function() {
		return true;
	},
	
	/**
	 * Validates value once confirmation input focus is lost.
	 * 
	 * @param	object		event
	 */
	_blurConfirm: function(event) {
		var $value = this._confirmElement.val();
		if (!$value) {
			return this._showError(this._confirmElement, WCF.Language.get('wcf.global.form.error.empty'));
		}
		
		this._blur(event);
	},
	
	/**
	 * Handles AJAX responses.
	 * 
	 * @param	object		data
	 * @param	string		textStatus
	 * @param	jQuery		jqXHR
	 */
	_success: function(data, textStatus, jqXHR) {
		if (data.returnValues.isValid) {
			this._showSuccess(this._element);
			if (this._confirmElement !== null && this._confirmElement.val()) {
				this._showSuccess(this._confirmElement);
			}
		}
		else {
			this._showError(this._element, WCF.Language.get(this._errorMessages.ajaxError + data.returnValues.error));
		}
	},
	
	/**
	 * Shows an error message.
	 * 
	 * @param	jQuery		element
	 * @param	string		message
	 */
	_showError: function(element, message) {
		element.parent().parent().addClass('formError').removeClass('formSuccess');
		
		var $innerError = element.parent().find('small.innerError');
		if (!$innerError.length) {
			$innerError = $('<small />').addClass('innerError').insertAfter(element);
		}
		
		$innerError.text(message);
	},
	
	/**
	 * Displays a success message.
	 * 
	 * @param	jQuery		element
	 */
	_showSuccess: function(element) {
		element.parent().parent().addClass('formSuccess').removeClass('formError');
		element.next('small.innerError').remove();
	}
});

/**
 * Username validation for registration.
 *
 * @see	WCF.User.Registration.Validation
 */
WCF.User.Registration.Validation.Username = WCF.User.Registration.Validation.extend({
	/**
	 * @see	WCF.User.Registration.Validation._actionName
	 */
	_actionName: 'validateUsername',
	
	/**
	 * @see	WCF.User.Registration.Validation._className
	 */
	_className: 'wcf\\data\\user\\UserRegistrationAction',
	
	/**
	 * @see	WCF.User.Registration.Validation._setOptions()
	 */
	_setOptions: function(options) {
		this._options = $.extend(true, {
			minlength: 3,
			maxlength: 25
		}, options);
	},
	
	/**
	 * @see	WCF.User.Registration.Validation._setErrorMessages()
	 */
	_setErrorMessages: function() {
		this._errorMessages = {
			ajaxError: 'wcf.user.username.error.'
		};
	},
	
	/**
	 * @see	WCF.User.Registration.Validation._validateOptions()
	 */
	_validateOptions: function() {
		var $value = this._element.val();
		if ($value.length < this._options.minlength || $value.length > this._options.maxlength) {
			this._showError(this._element, WCF.Language.get('wcf.user.username.error.notValid'));
			return false;
		}
		
		return true;
	},
	
	/**
	 * @see	WCF.User.Registration.Validation._getParameters()
	 */
	_getParameters: function() {
		return {
			username: this._element.val()
		};
	}
});

/**
 * Email validation for registration.
 * 
 * @see	WCF.User.Registration.Validation
 */
WCF.User.Registration.Validation.EmailAddress = WCF.User.Registration.Validation.extend({
	/**
	 * @see	WCF.User.Registration.Validation._actionName
	 */
	_actionName: 'validateEmailAddress',
	
	/**
	 * @see	WCF.User.Registration.Validation._className
	 */
	_className: 'wcf\\data\\user\\UserRegistrationAction',
	
	/**
	 * @see	WCF.User.Registration.Validation._getParameters()
	 */
	_getParameters: function() {
		return {
			email: this._element.val()
		};
	},
	
	/**
	 * @see	WCF.User.Registration.Validation._setErrorMessages()
	 */
	_setErrorMessages: function() {
		this._errorMessages = {
			ajaxError: 'wcf.user.email.error.',
			notEqual: WCF.Language.get('wcf.user.confirmEmail.error.notEqual')
		};
	}
});

/**
 * Password validation for registration.
 * 
 * @see	WCF.User.Registration.Validation
 */
WCF.User.Registration.Validation.Password = WCF.User.Registration.Validation.extend({
	/**
	 * @see	WCF.User.Registration.Validation._actionName
	 */
	_actionName: 'validatePassword',
	
	/**
	 * @see	WCF.User.Registration.Validation._className
	 */
	_className: 'wcf\\data\\user\\UserRegistrationAction',
	
	/**
	 * @see	WCF.User.Registration.Validation._getParameters()
	 */
	_getParameters: function() {
		return {
			password: this._element.val()
		};
	},
	
	/**
	 * @see	WCF.User.Registration.Validation._setErrorMessages()
	 */
	_setErrorMessages: function() {
		this._errorMessages = {
			ajaxError: 'wcf.user.password.error.',
			notEqual: WCF.Language.get('wcf.user.confirmPassword.error.notEqual')
		};
	}
});

/**
 * Toggles input fields for lost password form.
 */
WCF.User.Registration.LostPassword = Class.extend({
	/**
	 * email input
	 * @var	jQuery
	 */
	_email: null,
	
	/**
	 * username input
	 * @var	jQuery
	 */
	_username: null,
	
	/**
	 * Initializes LostPassword-form class.
	 */
	init: function() {
		// bind input fields
		this._email = $('#emailInput');
		this._username = $('#usernameInput');
		
		// bind event listener
		this._email.keyup($.proxy(this._checkEmail, this));
		this._username.keyup($.proxy(this._checkUsername, this));
		
		// toggle fields on init
		this._checkEmail();
		this._checkUsername();
	},
	
	/**
	 * Checks for content in email field and toggles username.
	 */
	_checkEmail: function() {
		if (this._email.val() == '') {
			this._username.enable();
			this._username.parents('dl:eq(0)').removeClass('disabled');
		}
		else {
			this._username.disable();
			this._username.parents('dl:eq(0)').addClass('disabled');
		}
	},
	
	/**
	 * Checks for content in username field and toggles email.
	 */
	_checkUsername: function() {
		if (this._username.val() == '') {
			this._email.enable();
			this._email.parents('dl:eq(0)').removeClass('disabled');
		}
		else {
			this._email.disable();
			this._email.parents('dl:eq(0)').addClass('disabled');
		}
	}
});

/**
 * Notification system for WCF.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
WCF.Notification = {};

/**
 * Notification Overlay.
 * 
 * @see	WCF.UserPanel
 */
WCF.Notification.Handler = WCF.UserPanel.extend({
	/**
	 * scrollable API
	 * @var	jquery.fn.scrollable
	 */
	_api: null,
	
	/**
	 * overlay container
	 * @var	jQuery
	 */
	_innerContainer: null,
	
	/**
	 * notification list
	 * @var	jQuery
	 */
	_listContainer: null,
	
	/**
	 * notification message container
	 * @var	jQuery
	 */
	_messageContainer: null,
	
	/**
	 * active notification id
	 * @var	integer
	 */
	_notificationID: 0,
	
	/**
	 * link to show all notifications
	 * @var	string
	 */
	_showAllLink: '',
	
	/**
	 * Creates a new overlay on init.
	 */
	init: function(showAllLink) {
		this._super('userNotifications');
		
		WCF.Dropdown.registerCallback('userNotifications', $.proxy(this._click, this));
	},
	
	/**
	 * @see	WCF.UserPanel._convert()
	 */
	_convert: function() {
		this._container.addClass('dropdown');
		this._link = this._container.children('a').remove();
		
		$('<a class="dropdownToggle">' + this._link.html() + '</a>').appendTo(this._container).click($.proxy(this._click, this));
		var $dropdownMenu = $('<div class="dropdownMenu userNotificationContainer" />').appendTo(this._container);
		this._innerContainer = $('<div id="userNotificationContainer" class="scrollableContainer" />').appendTo($dropdownMenu);
		$('<div class="scrollableItems clearfix"><div><p>' + WCF.Language.get('wcf.global.loading') + '</p></div><div><p>' + WCF.Language.get('wcf.global.loading') + '</p></div></div>').appendTo(this._innerContainer);
		
		var $itemsContainer = this._innerContainer.find('div.scrollableItems').click(function(event) { event.stopPropagation(); });
		this._listContainer = $itemsContainer.children('div:eq(0)');
		this._messageContainer = $itemsContainer.children('div:eq(1)');
		
		// initialize scrollable API
		this._innerContainer.scrollable({
			mousewheel: false,
			speed: 200
		});
		this._api = this._innerContainer.data('scrollable');
	},
	
	/**
	 * @see	WCF.UserPanel._click()
	 */
	_click: function() {
		if (this._didLoad) {
			return;
		}
		
		// load notifications
		new WCF.Notification.Loader(this._innerContainer, $.proxy(this._bindListener, this));
		
		this._didLoad = true;
	},
	
	/**
	 * Binds click listener for all items.
	 * 
	 * @param	jQuery		notificationList
	 */
	_bindListener: function(notificationList) {
		notificationList.find('li').each($.proxy(function(index, item) {
			$(item).click($.proxy(this._showMessage, this));
		}, this));
	},
	
	/**
	 * Displays the message (output) for current item.
	 * 
	 * @param	object		event
	 */
	_showMessage: function(event) {
		// consume event and discard it
		event.stopPropagation();
		
		var $item = $(event.currentTarget);
		
		// set notification id
		this._notificationID = $item.data('notificationID');
		
		// set fixed height (prevents box resize without animation)
		var $containerDimensions = this._innerContainer.getDimensions('outer');
		this._innerContainer.css({ height: $containerDimensions.height + 'px' });
		
		// insert html
		this._messageContainer.html($item.data('message')).click($.proxy(this.showList, this));
		var $messageContainerDimensions = this._messageContainer.getDimensions('outer');
		
		// bind buttons
		this._messageContainer.find('nav li').each($.proxy(function(index, button) {
			var $button = $(button);
			$button.click($.proxy(function(event) {
				new WCF.Notification.Action(this, this._api, this._listContainer.children('ul:eq(0)'), this._notificationID, $button);
				this.showList();
				
				return false;
			}, this));
		}, this));
		
		// adjust height
		if ($containerDimensions.height != $messageContainerDimensions.height) {
			this._innerContainer.animate({
				height: $messageContainerDimensions.height
			}, 200);
		}
		
		// show message
		this._api.next();
	},
	
	/**
	 * Displays list of notification items.
	 * 
	 * @param	object		event
	 */
	showList: function(event) {
		if (event) {
			// do not trigger API if clicking on a link
			if ($(event.target).getTagName() === 'a') {
				return;
			}
		}
		
		this._innerContainer.stop();
		this._api.prev();
		
		var $listHeight = this._listContainer.getDimensions();
		this._innerContainer.animate({
			height: $listHeight.height + 'px'
		}, 200);
	},
	
	/**
	 * Updates count of outstanding notifications
	 */
	updateCount: function(count) {
		var $userNotifications = $('#userNotifications');
		var $dropdownToggle = $userNotifications.children('.dropdownToggle');
		var $badge = $dropdownToggle.children('.badge');
		
		// revert to a simple link
		if (count > 0) {
			$badge.html(count);
		}
		else {
			// remove badge
			$badge.remove();
			
			// create new link
			$('<li><a href="' + $userNotifications.data('link') + '" title="' + $dropdownToggle.children('span').text() + '" class="jsTooltip">' + $dropdownToggle.html() + '</a></li>').insertBefore($userNotifications);
			$userNotifications.remove();
			
			return;
		}
	}
});

/**
 * Action fired upon button clicks within message.
 * 
 * @param	WCF.Notification.Handler	overlay
 * @param	jQuery.fn.scrollable		api
 * @param	jQuery				list
 * @param	integer				notificationID
 * @param	jQuery				targetElement
 */
WCF.Notification.Action = Class.extend({
	/**
	 * scrollable API
	 * @var	jQuery.fn.scrollable
	 */
	_api: null,
	
	/**
	 * overlay container
	 * @var	jQuery
	 */
	_container: null,
	
	/**
	 * loading overlay with spinner
	 * @var	jQuery
	 */
	_loading: null,
	
	/**
	 * current notification id
	 * @var	integer
	 */
	_notificationID: 0,
	
	/**
	 * notification overlay
	 * @var	WCF.Notification.Overlay
	 */
	_overlay: null,
	
	/**
	 * target element
	 * @var	jQuery
	 */
	_targetElement: null,
	
	/**
	 * Initializes a new action.
	 * 
	 * @param	WCF.Notification.Overlay	overlay
 	 * @param	jQuery.fn.scrollable		api
 	 * @param	jQuery				container
 	 * @param	integer				notificationID
 	 * @param	jQuery				targetElement
	 */
	init: function(overlay, api, list, notificationID, targetElement) {
		this._api = api;
		this._list = list;
		this._notificationID = notificationID;
		this._overlay = overlay;
		this._targetElement = targetElement;
		
		// send ajax request
		new WCF.Action.Proxy({
			autoSend: true,
			data: {
				actionName: this._targetElement.data('action'),
				className: this._targetElement.data('className'),
				objectIDs: [ this._targetElement.data('objectID') ],
				parameters: {
					notificationID: this._notificationID
				}
			},
			init: $.proxy(this._showLoadingOverlay, this),
			success: $.proxy(this._hideLoadingOverlay, this)
		});
	},
	
	/**
	 * Removes an item from list. An empty list will result in a notice displayed to user.
	 */
	_removeItem: function() {
		this._list.children('li').each($.proxy(function(index, item) {
			var $item = $(item);
			if ($item.data('notificationID') == this._notificationID) {
				// remove item itself
				$item.remove();
				
				// remove divider class
				if (this._list.children('li').length == 1) {
					this._list.children('li').removeClass('dropdownDivider');
				}
				
				// show list
				this._overlay.showList();
			}
		}, this));
	},
	
	/**
	 * Displays an overlay during loading.
	 */
	_showLoadingOverlay: function() {
		if (this._loading == null) {
			this._loading = $('<div id="userNotificationDetailsLoading"></div>').appendTo($('body')[0]);
			
			var $parentContainer = this._list.parent();
			var $dimensions = $parentContainer.getDimensions('outer');
			this._loading.css({
				height: $dimensions.height + 'px',
				left: $parentContainer.css('left'),
				top: $parentContainer.css('top'),
				width: $dimensions.width + 'px'
			});
		}
		
		this._loading.show();
	},
	
	/**
	 * Hides overlay after successful execution.
	 */
	_hideLoadingOverlay: function(data, textStatus, jqXHR) {
		this._loading.hide();
		this._removeItem();
		
		// update badge count
		this._overlay.updateCount(data.returnValues.totalCount);
	}
});

/**
 * Loads notifications.
 * 
 * @param	jQuery		container
 * @param	function	callback
 */
WCF.Notification.Loader = Class.extend({
	/**
	 * callback once all items are loaded
	 * @var	function
	 */
	_callback: null,
	
	/**
	 * overlay container
	 * @var	jQuery
	 */
	_container: null,
	
	/**
	 * Loads notifications.
	 * 
	 * @param	jQuery		container
	 * @param	function	callback
	 */
	init: function(container, callback) {
		this._container = container;
		this._callback = callback;
		
		// send ajax request
		new WCF.Action.Proxy({
			autoSend: true,
			data: {
				actionName: 'load',
				className: 'wcf\\data\\user\\notification\\UserNotificationAction'
			},
			success: $.proxy(this._success, this)
		});
	},
	
	/**
	 * Insert items after successful ajax query.
	 * 
	 * @param	object		data
	 * @param	string		textStatus
	 * @param	jQuery		jqXHR
	 */
	_success: function(data, textStatus, jqXHR) {
		var $userNotifications = $('#userNotifications');
		var $dropdownToggle = $userNotifications.children('.dropdownToggle');
		var $badge = $dropdownToggle.children('.badge');
		
		// revert to a simple link
		if (!data.returnValues.count) {
			// remove badge
			$badge.remove();
			
			// create new link
			$('<li><a href="' + $userNotifications.data('link') + '" title="' + $dropdownToggle.children('span').text() + '" class="jsTooltip">' + $dropdownToggle.html() + '</a></li>').insertBefore($userNotifications);
			$userNotifications.remove();
			
			return;
		}
		
		// update badge count
		if (!$badge.length) {
			$badge = $('<span class="badge badgeInverse" />').appendTo($dropdownToggle);
		}
		$badge.text(data.returnValues.totalCount);
		
		// create list container
		this._container.find('div.scrollableItems div:eq(0)').html('<ul></ul>');
		var $notificationList = this._container.find('div.scrollableItems ul');
		
		// insert notification items
		for (var i in data.returnValues.notifications) {
			var $notification = data.returnValues.notifications[i];
			
			var $item = $('' + $notification.template).data('notificationID', $notification.notificationID).data('message', $notification.message);
			$item.appendTo($notificationList);
		}
		
		// execute callback
		this._callback($notificationList);
		
		// display a "show all" link
		$('<li class="dropdownDivider"></li><li><a href="' + $userNotifications.data('link') + '">' + WCF.Language.get('wcf.user.notification.showAll') + '</a></li>').appendTo($notificationList);
	}
});

/**
 * Handles notification list actions.
 */
WCF.Notification.List = Class.extend({
	/**
	 * notification count
	 * @var	jQuery
	 */
	_badge: null,
	
	/**
	 * action proxy
	 * @var	WCF.Action.Proxy
	 */
	_proxy: null,
	
	/**
	 * Initializes the notification list.
	 */
	init: function() {
		var $containers = $('.jsNotificationAction');
		if (!$containers.length) {
			return;
		}
		
		$containers.each($.proxy(function(index, container) {
			$(container).children('li').click($.proxy(this._click, this));
		}, this));
		
		this._badge = $('.jsNotificationsBadge:eq(0)');
		this._proxy = new WCF.Action.Proxy({
			success: $.proxy(this._success, this)
		});
	},
	
	/**
	 * Handles button actions.
	 * 
	 * @param	object		event
	 */
	_click: function(event) {
		var $button = $(event.currentTarget);
		
		this._proxy.setOption('data', {
			actionName: $button.data('actionName'),
			className: $button.data('className'),
			objectIDs: [ $button.data('objectID') ],
			parameters: {
				notificationID: $button.parent().data('notificationID')
			}
		});
		this._proxy.sendRequest();
	},
	
	/**
	 * Handles successful button actions.
	 * 
	 * @param	object		data
	 * @param	string		textStatus
	 * @param	jQuery		jqXHR
	 */
	_success: function(data, textStatus, jqXHR) {
		var $notificationID = data.returnValues.notificationID;
		var self = this;
		$('.jsNotificationItem').each(function(index, item) {
			var $item = $(item);
			if ($item.data('notificationID') == $notificationID) {
				$item.remove();
				
				// reduce badge count
				self._badge.html((self._badge.html() - 1));
				
				return false;
			}
		});
	}
});

/**
 * Signature preview.
 * 
 * @see	WCF.Message.Preview
 */
WCF.User.SignaturePreview = WCF.Message.Preview.extend({
	/**
	 * @see	WCF.Message.Preview._handleResponse()
	 */
	_handleResponse: function(data) {
		// get preview container
		var $preview = $('#previewContainer');
		if (!$preview.length) {
			$preview = $('<fieldset id="previewContainer"><legend>' + WCF.Language.get('wcf.global.preview') + '</legend><div></div></fieldset>').insertBefore($('#signatureContainer')).wcfFadeIn();
		}
		
		$preview.children('div').first().html(data.returnValues.message);
	}
});

/**
 * Loads recent activity events once the user scrolls to the very bottom.
 * 
 * @param	integer		userID
 */
WCF.User.RecentActivityLoader = Class.extend({
	/**
	 * container object
	 * @var	jQuery
	 */
	_container: null,
	
	/**
	 * button to load next events
	 * @var	jQuery
	 */
	_loadButton: null,
	
	/**
	 * action proxy
	 * @var	WCF.Action.Proxy
	 */
	_proxy: null,
	
	/**
	 * user id
	 * @var	integer
	 */
	_userID: 0,
	
	/**
	 * Initializes a new RecentActivityLoader object.
	 * 
	 * @param	integer		userID
	 */
	init: function(userID) {
		this._container = $('#recentActivities');
		this._userID = userID;
		
		if (this._userID !== null && !this._userID) {
			console.debug("[WCF.User.RecentActivityLoader] Invalid parameter 'userID' given.");
			return;
		}
		
		this._proxy = new WCF.Action.Proxy({
			success: $.proxy(this._success, this)
		});
		
		this._loadButton = $('<li class="recentActivitiesMore"><button class="small">' + WCF.Language.get('wcf.user.recentActivity.more') + '</button></li>').appendTo(this._container);
		this._loadButton = this._loadButton.children('button').click($.proxy(this._click, this));
	},
	
	/**
	 * Loads next activity events.
	 */
	_click: function() {
		this._loadButton.enable();
		
		var $parameters = {
			lastEventTime: this._container.data('lastEventTime')
		};
		if (this._userID) {
			$parameters.userID = this._userID;
		}
		
		this._proxy.setOption('data', {
			actionName: 'load',
			className: 'wcf\\data\\user\\activity\\event\\UserActivityEventAction',
			parameters: $parameters
		});
		this._proxy.sendRequest();
	},
	
	/**
	 * Handles successful AJAX requests.
	 * 
	 * @param	object		data
	 * @param	string		textStatus
	 * @param	jQuery		jqXHR
	 */
	_success: function(data, textStatus, jqXHR) {
		if (data.returnValues.template) {
			$(data.returnValues.template).insertBefore(this._loadButton.parent());
			
			this._container.data('lastEventTime', data.returnValues.lastEventTime);
			this._loadButton.enable();
		}
		else {
			$('<small>' + WCF.Language.get('wcf.user.recentActivity.noMoreEntries') + '</small>').appendTo(this._loadButton.parent());
			this._loadButton.remove();
		}
	}
});

/**
 * Loads user profile previews.
 * 
 * @see	WCF.Popover
 */
WCF.User.ProfilePreview = WCF.Popover.extend({
	/**
	 * action proxy
	 * @var	WCF.Action.Proxy
	 */
	_proxy: null,
	
	/**
	 * list of user profiles
	 * @var	object
	 */
	_userProfiles: { },
	
	/**
	 * @see	WCF.Popover.init()
	 */
	init: function() {
		this._super('.userLink');
		
		this._proxy = new WCF.Action.Proxy({
			showLoadingOverlay: false
		});
	},
	
	/**
	 * @see	WCF.Popover._loadContent()
	 */
	_loadContent: function() {
		var $element = $('#' + this._activeElementID);
		var $userID = $element.data('userID');
		
		if (this._userProfiles[$userID]) {
			// use cached user profile
			this._insertContent(this._activeElementID, this._userProfiles[$userID], true);
		}
		else {
			this._proxy.setOption('data', {
				actionName: 'getUserProfile',
				className: 'wcf\\data\\user\\UserProfileAction',
				objectIDs: [ $userID ]
			});
			
			var $elementID = this._activeElementID;
			var self = this;
			this._proxy.setOption('success', function(data, textStatus, jqXHR) {
				// cache user profile
				self._userProfiles[$userID] = data.returnValues.template;
				
				// show user profile
				self._insertContent($elementID, data.returnValues.template, true);
			});
			this._proxy.sendRequest();
		}
	}
});

/**
 * Initalizes WCF.User.Action namespace.
 */
WCF.User.Action = {};

/**
 * Handles user follow and unfollow links.
 */
WCF.User.Action.Follow = Class.extend({
	/**
	 * list with elements containing follow and unfollow buttons
	 * @var	array
	 */
	_containerList: null,
	
	/**
	 * CSS selector for follow buttons
	 * @var	string
	 */
	_followButtonSelector: '.jsFollowButton',
	
	/**
	 * id of the user that is currently being followed/unfollowed
	 * @var	integer
	 */
	_userID: 0,
	
	/**
	 * Initializes new WCF.User.Action.Follow object.
	 * 
	 * @param	array		containerList
	 * @param	string		followButtonSelector
	 */
	init: function(containerList, followButtonSelector) {
		if (!containerList.length) {
			return;
		}
		this._containerList = containerList;
		
		if (followButtonSelector) {
			this._followButtonSelector = followButtonSelector;
		}
		
		// initialize proxy
		this._proxy = new WCF.Action.Proxy({
			success: $.proxy(this._success, this)
		});
		
		// bind event listeners
		this._containerList.each($.proxy(function(index, container) {
			$(container).find(this._followButtonSelector).click($.proxy(this._click, this));
		}, this));
	},
	
	/**
	 * Handles a click on a follow or unfollow button.
	 * 
	 * @param	object		event
	 */
	_click: function(event) {
		var link = $(event.target);
		if (!link.is('a')) {
			link = link.closest('a');
		}
		this._userID = link.data('objectID');
		
		this._proxy.setOption('data', {
			'actionName': link.data('following') ? 'unfollow' : 'follow',
			'className': 'wcf\\data\\user\\follow\\UserFollowAction',
			'parameters': {
				data: {
					userID: this._userID
				}
			}
		});
		this._proxy.sendRequest();
	},
	
	/**
	 * Handles the successful (un)following of a user.
	 * 
	 * @param	object		data
	 * @param	string		textStatus
	 * @param	jQuery		jqXHR
	 */
	_success: function(data, textStatus, jqXHR) {
		this._containerList.each($.proxy(function(index, container) {
			var button = $(container).find(this._followButtonSelector).get(0);
			
			if (button && $(button).data('objectID') == this._userID) {
				button = $(button);
				
				// toogle icon title
				if (data.returnValues.following) {
					button.data('tooltip', WCF.Language.get('wcf.user.button.unfollow')).children('.icon').removeClass('icon-plus').addClass('icon-minus');
				}
				else {
					button.data('tooltip', WCF.Language.get('wcf.user.button.follow')).children('.icon').removeClass('icon-minus').addClass('icon-plus');
				}
				
				button.data('following', data.returnValues.following);
				
				return false;
			}
		}, this));
	}
});

/**
 * Handles user ignore and unignore links.
 */
WCF.User.Action.Ignore = Class.extend({
	/**
	 * list with elements containing ignore and unignore buttons
	 * @var	array
	 */
	_containerList: null,
	
	/**
	 * CSS selector for ignore buttons
	 * @var	string
	 */
	_ignoreButtonSelector: '.jsIgnoreButton',
	
	/**
	 * id of the user that is currently being ignored/unignored
	 * @var	integer
	 */
	_userID: 0,
	
	/**
	 * Initializes new WCF.User.Action.Ignore object.
	 * 
	 * @param	array		containerList
	 * @param	string		ignoreButtonSelector
	 */
	init: function(containerList, ignoreButtonSelector) {
		if (!containerList.length) {
			return;
		}
		this._containerList = containerList;
		
		if (ignoreButtonSelector) {
			this._ignoreButtonSelector = ignoreButtonSelector;
		}
		
		// initialize proxy
		this._proxy = new WCF.Action.Proxy({
			success: $.proxy(this._success, this)
		});
		
		// bind event listeners
		this._containerList.each($.proxy(function(index, container) {
			$(container).find(this._ignoreButtonSelector).click($.proxy(this._click, this));
		}, this));
	},
	
	/**
	 * Handles a click on a ignore or unignore button.
	 * 
	 * @param	object		event
	 */
	_click: function(event) {
		var link = $(event.target);
		if (!link.is('a')) {
			link = link.closest('a');
		}
		this._userID = link.data('objectID');
		
		this._proxy.setOption('data', {
			'actionName': link.data('ignored') ? 'unignore' : 'ignore',
			'className': 'wcf\\data\\user\\ignore\\UserIgnoreAction',
			'parameters': {
				data: {
					ignoreUserID: this._userID
				}
			}
		});
		this._proxy.sendRequest();
	},
	
	/**
	 * Handles the successful (un)ignoring of a user.
	 * 
	 * @param	object		data
	 * @param	string		textStatus
	 * @param	jQuery		jqXHR
	 */
	_success: function(data, textStatus, jqXHR) {
		this._containerList.each($.proxy(function(index, container) {
			var button = $(container).find(this._ignoreButtonSelector).get(0);
			
			if (button && $(button).data('objectID') == this._userID) {
				button = $(button);
				
				// toogle icon title
				if (data.returnValues.isIgnoredUser) {
					button.data('tooltip', WCF.Language.get('wcf.user.button.unignore')).children('.icon').removeClass('icon-off').addClass('icon-circle-blank');
				}
				else {
					button.data('tooltip', WCF.Language.get('wcf.user.button.ignore')).children('.icon').removeClass('icon-circle-blank').addClass('icon-off');
				}
				
				button.data('ignored', data.returnValues.isIgnoredUser);
				
				return false;
			}
		}, this));
	}
});

/**
 * Namespace for avatar functions.
 */
WCF.User.Avatar = {};

/**
 * Avatar upload function
 * 
 * @see	WCF.Upload
 */
WCF.User.Avatar.Upload = WCF.Upload.extend({
	init: function(buttonSelector) {
		this._super($('#avatarUpload > dd > div'), undefined, 'wcf\\data\\user\\avatar\\UserAvatarAction');
		
		$('#avatarForm input[type=radio]').change(function() {
			if ($(this).val() == 'custom') {
				$('#avatarUpload > dd > div').show();
			}
			else {
				$('#avatarUpload > dd > div').hide();
			}
		});
		if (!$('#avatarForm input[type=radio][value=custom]:checked').length) {
			$('#avatarUpload > dd > div').hide();
		}
	},
	
	_initFile: function(file) {
		return $('#avatarUpload > dt > img');
	},
	
	_success: function(uploadID, data) {
		if (data.returnValues['url']) {
			// show avatar
			$('#avatarUpload > dt > img').attr('src', data.returnValues['url']).css({
				width: 'auto',
				height: 'auto'
			});
			
			// hide error
			$('#avatarUpload > dd > .innerError').remove();
			
			// show success message
			var $notification = new WCF.System.Notification(WCF.Language.get('wcf.user.avatar.upload.success'));
			$notification.show();
		}
		else if (data.returnValues['errorType']) {
			// show error
			this._getInnerErrorElement().text(WCF.Language.get('wcf.user.avatar.upload.error.'+data.returnValues['errorType']));
		}
	},
	
	_getInnerErrorElement: function() {
		var $span = $('#avatarUpload > dd > .innerError');
		if (!$span.length) {
			$span = $('<small class="innerError"></span>');
			$('#avatarUpload > dd').append($span);
		}
		
		return $span;
	}
});

/**
 * Generic implementation for grouped user lists.
 * 
 * @param	string		className
 * @param	string		dialogTitle
 * @param	object		additionalParameters
 */
WCF.User.List = Class.extend({
	/**
	 * list of additional parameters
	 * @var	object
	 */
	_additionalParameters: { },
	
	/**
	 * list of cached pages
	 * @var	object
	 */
	_cache: { },
	
	/**
	 * action class name
	 * @var	string
	 */
	_className: '',
	
	/**
	 * dialog overlay
	 * @var	jQuery
	 */
	_dialog: null,
	
	/**
	 * dialog title
	 * @var	string
	 */
	_dialogTitle: '',
	
	/**
	 * page count
	 * @var	integer
	 */
	_pageCount: 0,
	
	/**
	 * current page no
	 * @var	integer
	 */
	_pageNo: 1,
	
	/**
	 * action proxy
	 * @var	WCF.Action.Proxy
	 */
	_proxy: null,
	
	/**
	 * Initializes a new grouped user list.
	 * 
	 * @param	string		className
	 * @param	string		dialogTitle
	 * @param	object		additionalParameters
	 */
	init: function(className, dialogTitle, additionalParameters) {
		this._additionalParameters = additionalParameters || { };
		this._cache = { };
		this._className = className;
		this._dialog = null;
		this._dialogTitle = dialogTitle;
		this._pageCount = 0;
		this._pageNo = 1;
		
		this._proxy = new WCF.Action.Proxy({
			success: $.proxy(this._success, this)
		});
	},
	
	/**
	 * Opens the dialog overlay.
	 */
	open: function() {
		this._pageNo = 1;
		this._showPage();
	},
	
	/**
	 * Displays the specified page.
	 * 
	 * @param	object		event
	 * @param	object		data
	 */
	_showPage: function(event, data) {
		if (data && data.activePage) {
			this._pageNo = data.activePage;
		}
		
		if (this._pageCount != 0 && (this._pageNo < 1 || this._pageNo > this._pageCount)) {
			console.debug("[WCF.User.List] Cannot access page " + this._pageNo + " of " + this._pageCount);
			return;
		}
		
		if (this._cache[this._pageNo]) {
			var $dialogCreated = false;
			if (this._dialog === null) {
				this._dialog = $('<div id="userList' + this._className.hashCode() + '" style="min-width: 600px;" />').hide().appendTo(document.body);
				$dialogCreated = true;
			}
			
			// remove current view
			this._dialog.empty();
			
			// insert HTML
			this._dialog.html(this._cache[this._pageNo]);
			
			// add pagination
			if (this._pageCount > 1) {
				this._dialog.find('.jsPagination').wcfPages({
					activePage: this._pageNo,
					maxPage: this._pageCount
				}).bind('wcfpagesswitched', $.proxy(this._showPage, this));
			}
			
			// show dialog
			if ($dialogCreated) {
				this._dialog.wcfDialog({
					title: this._dialogTitle
				});
			}
			else {
				this._dialog.wcfDialog('open').wcfDialog('render');
			}
		}
		else {
			this._additionalParameters.pageNo = this._pageNo;
			
			// load template via AJAX
			this._proxy.setOption('data', {
				actionName: 'getGroupedUserList',
				className: this._className,
				interfaceName: 'wcf\\data\\IGroupedUserListAction',
				parameters: this._additionalParameters
			});
			this._proxy.sendRequest();
		}
	},
	
	/**
	 * Handles successful AJAX requests.
	 * 
	 * @param	object		data
	 * @param	string		textStatus
	 * @param	jQuery		jqXHR
	 */
	_success: function(data, textStatus, jqXHR) {
		if (data.returnValues.pageCount) {
			this._pageCount = data.returnValues.pageCount;
		}
		
		this._cache[this._pageNo] = data.returnValues.template;
		this._showPage();
	}
});

/**
 * Namespace for object watch functions.
 */
WCF.User.ObjectWatch = {};

/**
 * Handles subscribe/unsubscribe links.
 */
WCF.User.ObjectWatch.Subscribe = Class.extend({
	/**
	 * CSS selector for subscribe buttons
	 * @var	string
	 */
	_buttonSelector: '.jsSubscribeButton',
	
	/**
	 * list of buttons
	 * @var	object
	 */
	_buttons: { },
	
	/**
	 * dialog overlay
	 * @var	object
	 */
	_dialog: null,
	
	/**
	 * WCF.User.ObjectWatch.Subscribe object.
	 */
	init: function() {
		this._buttons = { };
		
		// initialize proxy
		this._proxy = new WCF.Action.Proxy({
			success: $.proxy(this._success, this)
		});
		
		// bind event listeners
		$(this._buttonSelector).each($.proxy(function(index, button) {
			var $button = $(button);
			var $objectID = $button.data('objectID');
			this._buttons[$objectID] = $button.click($.proxy(this._click, this));
		}, this));
	},
	
	/**
	 * Handles a click on a subscribe button.
	 * 
	 * @param	object		event
	 */
	_click: function(event) {
		var $button = $(event.currentTarget);
		
		this._proxy.setOption('data', {
			actionName: 'manageSubscription',
			className: 'wcf\\data\\user\\object\\watch\\UserObjectWatchAction',
			parameters: {
				objectID: $button.data('objectID'),
				objectType: $button.data('objectType')
			}
		});
		this._proxy.sendRequest();
	},
	
	/**
	 * Handles successful AJAX requests.
	 * 
	 * @param	object		data
	 * @param	string		textStatus
	 * @param	jQuery		jqXHR
	 */
	_success: function(data, textStatus, jqXHR) {
		if (data.actionName === 'manageSubscription') {
			if (this._dialog === null) {
				this._dialog = $('<div>' + data.returnValues.template + '</div>').hide().appendTo(document.body);
				this._dialog.wcfDialog({
					title: WCF.Language.get('wcf.user.objectWatch.manageSubscription')
				});
			}
			else {
				this._dialog.html(data.returnValues.template);
				this._dialog.wcfDialog('open');
			}
			
			// bind event listener
			this._dialog.find('.formSubmit > .jsButtonSave').data('objectID', data.returnValues.objectID).click($.proxy(this._save, this));
			var $enableNotification = this._dialog.find('input[name=enableNotification]').disable();
			
			// toggle subscription
			this._dialog.find('input[name=subscribe]').change(function(event) {
				var $input = $(event.currentTarget);
				if ($input.val() == 1) {
					$enableNotification.enable();
				}
				else {
					$enableNotification.disable();
				}
			});
			
			// setup
			var $selectedOption = this._dialog.find('input[name=subscribe]:checked');
			if ($selectedOption.length && $selectedOption.val() == 1) {
				$enableNotification.enable();
			}
		}
		else if (data.actionName === 'saveSubscription' && this._dialog.is(':visible')) {
			this._dialog.wcfDialog('close');
		}
	},
	
	/**
	 * Saves the subscription.
	 * 
	 * @param	object		event
	 */
	_save: function(event) {
		var $button = this._buttons[$(event.currentTarget).data('objectID')];
		var $subscribe = this._dialog.find('input[name=subscribe]:checked').val();
		var $enableNotification = (this._dialog.find('input[name=enableNotification]').is(':checked')) ? 1 : 0;
		
		this._proxy.setOption('data', {
			actionName: 'saveSubscription',
			className: 'wcf\\data\\user\\object\\watch\\UserObjectWatchAction',
			parameters: {
				enableNotification: $enableNotification,
				objectID: $button.data('objectID'),
				objectType: $button.data('objectType'),
				subscribe: $subscribe
			}
		});
		this._proxy.sendRequest();
	}
});

/**
 * Enables notifications for subscriptions.
 */
WCF.User.ObjectWatch.Notification = Class.extend({
	/**
	 * CSS selector for buttons
	 * @var	string
	 */
	_buttonSelector: '.jsObjectWatchNotificationButton',
	
	/**
	 * watch id
	 * @var	integer
	 */
	_watchID: 0,
	
	/**
	 * WCF.User.ObjectWatch.Notification object.
	 */
	init: function() {
		// initialize proxy
		this._proxy = new WCF.Action.Proxy({
			success: $.proxy(this._success, this)
		});
		
		// bind event listeners
		$(this._buttonSelector).click($.proxy(this._click, this));
	},
	
	/**
	 * Handles a click on a button.
	 * 
	 * @param	object		event
	 */
	_click: function(event) {
		var link = $(event.target);
		if (!link.is('a')) {
			link = link.closest('a');
		}
		this._watchID = link.data('watchID');
		
		this._proxy.setOption('data', {
			actionName: link.data('notification') ? 'disableNotification' : 'enableNotification',
			className: 'wcf\\data\\user\\object\\watch\\UserObjectWatchAction',
			objectIDs: [ this._watchID ]
		});
		this._proxy.sendRequest();
	},
	
	/**
	 * Handles the successful action.
	 * 
	 * @param	object		data
	 * @param	string		textStatus
	 * @param	jQuery		jqXHR
	 */
	_success: function(data, textStatus, jqXHR) {
		$(this._buttonSelector).each($.proxy(function(index, container) {
			var button = $(container);
			
			if (button.data('watchID') == this._watchID) {
				// toogle icon title
				if (button.data('notification')) {
					button.children('img').removeClass('disabled');
					button.data('tooltip', WCF.Language.get('wcf.user.watchedObjects.enableNotification'));
				}
				else {
					button.children('img').addClass('disabled');
					button.data('tooltip', WCF.Language.get('wcf.user.watchedObjects.disableNotification'));
				}
				
				button.data('notification', !button.data('notification'));
				
				return false;
			}
		}, this));
	}
});
