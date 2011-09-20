/**
 * UserProfile namespace
 */
WCF.User.Profile = {};

/**
 * UserProfile friend system.
 * 
 * @param	integer		userID
 * @param	boolean		isFriend
 * @param	boolean		isRequested
 * @param	boolean		isRequesting
 */
WCF.User.Profile.Friend = function(userID, isFriend, isRequested, isRequesting) { this.init(userID, isFriend, isRequested, isRequesting); };
WCF.User.Profile.Friend.prototype = {
	/**
	 * list of action buttons
	 * @var	object
	 */
	_buttons: {},
	
	/**
	 * user is already a friend
	 * @var	boolean
	 */
	_isFriend: false,
	
	/**
	 * user requested friendship
	 * @var	boolean
	 */
	_isRequested: false,
	
	/**
	 * requesting friendship with user
	 * @var	boolean
	 */
	_isRequesting: false,
	
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
	 * Creates a new instance of UserProfile friend system.
	 * 
	 * @param	integer		userID
	 * @param	boolean		isFriend
	 * @param	boolean		isRequested
	 * @param	boolean		isRequesting
	 */
	init: function(userID, isFriend, isRequested, isRequesting) {
		this._userID = userID;
		this._isFriend = isFriend;
		this._isRequested = isRequested;
		this._isRequesting = isRequesting;
		
		this._proxy = new WCF.Action.Proxy({
			success: $.proxy(this._success, this)
		});
		
		this._createButtons();
	},
	
	/**
	 * Creates buttons for friendship management.
	 */
	_createButtons: function() {
		this._buttons = {
			accept: $('<button id="acceptRequest">' + WCF.Language.get('wcf.user.profile.friend.acceptRequest') + '</button>').data('action', 'accept').hide(),
			cancel: $('<button id="cancelRequest">' + WCF.Language.get('wcf.user.profile.friend.cancelRequest') + '</button>').data('action', 'cancel').hide(),
			create: $('<button id="createRequest">' + WCF.Language.get('wcf.user.profile.friend.createRequest') + '</button>').data('action', 'create').hide(),
			deleteFriend: $('<button id="deleteFriend">' + WCF.Language.get('wcf.user.profile.friend.deleteFriend') + '</button>').data('action', 'delete').hide(),
			ignore: $('<button id="ignoreRequest">' + WCF.Language.get('wcf.user.profile.friend.ignoreRequest') + '</button>').data('action', 'ignore').hide(),
			reject: $('<button id="rejectRequest">' + WCF.Language.get('wcf.user.profile.friend.rejectRequest') + '</button>').data('action', 'reject').hide()
		}
		
		// insert buttons and bind listener
		for (var $type in this._buttons) {
			this._buttons[$type].appendTo('div#profileButtonContainer').click($.proxy(this._click, this));
		}
		
		// toggle displayed buttons
		this._setActiveButtons();
	},
	
	/**
	 * Handles button clicks.
	 * 
	 * @param	object		event
	 */
	_click: function(event) {
		var $action = $(event.target).data('action');
		
		switch ($action) {
			case 'accept':
			case 'ignore':
			case 'reject':
				var $parameters = {
					data: {
						userID: this._userID
					}
				}
			break;
			
			default:
				var $parameters = {
					data: {
						friendUserID: this._userID
					}
				}
			break;
		}
		
		this._proxy.setOption('data', {
			actionName: $action,
			className: 'wcf\\data\\user\\friend\\request\\UserFriendRequestAction',
			parameters: $parameters
		});
		
		this._proxy.sendRequest();
	},
	
	/**
	 * Updates buttons on success.
	 * 
	 * @param	object		data
	 * @param	string		textStatus
	 * @param	jQuery		jqXHR
	 */
	_success: function(data, textStatus, jqXHR) {
		this._isFriend = data.returnValues.isFriend;
		this._isRequested = data.returnValues.isRequested;
		this._isRequesting = data.returnValues.isRequesting;
		
		this._setActiveButtons();
	},
	
	/**
	 * Sets active buttons.
	 */
	_setActiveButtons: function() {
		// hide all buttons
		for (var $type in this._buttons) {
			this._buttons[$type].hide();
		}
		
		if (this._isFriend) {
			this._buttons.deleteFriend.show();
		}
		else {
			if (this._isRequested) {
				this._buttons.cancel.show();
			}
			else {
				if (this._isRequesting) {
					this._buttons.accept.show();
					this._buttons.ignore.show();
					this._buttons.reject.show();
				}
				else {
					this._buttons.create.show();
				}
			}
		}
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
