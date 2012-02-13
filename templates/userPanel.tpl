{if $__wcf->user->userID}
	<!-- user menu -->
	<li id="userMenu" class="wcf-userMenu">
		<span class="wcf-dropdownCaption userAvatar">{if $__wcf->getUserProfileHandler()->getAvatar()}{@$__wcf->getUserProfileHandler()->getAvatar()->getImageTag(16)}{/if} {lang}wcf.user.userNote{/lang}</span>
		<ul class="wcf-dropdown">
			<li><a href="{link controller='User' object=$__wcf->user}{/link}">My Profile</a></li>
			<li><a href="{link controller='ProfileEdit'}{/link}">Edit Profile</a></li>
			<li><a href="{link controller='Logout'}t={@SECURITY_TOKEN}{/link}" onclick="return confirm('{lang}wcf.user.logout.sure{/lang}')">{lang}wcf.user.logout{/lang}</a></li>
		</ul>
	</li>
{else}
	{if !$__disableLoginLink|isset}
		<!-- login box -->
		<li>
			<span class="loginBox wcf-dropdownCaption"><a id="loginLink" href="{link controller='Login'}{/link}">{lang}wcf.user.loginOrRegister{/lang}</a></span>
			<div id="loginForm" style="display: none;">
				<form method="post" action="{link controller='Login'}{/link}">
					<dl>
						<dt><label for="username">{lang}wcf.user.usernameOrEmail{/lang}</label></dt>
						<dd><input type="text" id="username" name="username" value="" required="required" autofocus="autofocus" class="medium" /></dd>
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
						<dd><input type="checkbox" id="useCookies" name="useCookies" value="1" checked="checked" /></dd>
					</dl>
					
					<div class="wcf-formSubmit">
						<input type="reset" value="{lang}wcf.global.button.reset{/lang}" accesskey="r" />
						<input type="submit" id="loginSubmitButton" name="submitButton" value="{lang}wcf.user.button.login{/lang}" accesskey="s" />
						<input type="hidden" name="url" value="{$__wcf->session->requestURI}" />
					</div>
				</form>
			</div>
			
			<script type="text/javascript" src="{@$__wcf->getPath('wcf')}js/WCF.User.js"></script>
			<script type="text/javascript">
				//<![CDATA[
				$(function() {
					WCF.Language.addObject({
						'wcf.user.button.login': '{lang}wcf.user.button.login{/lang}',
						'wcf.user.button.register': '{lang}wcf.user.button.register{/lang}',
						'wcf.user.login': '{lang}wcf.user.login{/lang}'
					});
					new WCF.User.Login(true);
				});
				//]]>
			</script>
		</li>
	{/if}
{/if}

{event name='menuItems'}
