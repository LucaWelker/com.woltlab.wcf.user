{if $__wcf->user->userID}
	<!-- user menu -->
	<li id="userMenu" class="dropdown">
		<a class="dropdownToggle framed" data-toggle="userMenu">{if $__wcf->getUserProfileHandler()->getAvatar()}{@$__wcf->getUserProfileHandler()->getAvatar()->getImageTag(24)}{/if} {lang}wcf.user.userNote{/lang}</a>
		<ul class="dropdownMenu">
			<li><a href="{link controller='User' object=$__wcf->user}{/link}" class="box32">
				<div class="framed">{@$__wcf->getUserProfileHandler()->getAvatar()->getImageTag(32)}</div>
				
				<hgroup class="containerHeadline">
					<h1>{$__wcf->user->username}</h1>
					<h2>{lang}wcf.user.myProfile{/lang}</h2>
				</hgroup>
			</a></li>
			<li><a href="{link controller='User' object=$__wcf->user}editOnInit=true#about{/link}">{lang}wcf.user.editProfile{/lang}</a></li>
			<li><a href="{link controller='Settings'}{/link}">{lang}wcf.user.menu.settings{/lang}</a></li>
			{if $__wcf->session->getPermission('admin.general.canUseAcp')}
				<li class="dropdownDivider"></li>
				<li><a href="acp/index.php">ACP</a></li>
			{/if}
			<li class="dropdownDivider"></li>
			<li><a href="{link controller='Logout'}t={@SECURITY_TOKEN}{/link}" onclick="WCF.System.Confirmation.show('{lang}wcf.user.logout.sure{/lang}', $.proxy(function (action) { if (action == 'confirm') window.location.href = $(this).attr('href'); }, this)); return false;">{lang}wcf.user.logout{/lang}</a></li>
		</ul>
	</li>
	
	<!-- user notifications -->
	<li id="userNotifications" class="dropdown" data-count="{@$__wcf->getUserNotificationHandler()->getNotificationCount()}">
		<a class="dropdownToggle" data-toggle="userNotifications">{lang}wcf.user.notification.notifications{/lang} <span class="badge badgeInverse">{#$__wcf->getUserNotificationHandler()->getNotificationCount()}</span></a>
		<div class="dropdownMenu userNotificationContainer">
			<div id="userNotificationContainer" class="scrollableContainer">
				<div class="scrollableItems cleafix">
					<div>
						<p>{lang}wcf.global.loading{/lang}</p>
					</div>
					<div>
						<p>{lang}wcf.global.loading{/lang}</p>
					</div>
				</div>
			</div>
		</div>
	</li>
{else}
	{if !$__disableLoginLink|isset}
		<!-- login box -->
		<li>
			<a id="loginLink" href="{link controller='Login'}{/link}">{lang}wcf.user.loginOrRegister{/lang}</a>
			<div id="loginForm" style="display: none;">
				<form method="post" action="{link controller='Login'}{/link}">
					<fieldset>
						<dl>
							<dt><label for="username">{lang}wcf.user.usernameOrEmail{/lang}</label></dt>
							<dd>
								<input type="text" id="username" name="username" value="" required="required" autofocus="autofocus" class="long" />
							</dd>
						</dl>
						
						<dl>
							<dt>{lang}wcf.user.login.action{/lang}</dt>
							<dd>
								<label><input type="radio" name="action" value="register" /> {lang}wcf.user.login.action.register{/lang}</label>
								<label><input type="radio" name="action" value="login" checked="checked" /> {lang}wcf.user.login.action.login{/lang}</label>
							</dd>
						</dl>
						
						<dl>
							<dt><label for="password">{lang}wcf.user.password{/lang}</label></dt>
							<dd>
								<input type="password" id="password" name="password" value="" class="long" />
							</dd>
						</dl>
						
						<dl>
							<dd><label><input type="checkbox" id="useCookies" name="useCookies" value="1" checked="checked" /> {lang}wcf.user.useCookies{/lang}</label></dd>
						</dl>
						
						<div class="formSubmit">
							<input type="submit" id="loginSubmitButton" name="submitButton" value="{lang}wcf.user.button.login{/lang}" accesskey="s" />
							<input type="hidden" name="url" value="{$__wcf->session->requestURI}" />
						</div>
					</fieldset>
				</form>
			</div>
			
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
