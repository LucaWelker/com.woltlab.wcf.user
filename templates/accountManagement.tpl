{include file="documentHeader"}

<head>
	<title>{lang}wcf.user.accountManagement.title{/lang} - {lang}wcf.user.usercp{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	
	{include file='headInclude' sandbox=false}
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='profileEditSidebar' sandbox=false}

{include file='header' sandbox=false sidebarOrientation='left'}

<header class="box48 boxHeadline">
	<img src="{icon size='L'}user1{/icon}" alt="" class="icon48" />
	<hgroup>
		<h1>{lang}wcf.user.accountManagement.title{/lang}</h1>
	</hgroup>
</header>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

<p class="warning">{lang}wcf.user.accountManagement.edit.warning{/lang}</p>
		
<form method="post" action="{link controller='AccountManagement'}{/link}">
	<div class="container containerPadding marginTop shadow">
		<fieldset>
			<legend><label for="password">{lang}wcf.user.accountManagement.password{/lang}</label></legend>
				
			<dl{if $errorField == 'password'} class="formError"{/if}>
				<dt><label for="password">{lang}wcf.user.accountManagement.password{/lang}</label></dt>
				<dd>
					<input type="password" id="password" name="password" value="" required="true" class="medium" />
					{if $errorField == 'password'}
						<small class="innerError">
							{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							{if $errorType == 'false'}{lang}wcf.user.login.error.password.false{/lang}{/if}
						</small>
					{/if}
					<small>{lang}wcf.user.accountManagement.password.description{/lang}</small>
				</dd>
			</dl>
		</fieldset>
			
		{if $canChangeUsername}
			<fieldset>
				<legend><label for="username">{lang}wcf.user.rename.title{/lang}</label></legend>
					
				<dl{if $errorField == 'username'} class="formError"{/if}>
					<dt><label for="username">{lang}wcf.user.username{/lang}</label></dt>
					<dd>
						<input type="text" id="username" name="username" value="{$username}" class="medium" />
							
						{if $errorField == 'username'}
							<small class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
								{if $errorType == 'notValid'}{lang}wcf.user.error.username.notValid{/lang}{/if}
								{if $errorType == 'notUnique'}{lang}wcf.user.error.username.notUnique{/lang}{/if}
								{if $errorType == 'notChanged'}{lang}wcf.user.rename.error.username.notChanged{/lang}{/if}
							</small>
						{/if}
						{if $renamePeriod > 0}
							<small>{lang}wcf.user.rename.description{/lang}</small>
						{/if}
					</dd>
				</dl>
			</fieldset>
		{/if}
			
		<fieldset>
			<legend><label for="newPassword">{lang}wcf.user.passwordChange.title{/lang}</label></legend>
				
			<dl{if $errorField == 'newPassword'} class="formError"{/if}>
				<dt><label for="newPassword">{lang}wcf.user.passwordChange.newPassword{/lang}</label></dt>
				<dd>
					<input type="password" id="newPassword" name="newPassword" value="{$newPassword}" class="medium" />
						
					{if $errorField == 'newPassword'}
						<small class="innerError">
							{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							{if $errorType == 'notSecure'}{lang}wcf.user.error.password.notSecure{/lang}{/if}
						</small>
					{/if}
				</dd>
			</dl>
				
			<dl{if $errorField == 'confirmNewPassword'} class="formError"{/if}>
				<dt><label for="confirmNewPassword">{lang}wcf.user.passwordChange.confirmNewPassword{/lang}</label></dt>
				<dd>
					<input type="password" id="confirmNewPassword" name="confirmNewPassword" value="{$confirmNewPassword}" class="medium" />
						
					{if $errorField == 'confirmNewPassword'}
						<small class="innerError">
							{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							{if $errorType == 'notEqual'}{lang}wcf.user.error.confirmPassword.notEqual{/lang}{/if}
						</small>
					{/if}
				</dd>
			</dl>
			
		</fieldset>
			
		{if $__wcf->getSession()->getPermission('user.profile.canChangeEmail')}
			<fieldset>
				<legend><label for="email">{lang}wcf.user.emailChange.title{/lang}</label></legend>
					
				<dl{if $errorField == 'email'} class="formError"{/if}>
					<dt><label for="email">{lang}wcf.user.email{/lang}</label></dt>
					<dd>
						<input type="email" id="email" name="email" value="{$email}" class="medium" />
							
						{if $errorField == 'email'}
							<small class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
								{if $errorType == 'notValid'}{lang}wcf.user.error.email.notValid{/lang}{/if}
								{if $errorType == 'notUnique'}{lang}wcf.user.error.email.notUnique{/lang}{/if}
								{if $errorType == 'notChanged'}{lang}wcf.user.emailChange.error.email.notChanged{/lang}{/if}
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
								{if $errorType == 'notEqual'}{lang}wcf.user.error.confirmEmail.notEqual{/lang}{/if}
							</small>
						{/if}
					</dd>
				</dl>
					
				{*TODO*}
				{if REGISTER_ACTIVATION_METHOD == 1 && $__wcf->getUser()->reactivationCode != 0}
					<dl>
						<dd>
							<a href="index.php?page=Register&amp;action=reenable{@SID_ARG_2ND}">{lang}wcf.user.emailChange.reactivation.title{/lang}</a>
						</dd>
					</dl>
				{/if}
			</fieldset>
		{/if}
		
		{*TODO*}		
		{if $__wcf->getSession()->getPermission('user.profile.canQuit')}
			<fieldset>
				<legend>{lang}wcf.user.quit.title{/lang}</legend>
				
				{if $quitStarted}
					<div class="formElement"><!-- ToDo: Def.List -->
						<div class="formField">
							<label><input type="checkbox" name="cancelQuit" value="1" {if $cancelQuit == 1}checked="checked" {/if}/> {lang}wcf.user.quit.cancel{/lang}</label>
						</div>
					</div>
				{else}
					<div class="formElement"><!-- ToDo: Def.List -->
						<div class="formField">
							<label><input type="checkbox" name="quit" value="1" {if $quit == 1}checked="checked" {/if}/> {lang}wcf.user.quit{/lang}</label>
						</div>
						<div class="formFieldDesc">
							<p>{lang}wcf.user.quit.description{/lang}</p>
						</div>
					</div>
				{/if}
			</fieldset>
		{/if}
	</div>
		
	<div class="formSubmit">
		{@SECURITY_TOKEN_INPUT_TAG}
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
	</div>
</form>

{include file='footer' sandbox=false}

</body>
</html>
