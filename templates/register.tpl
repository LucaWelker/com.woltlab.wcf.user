{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.register{/lang} - {PAGE_TITLE|language}</title>
	{include file='headInclude'}
	
	<script type="text/javascript" src="{@$__wcf->getPath()}js/WCF.User.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			WCF.Language.addObject({
				'wcf.global.form.error.empty': '{lang}wcf.global.form.error.empty{/lang}',
				'wcf.user.error.username.notValid': '{lang}wcf.user.error.username.notValid{/lang}',
				'wcf.user.error.username.notUnique': '{lang}wcf.user.error.username.notUnique{/lang}',
				'wcf.user.error.email.notValid' : '{lang}wcf.user.error.email.notValid{/lang}',
				'wcf.user.error.email.notUnique' : '{lang}wcf.user.error.email.notUnique{/lang}',
				'wcf.user.error.confirmEmail.notEqual' : '{lang}wcf.user.error.confirmEmail.notEqual{/lang}',
				'wcf.user.error.password.notSecure' : '{lang}wcf.user.error.password.notSecure{/lang}',
				'wcf.user.error.confirmPassword.notEqual' : '{lang}wcf.user.error.confirmPassword.notEqual{/lang}'
			});
			
			new WCF.User.Registration.Validation.EmailAddress($('#email'), $('#confirmEmail'), null);
			new WCF.User.Registration.Validation.Password($('#password'), $('#confirmPassword'), null);
			new WCF.User.Registration.Validation.Username($('#username', null, {
				minlength: {@REGISTER_USERNAME_MIN_LENGTH},
				maxlength: {@REGISTER_USERNAME_MAX_LENGTH}
			}));
		});
		//]]>
	</script>
</head>

<body id="tpl{$templateName|ucfirst}">
{include file='header' __disableLoginLink=true}

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.user.register{/lang}</h1>
	</hgroup>
</header>

<form method="post" action="{link controller='Register'}{/link}">
	<div class="container containerPadding marginTop shadow">
		<fieldset>
			<legend>{lang}wcf.user.username{/lang}</legend>
		
			<dl>
				<dt{if $errorType.username|isset} class="formError"{/if}>
					<label for="username">{lang}wcf.user.username{/lang}</label>
				</dt>
				<dd>
					<input type="text" id="username" name="username" value="{$username}" required="true" class="medium" />
					{if $errorType.username|isset}
						<small class="innerError">
							{if $errorType.username == 'empty'}{lang}wcf.global.form.error.empty{/lang}{/if}
							{if $errorType.username == 'notValid'}{lang}wcf.user.error.username.notValid{/lang}{/if}
							{if $errorType.username == 'notUnique'}{lang}wcf.user.error.username.notUnique{/lang}{/if}
						</small>
					{/if}
					<small>{lang}wcf.user.username.description{/lang}</small>
				</dd>
			</dl>
		</fieldset>
		
		<fieldset>
			<legend>{lang}wcf.user.email{/lang}</legend>
			
			<dl>
				<dt{if $errorType.email|isset} class="formError"{/if}>
					<label for="email">{lang}wcf.user.email{/lang}</label>
				</dt>
				<dd>	
					<input type="email" id="email" name="email" value="{$email}" required="true" class="medium" />
					{if $errorType.email|isset}
						<small class="innerError">
							{if $errorType.email == 'empty'}{lang}wcf.global.form.error.empty{/lang}{/if}
							{if $errorType.email == 'notValid'}{lang}wcf.user.error.email.notValid{/lang}{/if}
							{if $errorType.email == 'notUnique'}{lang}wcf.user.error.email.notUnique{/lang}{/if}
						</small>
					{/if}
				</dd>
				
				<dt{if $errorType.confirmEmail|isset} class="formError"{/if}>
					<label for="confirmEmail">{lang}wcf.user.confirmEmail{/lang}</label>
				</dt>
				<dd>
					<input type="email" id="confirmEmail" name="confirmEmail" value="{$confirmEmail}" required="true" class="medium" />
					{if $errorType.confirmEmail|isset}
						<small class="innerError">
							{if $errorType.confirmEmail == 'notEqual'}{lang}wcf.user.error.confirmEmail.notEqual{/lang}{/if}
						</small>
					{/if}
				</dd>
			</dl>
		</fieldset>
		
		<fieldset>
			<legend>{lang}wcf.user.password{/lang}</legend>
			
			<dl>
				<dt{if $errorType.password|isset} class="formError"{/if}>
					<label for="password">{lang}wcf.user.password{/lang}</label>
				</dt>
				<dd>
					<input type="password" id="password" name="password" value="{$password}" required="true" class="medium" />
					{if $errorType.password|isset}
						<small class="innerError">
							{if $errorType.password == 'empty'}{lang}wcf.global.form.error.empty{/lang}{/if}
							{if $errorType.password == 'notSecure'}{lang}wcf.user.error.password.notSecure{/lang}{/if}
						</small>
					{/if}
					<small>{lang}wcf.user.password.description{/lang}</small>
				</dd>
				
				<dt{if $errorType.confirmPassword|isset} class="formError"{/if}>
					<label for="confirmPassword">{lang}wcf.user.confirmPassword{/lang}</label>
				</dt>
				<dd>
					<input type="password" id="confirmPassword" name="confirmPassword" value="{$confirmPassword}" required="true" class="medium" />
					{if $errorType.confirmPassword|isset}
						<small class="innerError">
							{if $errorType.confirmPassword == 'notEqual'}{lang}wcf.user.error.confirmPassword.notEqual{/lang}{/if}
						</small>
					{/if}
				</dd>
			</dl>
		</fieldset>
	
		{if $useCaptcha}{include file='recaptcha'}{/if}
	</div>
	
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
 	</div>
</form>

{include file='footer'}

</body>
</html>