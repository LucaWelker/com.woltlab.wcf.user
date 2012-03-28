{include file="documentHeader"}

<head>
	<title>{lang}wcf.user.register.newActivationCode{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='header' sandbox=false}

<header class="box48 boxHeadline">
	<img src="{icon}register1.svg{/icon}" alt="" class="icon48" />
	<hgroup>
		<h1>{lang}wcf.user.register.newActivationCode{/lang}</h1>
	</hgroup>
</header>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}
	
<form method="post" action="{link controller='RegisterNewActivationCode'}{/link}">
	<div class="container containerPadding marginTop shadow">
		<dl{if $errorField == 'username'} class="formError"{/if}>
			<dt><label for="username">{lang}wcf.user.username{/lang}</label></dt>
			<dd>
				<input type="text" id="username" name="username" value="{@$username}" class="medium" />
				{if $errorField == 'username'}
					<small class="innerError">
						{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
						{if $errorType == 'notFound'}{lang}wcf.user.error.username.notFound{/lang}{/if}
						{if $errorType == 'alreadyEnabled'}{lang}wcf.user.register.error.userAlreadyEnabled{/lang}{/if}
					</small>
				{/if}
			</dd>
		</dl>

		<dl{if $errorField == 'password'} class="formError"{/if}>
			<dt><label for="password">{lang}wcf.user.password{/lang}</label></dt>
			<dd>
				<input type="password" id="password" name="password" value="{@$password}" class="medium" />
				{if $errorField == 'password'}
					<small class="innerError">
						{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
						{if $errorType == 'false'}{lang}wcf.user.login.error.password.false{/lang}{/if}
					</small>
				{/if}
			</dd>
		</dl>
			
		<dl{if $errorField == 'email'} class="formError"{/if}>
			<dt><label for="email">{lang}wcf.user.email{/lang}</label></dt>
			<dd>
				<input type="text" id="email" name="email" value="{@$email}" class="medium" />
				{if $errorField == 'email'}
					<small class="innerError">
						{if $errorType == 'notValid'}{lang}wcf.user.error.email.notValid{/lang}{/if}
						{if $errorType == 'notUnique'}{lang}wcf.user.error.email.notUnique{/lang}{/if}
					</small>
				{/if}
				<small>{lang}wcf.user.register.newActivationCode.email.description{/lang}</small>
			</dd>
		</dl>
	</div>
	
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
		<input type="hidden" name="action" value="newActivationCode" />
	</div>
</form>

{include file='footer' sandbox=false}

</body>
</html>
