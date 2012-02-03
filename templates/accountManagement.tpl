{include file="documentHeader"}

<head>
	<title>{lang}wcf.user.accountManagement.title{/lang} - {lang}wcf.user.usercp{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	
	{include file='headInclude' sandbox=false}
</head>

<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{include file='header' sandbox=false}

{capture append=userMessages}
	{if $errorField}
		<p class="wcf-error">{lang}wcf.global.form.error{/lang}</p>
	{/if}
		
	{if $success|isset && $success|count > 0}
		<div class="wcf-success">
			{foreach from=$success item=successMessage}
				<p>{lang}{@$successMessage}{/lang}</p>
			{/foreach}
		</div>
	{/if}
{/capture}
	
{* {include file="userCPHeader"} *}
	
<form method="post" action="index.php?form=AccountManagement">
	<div class="wcf-border wcf-tabMenuContent">
		<div>
			<hgroup class="wcf-subHeading">
				<h1>{lang}wcf.user.accountManagement.title{/lang}</h1>
			</hgroup>
							
			<p class="wcf-warning">{lang}wcf.user.accountManagement.edit.warning{/lang}</p>
				
			<dl{if $errorField == 'password'} class="wcf-formError"{/if}>
				<dt><label for="password">{lang}wcf.user.accountManagement.password{/lang}</label></dt>
				<dd>
					<input type="password" id="password" name="password" value="" class="medium" />
					{if $errorField == 'password'}
						<small class="wcf-innerError">
							{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							{if $errorType == 'false'}{lang}wcf.user.login.error.password.false{/lang}{/if}
						</small>
					{/if}
					<small>{lang}wcf.user.accountManagement.password.description{/lang}</small>
				</dd>
			</dl>
				
			{if $canChangeUsername}
				<fieldset>
					<legend><label for="username">{lang}wcf.user.rename.title{/lang}</label></legend>
						
					<dl{if $errorField == 'username'} class="wcf-formError"{/if}>
						<dt><label for="username">{lang}wcf.user.username{/lang}</label></dt>
						<dd>
							<input type="text" id="username" name="username" value="{$username}" class="medium" />
								
							{if $errorField == 'username'}
								<small class="wcf-innerError">
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
					
				<dl{if $errorField == 'newPassword'} class="wcf-formError"{/if}>
					<dt><label for="newPassword">{lang}wcf.user.passwordChange.newPassword{/lang}</label></dt>
					<dd>
						<input type="password" id="newPassword" name="newPassword" value="{$newPassword}" class="medium" />
							
						{if $errorField == 'newPassword'}
							<small class="wcf-innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
								{if $errorType == 'notSecure'}{lang}wcf.user.error.password.notSecure{/lang}{/if}
							</small>
						{/if}
					</dd>
				</dl>
					
				<dl{if $errorField == 'confirmNewPassword'} class="wcf-formError"{/if}>
					<dt><label for="confirmNewPassword">{lang}wcf.user.passwordChange.confirmNewPassword{/lang}</label></dt>
					<dd>
						<input type="password" id="confirmNewPassword" name="confirmNewPassword" value="{$confirmNewPassword}" class="medium" />
							
						{if $errorField == 'confirmNewPassword'}
							<small class="wcf-innerError">
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
						
					<dl{if $errorField == 'email'} class="wcf-formError"{/if}>
						<dt><label for="email">{lang}wcf.user.email{/lang}</label></dt>
						<dd>
							<input type="email" id="email" name="email" value="{$email}" class="medium" />
								
							{if $errorField == 'email'}
								<small class="wcf-innerError">
									{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
									{if $errorType == 'notValid'}{lang}wcf.user.error.email.notValid{/lang}{/if}
									{if $errorType == 'notUnique'}{lang}wcf.user.error.email.notUnique{/lang}{/if}
									{if $errorType == 'notChanged'}{lang}wcf.user.emailChange.error.email.notChanged{/lang}{/if}
								</small>
							{/if}
						</dd>
					</dl>
						
					<dl{if $errorField == 'confirmEmail'} class="wcf-formError"{/if}>
						<dt><label for="confirmEmail">{lang}wcf.user.confirmEmail{/lang}</label></dt>
						<dd>
							<input type="email" id="confirmEmail" name="confirmEmail" value="{$confirmEmail}" class="medium" />
								
							{if $errorField == 'confirmEmail'}
								<small class="wcf-innerError">
									{if $errorType == 'notEqual'}{lang}wcf.user.error.confirmEmail.notEqual{/lang}{/if}
								</small>
							{/if}
						</dd>
					</dl>
						
					{if REGISTER_ACTIVATION_METHOD == 1 && $__wcf->getUser()->reactivationCode != 0}
						<div class="formElement"><!-- ToDo: Def.List -->
							<div class="formField">
								<ul class="formOptionsLong">
									<li><img src="{icon}email1.svg{/icon}" alt="" /> <a href="index.php?page=Register&amp;action=reenable{@SID_ARG_2ND}">{lang}wcf.user.emailChange.reactivation.title{/lang}</a></li>
								</ul>
							</div>
						</div>
					{/if}
				</fieldset>
			{/if}
				
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
				
			{if $additionalFields|isset}{@$additionalFields}{/if}
		</div>
	</div>
		
	<div class="wcf-formSubmit">
		{@SID_INPUT_TAG}
		{@SECURITY_TOKEN_INPUT_TAG}
		<input type="reset" value="{lang}wcf.global.button.reset{/lang}" accesskey="r" />
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
	</div>
</form>

{include file='footer' sandbox=false}

</body>
</html>
