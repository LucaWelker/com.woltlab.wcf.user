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
WCF.User.Profile.Follow = function (userID, following) { this.init(userID, following);  };
WCF.User.Profile.Follow.prototype = {
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
};

/**
 * Provides methods to manage ignored users.
 * 
 * @param	integer		userID
 * @param	boolean		isIgnoredUser
 */
WCF.User.Profile.IgnoreUser = function(userID, isIgnoredUser) { this.init(userID, isIgnoredUser); };
WCF.User.Profile.IgnoreUser.prototype = {
	/**
	 * ignore button
	 * @var	jQuery
	 */
	_ignoreButton: null,
	
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
		
		// bind event
		this._ignoreButton = $('#ignoreUser').click($.proxy(this._click, this));
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
		this._ignoreButton.text(WCF.Language.get('wcf.user.profile.' + (this._isIgnoredUser ? 'un' : '') + 'ignoreUser'));
	}
};

/**
 * Provides methods to load tab menu content upon request.
 */
WCF.User.Profile.TabMenu = function(userID) { this.init(userID); };
WCF.User.Profile.TabMenu.prototype = {
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
};

WCF.User.Profile.Editor = Class.extend({
	_buttons: { },

	_className: '',

	_originalTab: 0,

	_tabMenu: null,

	init: function() {
		// create buttons
		this._buttons = {
			beginEdit: $('<button id="beginEdit">edit profile</button>').appendTo($('#profileButtonContainer')),
			finalizeEdit: $('<button id="finalizeEdit">end edit</button>').hide().appendTo($('#profileButtonContainer'))
		};

		// get tab menu
		this._tabMenu = $('#profileContent').data('wcfTabs');

		// bind event listener
		this._buttons.beginEdit.click($.proxy(this._click, this));
		this._buttons.finalizeEdit.click($.proxy(this._click, this));
	},

	_click: function(event) {
		var $buttonID = $(event.currentTarget).wcfIdentify();

		switch ($buttonID) {
			case 'beginEdit':
				this.beginEdit();
			break;

			case 'finalizeEdit':
				this.finalizeEdit();
			break;
		}
	},

	beginEdit: function() {
		// toggle buttons
		this._toggleButtons();
		
		// store original tab
		this._originalTab = this._tabMenu.getCurrentIndex();
		
		// select overview tab
		this._tabMenu.select('wcf_user_profile_menu_overview');
	},

	finalizeEdit: function() {
		// restore original tab
		this._tabMenu.select(this._originalTab);
	},

	_toggleButtons: function() {
		this._buttons.beginEdit.toggle();
		this._buttons.finalizeEdit.toggle();
	}
});
