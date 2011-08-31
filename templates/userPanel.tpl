{*
	TODO: This css should be part of com.woltlab.wcf.notification (w/o parts related to userMenu of course!)

	CSS should be moved to a user css-file, whereas many parts may be left out except for the following ones:
	 - [.scrollableContainer] Don't touch!
	 - [.scrollableItems] Keep the inflated width, you may lower its number but it must be incredible bloated
	 - [.scrollableItems > div] Must be a fixed-width float, otherwise it would break the scrollable class
	   from jQueryTools. Do NOT use css-tables or [display: inline-block] as it won't work with the whole magic

	A quick note on element nesting:
	 - [.userNotificationContainer] Container which holds all elements (must be positioned absolute, values
	   for top and left are determined during runtime using JavaScript)
	 - [#userNotificationContainer] This is a pretty nasty pitfall, as #userNotificationContainer (equal to
	   .scrollableContainer references to the direct descendant of .userNotificationContainer (same name to
	   preserve logic within JavaScript)

											-- Alexander (2011-08-11)
*}

<style type="text/css">
	* {
		font-family: Calibri;
		font-size: 10pt;
		margin: 0;
		padding: 0;
	}

	ul#userMenu {
		margin: 0;
		padding: 0;
	}

	ul#userMenu > li {
		background-color: rgb(224, 224, 224);
		border-radius: 3px;
		box-shadow: 0 0 5px rgb(151, 151, 151);
		display: inline-block;
		margin: 0 3px 0 3px;
		padding: 7px;
	}

	div.userNotificationContainer {
		background-color: rgba(224, 224, 224, .9);
		border: 1px solid rgb(192, 192, 192);
		position: absolute;
		width: 250px;
	}

	div.scrollableContainer {
		position: relative;
		overflow: hidden;
		width: 250px;
	}

	div.scrollableContainer div.scrollableItems {
		position: relative;
		width: 20000em;
	}

	div.scrollableContainer div.scrollableItems > div {
		border-right: 1px solid rgb(192, 192, 192);
		float: left;
		width: 250px;
	}

	div.scrollableContainer {
		font-family: Calibri;
		font-size: 90%;
	}

	div.scrollableContainer > div:first-child ul {
		margin: 0;
		padding: 0;
	}

	div.scrollableContainer > div:first-child li {
		border-top: 1px solid rgb(192, 192, 192);
		padding: 7px;
	}

	div.scrollableContainer > div:first-child li:first-child {
		border-top-width: 0;
	}

	div.scrollableContainer > div:first-child p {
		padding: 7px;
	}

	.userNotificationDetails {
		padding: 7px;
		width: 236px;
	}

	.userNotificationDetails hgroup {
		display: table;
		margin-bottom: 7px;
		width: 100%;
	}

	.userNotificationDetails .row {
		display: table-row;
	}

	.userNotificationDetails .row div {
		display: table-cell;
		text-align: center;
		vertical-align: middle;
	}

	.userNotificationDetails .row div:first-child {
		width: 64px;
	}

	.userNotificationDetails .row div:last-child {
		padding-left: 7px;
	}

	.userNotificationDetails .avatar img {
		height: 64px;
		width: 64px;
	}

	.userNotificationDetails h1 {
		font-size: 110%;
	}

	.userNotificationDetails section {
		border-top: 1px solid rgb(192, 192, 192);
		padding-top: 7px;
	}

	.userNotificationDetails nav {
		border-top: 1px solid rgb(192, 192, 192);
		margin-top: 7px;
		padding-top: 7px;
		text-align: center;
	}

	.userNotificationDetails ul {
		display: inline-block;
		list-style-type: none;
	}

	.userNotificationDetails li {
		background-image: -o-linear-gradient(top, rgb(224, 224, 224), rgb(192, 192, 192));
		border: 1px solid rgb(192, 192, 192) !important;
		cursor: pointer;
		display: inline-block;
		padding: 3px;
	}

	.userNotificationDetails li:hover {
		background-image: -o-linear-gradient(top, rgb(192, 192, 192), rgb(224, 224, 224));
	}

	#userNotificationDetailsLoading {
		background-color: rgba(255, 255, 255, 0.6);
		background-image: url('{@RELATIVE_WCF_DIR}icon/ajax-loader.gif');
		background-position: center center;
		background-repeat: no-repeat;
		position: absolute;
	}
</style>

<!-- ToDo: It would be great if everything (every element of the "user panel") could be placed into one large list with sub-lists, with all elements in it. Even if registered or not, all should be in one list! Should be easy to separate things with "if". That would keep the code simpler and more logical, and handling with css more easy and uniform. -->

{if $__wcf->user->userID}
	
	{* functions (login, registration) *}
	<ul id="userMenu"><!-- renamed! -->
		<li><span>{* include avatar here *}</span> Hello {$__wcf->user->username}!</li>
		<li><a href="{link}index.php?action=Logout&amp;t={@SECURITY_TOKEN}{/link}" onclick="return confirm('Are you sure?')">Logout</a></li>
		<li id="userNotifications" data-count="{@$__wcf->getUserNotificationHandler()->getNotificationCount()}">{#$__wcf->getUserNotificationHandler()->getNotificationCount()}</li>
	</ul>
	
	{* TODO: This should be part of com.woltlab.wcf.notification instead! *}
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/WCF.Notification.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			WCF.Language.addObject({
				'wcf.user.notification.noNotifications': '{lang}wcf.user.notification.noNotifications{/lang}',
				'wcf.user.notification.count': '{lang}wcf.user.notification.count{/lang}'
			});
			new WCF.Notification.Handler();
		});
		//]]>
	</script>
	
	<pre id="log"></pre>
{else}
	{* user note *}
	<p>Hello guest!</p><!-- This should also be inside a list element! -->
	
	{if !$__disableLoginLink|isset}
		{* login box *}
		<div id="loginBox" style="display: none; border: 1px solid #000">
			<form method="post" action="{link}index.php?form=Login{/link}">
				<dl>
					<dt><label for="username">Username or email address</label></dt>
					<dd><input type="text" id="username" name="username" value="" required="true" class="short" /></dd>
				</dl>
				
				<dl>
					<dt>Do you have an account?</dt>
					<dd>
						<label><input type="radio" name="action" value="register" /> No, I am a new user.</label>
						<label><input type="radio" name="action" value="login" checked="checked" /> Yes, my password is:</label>
					</dd>
				</dl>
				
				<dl>
					<dt><label for="password">Password</label></dt>
					<dd><input type="password" id="password" name="password" value="" class="short" /></dd>
				</dl>
				
				<dl>
					<dt><label for="useCookies">Stay logged in</label></dt>
					<dd><input type="checkbox" id="useCookies" name="useCookies" value="1" /></dd>
				</dl>
				
				<input type="submit" value="Login" />
			</form>
		</div>
		
		{* functions (login, registration) *}
		<ul>
			<li><a id="loginLink" href="{link}index.php?form=Login{/link}">Login or Register</a></li>
		</ul>
		
		<script type="text/javascript">
			//<![CDATA[
			$(function() {
				$('#loginLink').click(function(event) {
					if ($('#loginBox').is(':visible')) {
						$('#loginBox').hide('slow');
					}
					else {
						$('#loginBox').show('slow');
					}
					return false;
				});
			});
			//]]>
		</script>
	{/if}
{/if}
