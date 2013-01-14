{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.accountManagement{/lang} - {lang}wcf.user.usercp{/lang} - {PAGE_TITLE|language}</title>
	
	{include file='headInclude'}
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='userMenuSidebar'}

{include file='header' sidebarOrientation='left'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.user.accountManagement{/lang}</h1>
	</hgroup>
</header>

{include file='userNotice'}

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

<p class="warning">{lang}wcf.user.accountManagement.warning{/lang}</p>

{if $success|isset && $success|count > 0}
	<div class="success">
		{foreach from=$success item=successMessage}
			<p>{lang}{@$successMessage}{/lang}</p>
		{/foreach}
	</div>
{/if}
	
<form method="post" action="{link controller='AccountManagement'}{/link}">
	<div class="container containerPadding marginTop">
		<fieldset>
			<legend><label for="password">{lang}wcf.user.password{/lang}</label></legend>
			
			<dl{if $errorField == 'password'} class="formError"{/if}>
				<dt><label for="password">{lang}wcf.user.password{/lang}</label></dt>
				<dd>
					<input type="password" id="password" name="password" value="" required="required" class="medium" />
					{if $errorField == 'password'}
						<small class="innerError">
							{if $errorType == 'empty'}{lang}wcf.global.form.error.empty{/lang}{/if}
							{if $errorType == 'false'}{lang}wcf.user.password.error.false{/lang}{/if}
						</small>
					{/if}
					<small>{lang}wcf.user.accountManagement.password.description{/lang}</small>
				</dd>
			</dl>
			
			<dl>
				<dd>
					<ul class="buttonList">
						<li><a class="button small" href="{link controller='LostPassword'}{/link}"><img src="{icon}key{/icon}" alt="" class="icon16" /> <span>{lang}wcf.user.lostPassword{/lang}</span></a></li>
					</ul>
				</dd>
			</dl>
		</fieldset>
		
		{if $__wcf->getSession()->getPermission('user.profile.canRename')}
			<fieldset>
				<legend><label for="username">{lang}wcf.user.changeUsername{/lang}</label></legend>
					
				<dl{if $errorField == 'username'} class="formError"{/if}>
					<dt><label for="username">{lang}wcf.user.newUsername{/lang}</label></dt>
					<dd>
						<input type="text" id="username" name="username" value="{$username}" required="required" pattern="^[^,]{ldelim}{REGISTER_USERNAME_MIN_LENGTH},{REGISTER_USERNAME_MAX_LENGTH}}$" class="medium" />
							
						{if $errorField == 'username'}
							<small class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.form.error.empty{/lang}{/if}
								{if $errorType == 'notValid'}{lang}wcf.user.username.error.notValid{/lang}{/if}
								{if $errorType == 'notUnique'}{lang}wcf.user.username.error.notUnique{/lang}{/if}
								{if $errorType == 'alreadyRenamed'}{lang}wcf.user.username.error.alreadyRenamed{/lang}{/if}
							</small>
						{/if}
						{if $renamePeriod > 0}
							<small>{lang}wcf.user.changeUsername.description{/lang}</small>
						{/if}
					</dd>
				</dl>
			</fieldset>
		{/if}
		
		<fieldset>
			<legend><label for="newPassword">{lang}wcf.user.changePassword{/lang}</label></legend>
			
			<dl{if $errorField == 'newPassword'} class="formError"{/if}>
				<dt><label for="newPassword">{lang}wcf.user.newPassword{/lang}</label></dt>
				<dd>
					<input type="password" id="newPassword" name="newPassword" value="{$newPassword}" class="medium" />
						
					{if $errorField == 'newPassword'}
						<small class="innerError">
							{if $errorType == 'empty'}{lang}wcf.global.form.error.empty{/lang}{/if}
							{if $errorType == 'notSecure'}{lang}wcf.user.password.error.notSecure{/lang}{/if}
						</small>
					{/if}
				</dd>
			</dl>
			
			<dl{if $errorField == 'confirmNewPassword'} class="formError"{/if}>
				<dt><label for="confirmNewPassword">{lang}wcf.user.confirmPassword{/lang}</label></dt>
				<dd>
					<input type="password" id="confirmNewPassword" name="confirmNewPassword" value="{$confirmNewPassword}" class="medium" />
						
					{if $errorField == 'confirmNewPassword'}
						<small class="innerError">
							{if $errorType == 'empty'}{lang}wcf.global.form.error.empty{/lang}{/if}
							{if $errorType == 'notEqual'}{lang}wcf.user.confirmPassword.error.notEqual{/lang}{/if}
						</small>
					{/if}
				</dd>
			</dl>
			
		</fieldset>
		
		{if $__wcf->getSession()->getPermission('user.profile.canChangeEmail')}
			<fieldset>
				<legend><label for="email">{lang}wcf.user.changeEmail{/lang}</label></legend>
				
				<dl{if $errorField == 'email'} class="formError"{/if}>
					<dt><label for="email">{lang}wcf.user.newEmail{/lang}</label></dt>
					<dd>
						<input type="email" id="email" name="email" value="{$email}" class="medium" />
							
						{if $errorField == 'email'}
							<small class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.form.error.empty{/lang}{/if}
								{if $errorType == 'notValid'}{lang}wcf.user.email.error.notValid{/lang}{/if}
								{if $errorType == 'notUnique'}{lang}wcf.user.email.error.notUnique{/lang}{/if}
							</small>
						{/if}
					</dd>
				</dl>
				
				<dl{if $errorField == 'confirmEmail'} class="formError"{/if}>
					<dt><label for="confirmEmail">{lang}wcf.user.confirmEmail{/lang}</label></dt>
					<dd>
						<input type="email" id="confirmEmail" name="confirmEmail" value="{$confirmEmail}" class="medium" />
							
						{if $errorField == 'confirmEmail'}
							<small class="innerError">
								{if $errorType == 'notEqual'}{lang}wcf.user.confirmEmail.error.notEqual{/lang}{/if}
							</small>
						{/if}
					</dd>
				</dl>
				
				{if REGISTER_ACTIVATION_METHOD == 1 && $__wcf->getUser()->reactivationCode != 0}
					<dl>
						<dd>
							<ul class="buttonList">
								<li><a class="button small" href="{link controller='EmailActivation'}{/link}"><img src="{icon}check{/icon}" alt="" class="icon16" /> <span>{lang}wcf.user.emailActivation{/lang}</span></a></li>
							</ul>
						</dd>
					</dl>
				{/if}
			</fieldset>
		{/if}
		
		{if $__wcf->getSession()->getPermission('user.profile.canQuit')}
			<fieldset>
				<legend>{lang}wcf.user.quit{/lang}</legend>
				
				{if $quitStarted}
					<dl>
						<dd>
							<label><input type="checkbox" name="quit" value="1" {if $quit == 1}checked="checked" {/if}/> {lang}wcf.user.quit.cancel{/lang}</label>
						</dd>
					</dl>
				{else}
					<dl>
						<dd>
							<label><input type="checkbox" name="quit" value="1" {if $quit == 1}checked="checked" {/if}/> {lang}wcf.user.quit.sure{/lang}</label>
							<small>{lang}wcf.user.quit.description{/lang}</small>
						</dd>
					</dl>
				{/if}
			</fieldset>
		{/if}
		
		{event name='fieldsets'}
	</div>
	
	<div class="formSubmit">
		{@SECURITY_TOKEN_INPUT_TAG}
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
	</div>
</form>

{include file='footer'}

</body>
</html>
