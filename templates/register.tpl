{include file='documentHeader'}
<head>
	<title>Register form</title>
	
	{include file='headInclude' sandbox=false}
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/WCF.User.Registration.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			WCF.Language.addObject({
				'wcf.global.error.empty': '{lang}wcf.global.error.empty{/lang}',
				'wcf.user.error.username.notValid': '{lang}wcf.user.error.username.notValid{/lang}',
				'wcf.user.error.username.notUnique': '{lang}wcf.user.error.username.notUnique{/lang}',
				'wcf.user.error.email.notValid' : '{lang}wcf.user.error.email.notValid{/lang}',
				'wcf.user.error.email.notUnique' : '{lang}wcf.user.error.email.notUnique{/lang}',
				'wcf.user.error.confirmEmail.notEqual' : '{lang}wcf.user.error.confirmEmail.notEqual{/lang}',
				'wcf.user.error.password.notSecure' : '{lang}wcf.user.error.password.notSecure{/lang}',
				'wcf.user.error.confirmPassword.notEqual' : '{lang}wcf.user.error.confirmPassword.notEqual{/lang}'
			});
		});
		//]]>
	</script>
	<style type="text/css">
		.formError, .innerError {
			color: red;
		}
		.formSuccess {
			color: green;
		}
	</style>
</head>

<body>

{include file='header' sandbox=false __disableLoginLink=true}

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

<form method="post" action="{link}index.php?form=Register{/link}">
	<dl>
		<dt{if $errorType.username|isset} class="formError"{/if}><label for="username">{lang}wcf.user.username{/lang}</label></dt>
		<dd>
			<input type="text" id="username" name="username" value="{$username}" class="medium" />
			{if $errorType.username|isset}
				<small class="innerError">
					{if $errorType.username == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
					{if $errorType.username == 'notValid'}{lang}wcf.user.error.username.notValid{/lang}{/if}
					{if $errorType.username == 'notUnique'}{lang}wcf.user.error.username.notUnique{/lang}{/if}
				</small>
			{/if}
			<small>{lang}wcf.user.username.description{/lang}</small>
		</dd>
	</dl>
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			new WCF.User.Registration.ValidateUsername($('#username'), {
				minlength: {@REGISTER_USERNAME_MIN_LENGTH},
				maxlength: {@REGISTER_USERNAME_MAX_LENGTH}
			});
		});
		//]]>
	</script>
	
	<fieldset>
		<legend>{lang}wcf.user.email{/lang}</legend>
		
		<dl>
			<dt{if $errorType.email|isset} class="formError"{/if}><label for="email">{lang}wcf.user.email{/lang}</label></dt>
			<dd>	
				<input type="email" id="email" name="email" value="{$email}" class="medium" />
				{if $errorType.email|isset}
					<small class="innerError">
						{if $errorType.email == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
						{if $errorType.email == 'notValid'}{lang}wcf.user.error.email.notValid{/lang}{/if}
						{if $errorType.email == 'notUnique'}{lang}wcf.user.error.email.notUnique{/lang}{/if}
					</small>
				{/if}
				<small>{lang}wcf.user.email.description{/lang}</small>
			</dd>
			
			<dt{if $errorType.confirmEmail|isset} class="formError"{/if}><label for="confirmEmail">{lang}wcf.user.confirmEmail{/lang}</label></dt>
			<dd>
				<input type="email" id="confirmEmail" name="confirmEmail" value="{$confirmEmail}" class="medium" />
				{if $errorType.confirmEmail|isset}
					<small class="innerError">
						{if $errorType.confirmEmail == 'notEqual'}{lang}wcf.user.error.confirmEmail.notEqual{/lang}{/if}
					</small>
				{/if}
				<small>{lang}wcf.user.confirmEmail.description{/lang}</small>
			</dd>
		</dl>
	</fieldset>
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			new WCF.User.Registration.ValidateEmailAddress($('#email'), $('#confirmEmail'));
		});
		//]]>
	</script>
	
	<fieldset>
		<legend>{lang}wcf.user.password{/lang}</legend>
		
		<dl>
			<dt{if $errorType.password|isset} class="formError"{/if}><label for="password">{lang}wcf.user.password{/lang}</label></dt>
			<dd>
				<input type="password" id="password" name="password" value="{$password}" class="medium" />
				{if $errorType.password|isset}
					<small class="innerError">
						{if $errorType.password == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
						{if $errorType.password == 'notSecure'}{lang}wcf.user.error.password.notSecure{/lang}{/if}
					</small>
				{/if}
				<small>{lang}wcf.user.password.description{/lang}</small>
			</dd>
			
			<dt{if $errorType.confirmPassword|isset} class="formError"{/if}><label for="confirmPassword">{lang}wcf.user.confirmPassword{/lang}</label></dt>
			<dd>
				<input type="password" id="confirmPassword" name="confirmPassword" value="{$confirmPassword}" class="medium" />
				{if $errorType.confirmPassword|isset}
					<small class="innerError">
						{if $errorType.confirmPassword == 'notEqual'}{lang}wcf.user.error.confirmPassword.notEqual{/lang}{/if}
					</small>
				{/if}
				<small>{lang}wcf.user.confirmPassword.description{/lang}</small>
			</dd>
		</dl>
	</fieldset>
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			new WCF.User.Registration.ValidatePassword($('#password'), $('#confirmPassword'));
		});
		//]]>
	</script>

	{if $useCaptcha}{include file='recaptcha'}{/if}

	<div class="formSubmit">
		<input type="reset" value="{lang}wcf.global.button.reset{/lang}" accesskey="r" />
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
		{@SID_INPUT_TAG}
 	</div>
</form>

{include file='footer' sandbox=false}

</body>
</html>