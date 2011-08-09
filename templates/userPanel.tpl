{if $__wcf->user->userID}
	{* avatar *}

	{* user note *}
	<p>Welcome {$__wcf->user->username}!</p>

	{* functions (login, registration) *}
	<ul>
		<li title="You have {#$__wcf->getUserNotificationHandler()->getNotificationCount()} outstanding notifications!">{#$__wcf->getUserNotificationHandler()->getNotificationCount()} notifications</li>
		
		<li><a href="{link}index.php?action=Logout&t={@SECURITY_TOKEN}{/link}" onclick="return confirm('Are you sure?')">Logout</a></li>
	</ul>
{else}
	{* user note *}
	<p>Welcome guest!</p>
	
	{if !$__disableLoginLink|isset}
		{* login box *}
		<div id="loginBox" style="display:none; border: 1px solid #000">
			<form method="post" action="{link}index.php?form=Login{/link}">
				<dl>
					<dt><label for="username">Username or email address</label></dt>
					<dd><input type="text" id="username" name="username" value="" /></dd>
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
					<dd><input type="password" id="password" name="password" value="" /></dd>
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