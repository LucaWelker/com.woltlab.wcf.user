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
		//$loginForm.find('input[type=reset]').click($.proxy(this._reset, this));
		
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
	/*_reset: function() {
		this._setState(true, WCF.Language.get('wcf.user.button.login'));
	},*/
	
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
		this._button = $('<li id="followUser"><a class="button">'+WCF.Language.get('wcf.user.button.follow')+'</a></li>').appendTo($('#profileButtonContainer'));
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
			this._button = $('<li id="ignoreUser"><a class="button"></a></li>').appendTo($('#profileButtonContainer'));
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
	 * @param	boolean		editOnInit
	 */
	init: function(userID, editOnInit) {
		if (this._didInit) return;
		
		this._userID = userID;
		
		this._prepareUI();
		this._didInit = true;
		
		this._proxy = new WCF.Action.Proxy({
			success: $.proxy(this._success, this),
			url: 'index.php/UserProfileEditableContent/?t=' + SECURITY_TOKEN + SID_ARG_2ND
		});
		
		if (editOnInit) {
			// delay execution by 250 ms to allow handlers to register themselves
			var self = this;
			new WCF.PeriodicalExecuter(function(pe) {
				pe.stop();
				
				self._pendingAction = 'beginEdit';
				self._beginEdit();
			}, 250);
		}
	},
	
	/**
	 * Prepares user interface elements on init.
	 */
	_prepareUI: function() {
		var $buttonContainer = $('#profileButtonContainer');
		
		// create interface elements
		this._ui = {
			buttons: {
				beginEdit: $('<li class="button">'+WCF.Language.get('wcf.user.editProfile')+'</li>').data('action', 'beginEdit').appendTo($buttonContainer)
			}
		};
		
		// bind event listener
		this._ui.buttons.beginEdit.click($.proxy(this._click, this));
		// toggle buttons
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
			break;
			
			case 'restore':
				this._restore();
				
				// toggle button
				this._ui.buttons.beginEdit.show();
			break;
			
			case 'save':
				this._save();

				// toggle button
				this._ui.buttons.beginEdit.show();
			break;
		}
	},
	
	/**
	 * Returns action buttons.
	 * 
	 * @return	object
	 */
	getButtons: function() {
		return {
			save: $('<button class="buttonPrimary" accesskey="s">'+WCF.Language.get('wcf.global.button.save')+'</button>').data('action', 'save').click($.proxy(this._click, this)),
			restore: $('<button>'+WCF.Language.get('wcf.global.button.cancel')+'</button>').data('action', 'restore').click($.proxy(this._click, this))
		};
	},
	
	/**
	 * Begins editing mode by sending all active object type ids.
	 */
	_beginEdit: function() {
		// toggle button
		this._ui.buttons.beginEdit.hide();
		
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
			
			if (typeof data[$objectTypeID] === undefined) {
				return;
			}
			
			switch (this._pendingAction) {
				case 'beginEdit':
					callback.prepareEdit(data[$objectTypeID]);
				break;
				
				case 'save':
					callback.updateCache(data[$objectTypeID]);
					callback.restore();
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
		this._container.find('input').on('keyup', function (event) {
			if (event.keyCode === 13) { // Enter
				WCF.User.Profile.Editor.Handler._pendingAction = 'save';
				WCF.User.Profile.Editor.Handler._save();

				// toggle button
				WCF.User.Profile.Editor.Handler._ui.buttons.beginEdit.show();
			}
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
 * User profile editor implementation for information contents.
 * 
 * @see	WCF.User.Profile.Editor.Base
 */
WCF.User.Profile.Editor.Information = WCF.User.Profile.Editor.Base.extend({
	/**
	 * @see	WCF.User.Profile.Editor.Base._containerID
	 */
	_containerID: 'about',
	
	/**
	 * @see	WCF.User.Profile.Editor.Base._name
	 */
	_name: 'WCF.User.Profile.Editor.Information',
	
	/**
	 * @see	WCF.User.Profile.Editor.Base.beginEdit()
	 */
	beginEdit: function() {
		// show tab
		$('#profileContent').wcfTabs('select', 'about');
		
		return this._super();
	},
	
	/**
	 * @see	WCF.User.Profile.Editor.Base.prepareEdit()
	 */
	prepareEdit: function(returnValues) {
		this._super(returnValues);
		
		var $formSubmit = $('<div class="formSubmit" />').appendTo($('#' + this._containerID).children('.containerPadding'));
		var $buttons = WCF.User.Profile.Editor.Handler.getButtons();
		$buttons.save.appendTo($formSubmit);
		$buttons.restore.appendTo($formSubmit);
	},
	
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
		
		WCF.Dropdown.registerCallback('userNotifications', $.proxy(this._createOverlay, this));
	},
	
	/**
	 * @see	WCF.UserPanel._convert()
	 */
	_convert: function() {
		this._container.addClass('dropdown');
		this._link = this._container.children('a').remove();
		
		$('<a class="dropdownToggle jsTooltip" title="' + this._container.data('title') + '">' + this._link.html() + '</a>').appendTo(this._container).click($.proxy(this._click, this));
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
	 * pagination offset
	 * @var	integer
	 */
	_pageNo: 0,
	
	/**
	 * action proxy
	 * @var	WCF.Action.Proxy
	 */
	_proxy: null,
	
	/**
	 * API reference
	 * @var	WCF.Action.Scroll
	 */
	_scrollAPI: null,
	
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
		this._pageNo = 0;
		this._userID = userID;
		this._proxy = new WCF.Action.Proxy({
			success: $.proxy(this._success, this)
		});
		
		new WCF.Action.Scroll(300, $.proxy(this._scroll, this));
	},
	
	/**
	 * Loads next entries once users hits the very bottom.
	 * 
	 * @param	WCF.Action.Scroll	api
	 */
	_scroll: function(api) {
		// bind API reference
		this._scrollAPI = api;
		
		// stop event until request was sucessful
		this._scrollAPI.stop();
		
		this._pageNo++;
		this._proxy.setOption('data', {
			actionName: 'load',
			className: 'wcf\\data\\user\\activity\\event\\UserActivityEventAction',
			parameters: {
				data: {
					userID: this._userID,
					pageNo: this._pageNo
				}
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
		if (data.returnValues.template) {
			var $listItems = $('<div>' + data.returnValues.template + '</div>').find('.containerList > li');
			if ($listItems.length) {
				var $recentActivities = $('#recentActivities');
				console.debug($recentActivities.find('li'));
				$listItems.each(function(index, item) {
					$(item).appendTo($recentActivities);
				});
			}
		}
		
		// resume scroll event handler
		if (data.returnValues.hasMoreElements) {
			this._scrollAPI.start();
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
					button.children('img').attr('src', WCF.Icon.get('wcf.icon.remove'));
					button.data('tooltip', WCF.Language.get('wcf.user.button.unfollow'));
				}
				else {
					button.children('img').attr('src', WCF.Icon.get('wcf.icon.add'));
					button.data('tooltip', WCF.Language.get('wcf.user.button.follow'));
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
					button.children('img').attr('src', WCF.Icon.get('wcf.icon.enabled'));
					button.data('tooltip', WCF.Language.get('wcf.user.button.unignore'));
				}
				else {
					button.children('img').attr('src', WCF.Icon.get('wcf.icon.disabled'));
					button.data('tooltip', WCF.Language.get('wcf.user.button.ignore'));
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
	 * id of the object that is currently being subscribed
	 * @var	integer
	 */
	_objectID: 0,
	
	/**
	 * object type of the object that is currently being subscribed
	 * @var	string
	 */
	_objectType: '',
	
	/**
	 * WCF.User.ObjectWatch.Subscribe object.
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
	 * Handles a click on a subscribe button.
	 * 
	 * @param	object		event
	 */
	_click: function(event) {
		var link = $(event.target);
		if (!link.is('a')) {
			link = link.closest('a');
		}
		this._objectID = link.data('objectID');
		this._objectType = link.data('objectType');
		
		this._proxy.setOption('data', {
			'actionName': link.data('subscribed') ? 'unsubscribe' : 'subscribe',
			'className': 'wcf\\data\\user\\object\\watch\\UserObjectWatchAction',
			'parameters': {
				data: {
					objectID: this._objectID,
					objectType: this._objectType
				}
			}
		});
		this._proxy.sendRequest();
	},
	
	/**
	 * Handles the successful subscription.
	 * 
	 * @param	object		data
	 * @param	string		textStatus
	 * @param	jQuery		jqXHR
	 */
	_success: function(data, textStatus, jqXHR) {
		$(this._buttonSelector).each($.proxy(function(index, container) {
			var button = $(container);
			
			if (button.data('objectID') == this._objectID && button.data('objectType') == this._objectType) {
				// toogle icon title
				if (button.data('subscribed')) {
					button.children('img').attr('src', WCF.Icon.get('wcf.icon.bookmark'));
					button.data('tooltip', WCF.Language.get('wcf.user.watchedObjects.subscribe'));
				}
				else {
					button.children('img').attr('src', WCF.Icon.get('wcf.icon.bookmark.delete'));
					button.data('tooltip', WCF.Language.get('wcf.user.watchedObjects.unsubscribe'));
				}
				
				button.data('subscribed', !button.data('subscribed'));
				
				return false;
			}
		}, this));
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

/**
 * Loads watched objects for user panel.
 * 
 * @see	WCF.UserPanel
 */
WCF.User.ObjectWatch.UserPanel = WCF.UserPanel.extend({
	/**
	 * link to show all watched objects
	 * @var	string
	 */
	_showAllLink: '',
	
	/**
	 * @see	WCF.UserPanel.init()
	 */
	init: function(showAllLink) {
		this._showAllLink = showAllLink;
		
		this._super('unreadWatchedObjects');
	},
	
	/**
	 * @see	WCF.UserPanel._addDefaultItems()
	 */
	_addDefaultItems: function(dropdownMenu) {
		this._addDivider(dropdownMenu);
		$('<li><a href="' + this._showAllLink + '">' + WCF.Language.get('wcf.user.watchedObjects.showAll') + '</a></li>').appendTo(dropdownMenu);
	},
	
	/**
	 * @see	WCF.UserPanel._getParameters()
	 */
	_getParameters: function() {
		return {
			actionName: 'getUnreadObjects',
			className: 'wcf\\data\\user\\object\\watch\\UserObjectWatchAction'
		};
	}
});
