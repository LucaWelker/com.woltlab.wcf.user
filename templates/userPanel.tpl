{*
	TODO: This css should be part of com.woltlab.wcf.notification (w/o parts related to userMenu of course!)
	
	A quick note on element nesting:
	 - [.userNotificationContainer] Container which holds all elements (must be positioned absolute, values
	   for top and left are determined during runtime using JavaScript)
	 - [#userNotificationContainer] This is a pretty nasty pitfall, as #userNotificationContainer (equal to
	   .scrollableContainer references to the direct descendant of .userNotificationContainer (same name to
	   preserve logic within JavaScript)

	-- Alexander (2011-08-11)
*}

{if $__wcf->user->userID}
	{if $__wcf->getUserProfileHandler()->getAvatar()}
		{assign var=__dummy value=$__wcf->getUserProfileHandler()->getAvatar()->setMaxSize(24, 24)}
		<li>{@$__wcf->getUserProfileHandler()->getAvatar()}</li>
	{/if}
	
	<!-- user Menu -->
	<li id="userMenu" class="userMenu">
		<span class="dropdownCaption">{lang}wcf.user.userNote{/lang}</span>
		<ul class="dropdown">
			<li><a href="{link controller='Logout'}t={@SECURITY_TOKEN}{/link}" onclick="return confirm('{lang}wcf.user.logout.sure{/lang}')">{lang}wcf.user.logout{/lang}</a></li>
		</ul>
	</li>
	
	<!-- user notifications -->
	<li id="userNotifications" data-count="{@$__wcf->getUserNotificationHandler()->getNotificationCount()}">{#$__wcf->getUserNotificationHandler()->getNotificationCount()}</li>
		
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
	{if !$__disableLoginLink|isset}
		<!-- login box -->
		<li>
			<span class="loginBox dropdownCaption"><a id="loginLink" href="{link controller='Login'}{/link}">{lang}wcf.user.loginOrRegister{/lang}</a></span>
			<div id="loginBox" class="" style="display: none;">
				<form method="post" action="{link controller='Login'}{/link}">
					<dl>
						<dt><label for="username">{lang}wcf.user.usernameOrEmail{/lang}</label></dt>
						<dd><input type="text" id="username" name="username" value="" required="required" class="medium" /></dd>
					</dl>
					
					<dl>
						<dt>{lang}wcf.user.login.action{/lang}</dt>
						<dd><label><input type="radio" name="action" value="register" /> {lang}wcf.user.login.action.register{/lang}</label></dd>
						<dd><label><input type="radio" name="action" value="login" checked="checked" /> {lang}wcf.user.login.action.login{/lang}</label></dd>
					</dl>
					
					<dl>
						<dt><label for="password">{lang}wcf.user.password{/lang}</label></dt>
						<dd><input type="password" id="password" name="password" value="" class="medium" /></dd>
					</dl>
					
					<dl>
						<dt class="reversed"><label for="useCookies">{lang}wcf.user.useCookies{/lang}</label></dt>
						<dd><input type="checkbox" id="useCookies" name="useCookies" value="1" /></dd>
					</dl>
					
					<div class="formSubmit">
						<input type="reset" value="{lang}wcf.global.button.reset{/lang}" accesskey="r" />
						<input type="submit" id="loginSubmitButton" name="submitButton" value="{lang}wcf.user.button.login{/lang}" accesskey="s" />
					</div>
				</form>
			</div>
		
			<script type="text/javascript">
				//<![CDATA[
				$(function() {
					$('#loginLink').click(function() {
						WCF.showDialog('loginBox', true, {
							title: '{lang}wcf.user.login{/lang}'
						});
						return false;
					});
				});
				//]]>
			</script>
		</li>
	{/if}
{/if}
