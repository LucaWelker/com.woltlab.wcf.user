{include file='documentHeader'}
<head>
	<title>Login form</title>
	
	{include file='headInclude' sandbox=false}
</head>
<body>
{include file='header' sandbox=false __disableLoginLink=true}

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

<form method="post" action="index.php/Login/">
	<dl>
		<dt><label for="username">Username or email address</label></dt>
		<dd><input type="text" id="username" name="username" value="{$username}" /></dd>
		{if $errorField == 'username'}
			<p class="innerError">
				{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
				{if $errorType == 'notFound'}{lang}wcf.user.error.username.notFound{/lang}{/if}
				{if $errorType == 'notEnabled'}{lang}wcf.user.login.error.username.notEnabled{/lang}{/if}
			</p>
		{/if}
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
		<dd><input type="password" id="password" name="password" value="{$password}" /></dd>
		{if $errorField == 'password'}
			<p class="innerError">
				{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
				{if $errorType == 'false'}{lang}wcf.user.login.error.password.false{/lang}{/if}
			</p>
		{/if}
	</dl>
	
	{if $supportsPersistentLogins}
		<dl>
			<dt><label for="useCookies">Stay logged in</label></dt>
			<dd><input type="checkbox" id="useCookies" name="useCookies" value="1" {if $useCookies}checked="checked" {/if}/></dd>
		</dl>
	{/if}
	
	<input type="submit" value="Login" />
</form>

{include file='footer' sandbox=false}

</body>
</html>