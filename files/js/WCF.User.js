/**
 * Quick login box
 * 
 * @param	boolean		isQuickLogin
 */
WCF.User.Login = Class.extend({
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
		$loginForm.find('input[type=reset]').click($.proxy(this._click, this));
		
		if (isQuickLogin) {
			$('#loginLink').click(function() {
				WCF.showDialog('loginForm', {
					title: WCF.Language.get('wcf.user.login')
				});
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
	 * Handles clicks on the reset button.
	 */
	_click: function() {
		this._setState(true, true, WCF.Language.get('wcf.user.button.login'));
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
			this._useCookies.enable()
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
		this._button = $('<button id="followUser">follow</button>').appendTo($('#profileButtonContainer'));
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
		var $label = 'follow';
		if(this._following) {
			$label = 'unfollow';
		}

		// update label
		this._button.text($label);
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
			this._button = $('<button id="ignoreUser"></button>').appendTo($('#profileButtonContainer'));
		}

		this._button.text(WCF.Language.get('wcf.user.profile.' + (this._isIgnoredUser ? 'un' : '') + 'ignoreUser'));
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
		this._profileContent.find('div.wcf-tabMenuContent').each($.proxy(function(index, container) {
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
		console.debug($panel.data('menuItem'));
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
		var $template = $('<div>' + data.returnValues.template + '</div>').hide().appendTo($content);
		
		// slide in content
		$content.children('div').wcfBlindIn();
	}
});

/**
 * Namespace for editable profile content.
 */
WCF.User.Profile.Editor = {};

/**
 * Editable profile content handler.
 */
WCF.User.Profile.Editor.Handler = {
	/**
	 * list of registered editors
	 * @var	array<WCF.User.Profile.Editor.Base>
	 */
	_callbacks: [ ],

	/**
	 * initialization state
	 * @var	boolean
	 */
	_didInit: false,

	/**
	 * number of active editors
	 * @var	integer
	 */
	_loading: 0,

	/**
	 * currently active action
	 * @var	string
	 */
	_pendingAction: '',

	/**
	 * action proxy
	 * @var	WCF.Action.Proxy
	 */
	_proxy: null,

	/**
	 * user interface elements
	 * @var	object
	 */
	_ui: {},

	/**
	 * target user id
	 * @var	integer
	 */
	_userID: 0,

	/**
	 * Initializes the user profile editor handler.
	 * 
	 * @param	integer		userID
	 */
	init: function(userID) {
		if (this._didInit) return;

		this._userID = userID;

		this._prepareUI();
		this._didInit = true;

		this._proxy = new WCF.Action.Proxy({
			success: $.proxy(this._success, this),
			url: 'index.php/UserProfileEditableContent/?t=' + SECURITY_TOKEN + SID_ARG_2ND
		});
	},

	/**
	 * Prepares user interface elements on init.
	 */
	_prepareUI: function() {
		var $buttonContainer = $('#profileButtonContainer');

		// create interface elements
		this._ui = {
			buttons: {
				beginEdit: $('<button id="beginEdit">beginEdit</button>').data('action', 'beginEdit').appendTo($buttonContainer),
				restore: $('<button id="restore">restore</button>').data('action', 'restore').appendTo($buttonContainer),
				save: $('<button id="save">save</button>').data('action', 'save').appendTo($buttonContainer)
			}
		};

		// bind event listener
		this._ui.buttons.beginEdit.click($.proxy(this._click, this));
		this._ui.buttons.restore.click($.proxy(this._click, this));
		this._ui.buttons.save.click($.proxy(this._click, this));

		// toggle buttons
		this._showButtons(true, false, false);
	},

	/**
	 * Handles button clicks.
	 * 
	 * @param	object		event
	 */
	_click: function(event) {
		this._pendingAction = $(event.currentTarget).data('action');
		
		switch (this._pendingAction) {
			case 'beginEdit':
				this._beginEdit();

				// toggle buttons
				this._showButtons(false, true, true);
			break;

			case 'restore':
				this._restore();
				
				// toggle buttons
				this._showButtons(true, false, false);
			break;

			case 'save':
				this._save();

				// toggle buttons
				this._showButtons(true, false, false);
			break;
		}
	},

	/**
	 * Begins editing mode by sending all active object type ids.
	 */
	_beginEdit: function() {
		var $objectTypeIDs = [ ];

		for (var $i = 0, $length = this._callbacks.length; $i < $length; $i++) {
			$objectTypeIDs.push(this._callbacks[$i].beginEdit());
		}

		this._proxy.setOption('data', {
			actionName: 'beginEdit',
			objectTypeIDs: $objectTypeIDs,
			userID: this._userID
		});
		this._proxy.sendRequest();
	},

	/**
	 * Restores previous view.
	 */
	_restore: function() {
		this._execute($.proxy(function(callback) {
			callback.restore();
		}, this));
	},

	/**
	 * Saves changed values.
	 */
	_save: function() {
		var $objectTypeIDs = [ ];
		var $values = {};
		this._execute($.proxy(function(callback) {
			var $objectTypeID = callback.getObjectTypeID();

			$objectTypeIDs.push($objectTypeID);
			$values[$objectTypeID] = callback.save();
		}, this));

		this._proxy.setOption('data', {
			actionName: 'save',
			objectTypeIDs: $objectTypeIDs,
			userID: this._userID,
			values: $values
		});
		this._proxy.sendRequest();
	},

	/**
	 * Displays the requested buttons.
	 * 
	 * @param	boolean		beginEdit
	 * @param	boolean		restore
	 * @param	boolean		save
	 */
	_showButtons: function(beginEdit, restore, save) {
		if (beginEdit) this._ui.buttons.beginEdit.show();
		else this._ui.buttons.beginEdit.hide();

		if (restore) this._ui.buttons.restore.show();
		else this._ui.buttons.restore.hide();

		if (save) this._ui.buttons.save.show();
		else this._ui.buttons.save.hide();
	},

	/**
	 * Registers a new callback.
	 * 
	 * @param	string		identifier
	 * @param	object		callback
	 */
	addCallback: function(identifier, callback) {
		if (!(callback instanceof WCF.User.Profile.Editor.Base)) {
			console.debug("[WCF.User.Profile.Editor.Handler] Given callback identified by '" + identifier + "' is not a valid callback, aborting.");
			return;
		}

		this._callbacks.push(callback);
	},

	/**
	 * Notifies that a callback is ready.
	 */
	didLoad: function() {
		this._loading--;
	},

	/**
	 * Handles AJAX responses.
	 * 
	 * @param	object		data
	 * @param	string		textStatus
	 * @param	jQuery		jqXHR
	 */
	_success: function(data, textStatus, jqXHR) {
		this._execute($.proxy(function(callback) {
			var $objectTypeID = callback.getObjectTypeID();
			
			switch (this._pendingAction) {
				case 'beginEdit':
					if (data[$objectTypeID]) {
						callback.prepareEdit(data[$objectTypeID]);
					}
				break;

				case 'save':
					if (data[$objectTypeID]) {
						callback.updateCache(data[$objectTypeID]);
						callback.restore();
					}
				break;
			}
		}, this));
	},

	/**
	 * Executes a callback on all previously registered callbacks.
	 * 
	 * @param	object		callback
	 */
	_execute: function(callback) {
		for (var $i = 0, $length = this._callbacks.length; $i < $length; $i++) {
			var $callback = this._callbacks[$i];
			callback($callback);
		}
	}
};

/**
 * Default implementation for user profile editors.
 * 
 * @param	integer		objectTypeID
 */
WCF.User.Profile.Editor.Base = Class.extend({
	/**
	 * cached view
	 * @var	string
	 */
	_cache: '',

	/**
	 * target container id
	 * @var	string
	 */
	_containerID: '',

	/**
	 * target container
	 * @var	jQuery
	 */
	_container: null,

	/**
	 * editor identifier
	 * @var	string
	 */
	_name: '',

	/**
	 * object type id
	 * @var	integer
	 */
	_objectTypeID: 0,
	
	/**
	 * Initializes a user profile editor.
	 * 
	 * @param	integer		objectTypeID
	 */
	init: function(objectTypeID) {
		this._objectTypeID = objectTypeID;
		
		if (this._name !== '') {
			WCF.User.Profile.Editor.Handler.addCallback(this._name, this);
		}

		this._container = $('#' + $.wcfEscapeID(this._containerID));
	},

	/**
	 * Begins editing mode.
	 * 
	 * @return	integer
	 */
	beginEdit: function() {
		return this._objectTypeID;
	},

	/**
	 * Prepares the editing mode by exchanging current view.
	 * 
	 * @param	string		returnValues
	 */
	prepareEdit: function(returnValues) {
		WCF.User.Profile.Editor.Handler.didLoad();

		var self = this;
		this._container.html(function(index, oldHTML) {
			self._cache = oldHTML;
			return returnValues;
		});
	},

	/**
	 * Restores previous view.
	 */
	restore: function() {
		WCF.User.Profile.Editor.Handler.didLoad();

		this._container.html(this._cache);
	},

	/**
	 * Returns edit input field values.
	 * 
	 * @return	object
	 */
	save: function() {
		return { };
	},

	/**
	 * Returns editor's object type id.
	 * 
	 * @return	integer
	 */
	getObjectTypeID: function() {
		return this._objectTypeID;
	},

	/**
	 * Updates cached view.
	 * 
	 * @param	string		cache
	 */
	updateCache: function(cache) {
		this._cache = cache;
	}
});

/**
 * User profile editor implementation for overview contents.
 * 
 * @see	WCF.User.Profile.Editor.Base
 */
WCF.User.Profile.Editor.Overview = WCF.User.Profile.Editor.Base.extend({
	/**
	 * @see	WCF.User.Profile.Editor.Base._containerID
	 */
	_containerID: 'wcf_user_profile_menu_overview',

	/**
	 * @see	WCF.User.Profile.Editor.Base._name
	 */
	_name: 'WCF.User.Profile.Editor.Overview',

	/**
	 * @see	WCF.User.Profile.Editor.Base.save()
	 */
	save: function() {
		var $values = { };

		// collect values
		this._container.find('input').each(function(index, element) {
			var $element = $(element);
			var $type = $element.attr('type');

			if (($type == 'radio' || $type === 'checkbox') && !$element.prop('checked')) {
				return;
			}

			$values[$element.attr('name')] = $element.val();
		});
		this._container.find('textarea').each(function(index, element) {
			var $element = $(element);

			$values[$element.attr('name')] = $element.val();
		});

		return this._parseValues($values);
	},

	/**
	 * Parses input names.
	 * 
	 * @param	object		values
	 * @return	object
	 */
	_parseValues: function(values) {
		var $parsedValues = { };
		var $regEx = /values\[([a-zA-Z0-9._-]+)\]/;

		for (var $i in values) {
			if ($regEx.test($i)) {
				var $matches = $regEx.exec($i);
				$parsedValues[$matches[1]] = values[$i];
			}
		}
		
		return $parsedValues;
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
		
		if (confirmElement !== null) {
			this._confirmElement = confirmElement;
			this._confirmElement.blur($.proxy(this._blurConfirm, this));
		}
		
		if (options) {
			this._setOptions(options);
		}
		
		this._proxy = new WCF.Action.Proxy({
			success: $.proxy(this._success, this)
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
			return this._showError(this._element, WCF.Language.get('wcf.global.error.empty'));
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
			return this._showError(this._confirmElement, WCF.Language.get('wcf.global.error.empty'));
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
		element.parent().prev().addClass('wcf-formError').removeClass('wcf-formSuccess');
		
		var $innerError = element.parent().find('small.wcf-innerError');
		if (!$innerError.length) {
			$innerError = $('<small />').addClass('wcf-innerError').insertAfter(element);
		}
		
		$innerError.text(message);
	},
	
	/**
	 * Displays a success message.
	 * 
	 * @param	jQuery		element
	 */
	_showSuccess: function(element) {
		element.parent().prev().addClass('wcf-formSuccess').removeClass('wcf-formError');
		element.next('small.wcf-innerError').remove();
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
			ajaxError: 'wcf.user.error.username.'
		};
	},
	
	/**
	 * @see	WCF.User.Registration.Validation._validateOptions()
	 */
	_validateOptions: function() {
		var $value = this._element.val();
		if ($value.length < this._options.minlength || $value.length > this._options.maxlength) {
			this._showError(this._element, WCF.Language.get('wcf.user.error.username.notValid'));
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
			ajaxError: 'wcf.user.error.email.',
			notEqual: WCF.Language.get('wcf.user.error.confirmEmail.notEqual')
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
			ajaxError: 'wcf.user.error.password.',
			notEqual: WCF.Language.get('wcf.user.error.confirmPassword.notEqual')
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
		$('#resetButton').click($.proxy(this._reset, this));
		
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
		}
		else {
			this._username.disable();
		}
	},
	
	/**
	 * Checks for content in username field and toggles email.
	 */
	_checkUsername: function() {
		if (this._username.val() == '') {
			this._email.enable();
		}
		else {
			this._email.disable();
		}
	},
	
	/**
	 * Restores field state.
	 */
	_reset: function() {
		this._email.enable();
		this._username.enable();
	}
});