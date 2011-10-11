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
	<!-- user Menu -->
	<li id="userMenu" class="userMenu">
		<span class="dropdownCaption">{lang}wcf.user.userNote{/lang}</span>
		<ul class="dropdown">
			<li><a href="{link}index.php?action=Logout&amp;t={@SECURITY_TOKEN}{/link}" onclick="return confirm('{lang}wcf.user.logout.sure{/lang}')">{lang}wcf.user.logout{/lang}</a></li>
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
	<li>Hello Guest!</li>
	
	{if !$__disableLoginLink|isset}
		<!-- login box -->
		<li>
			<span class="loginBox dropdownCaption"><a id="loginLink" href="{link}index.php?form=Login{/link}" title="Login or Register">Login or Register</a></span>
			<div id="loginBox" class="" style="display: none;>
				<form method="post" action="{link}index.php?form=Login{/link}">
					<dl>
						<dt><label for="username">User-name or e-mail address</label></dt>
						<dd><input type="text" id="username" name="username" value="" required="required=" class="medium" /></dd>
					</dl>
					
					<dl>
						<dt>Do you have an account?</dt>
						<dd><label><input type="radio" name="action" value="register" /> No, I am a new user.</label></dd>
						<dd><label><input type="radio" name="action" value="login" checked="checked" /> Yes, my password is:</label></dd>
						
					</dl>
					
					<dl>
						<dt><label for="password">Password</label></dt>
						<dd><input type="password" id="password" name="password" value="" class="medium" /></dd>
					</dl>
					
					<dl>
						<dt class="reversed"><label for="useCookies">Stay logged in</label></dt>
						<dd><input type="checkbox" id="useCookies" name="useCookies" value="1" /></dd>
					</dl>
					
					<div class="formSubmit">
						<input type="reset" value="{lang}wcf.global.button.reset{/lang}" accesskey="r" />
						<input type="submit" name="submitButton" value="Login" accesskey="s" />
					</div>
				</form>
			</div>
		
			<script type="text/javascript">
				//<![CDATA[
				$(function() {
					$('#loginLink').click(function(event) {
						WCF.showDialog('loginBox');
						return false;
					});
				});
				//]]>
			</script>
		</li>
		
	{/if}
{/if}
