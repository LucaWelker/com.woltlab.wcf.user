{include file="documentHeader"}

<head>
	<title>{lang}wcf.user.register.newActivationCode{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='header' sandbox=false}

<header class="wcf-container wcf-mainHeading">
	<img src="{icon}register1.svg{/icon}" alt="" class="wcf-containerIcon" />
	<hgroup class="wcf-containerContent">
		<h1>{lang}wcf.user.register.newActivationCode{/lang}</h1>
	</hgroup>
</header>

{if $userMessages|isset}{@$userMessages}{/if}
	
{if $errorField}
	<p class="wcf-error">{lang}wcf.global.form.error{/lang}</p>
{/if}
	
<form method="post" action="index.php?form=RegisterNewActivationCode">
	<div class="wcf-border wcf-content">
		<div>
			<dl{if $errorField == 'username'} class="wcf-formError"{/if}>
				<dt><label for="username">{lang}wcf.user.username{/lang}</label></dt>
				<dd>
					<input type="text" id="username" name="username" value="{@$username}" class="medium" />
					{if $errorField == 'username'}
						<small class="wcf-innerError">
							{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							{if $errorType == 'notFound'}{lang}wcf.user.error.username.notFound{/lang}{/if}
							{if $errorType == 'alreadyEnabled'}{lang}wcf.user.register.error.userAlreadyEnabled{/lang}{/if}
						</small>
					{/if}
				</dd>
			</dl>
	
			<dl{if $errorField == 'password'} class="wcf-formError"{/if}>
				<dt><label for="password">{lang}wcf.user.password{/lang}</label></dt>
				<dd>
					<input type="password" id="password" name="password" value="{@$password}" class="medium" />
					{if $errorField == 'password'}
						<small class="wcf-innerError">
							{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							{if $errorType == 'false'}{lang}wcf.user.login.error.password.false{/lang}{/if}
						</small>
					{/if}
				</dd>
			</dl>
				
			<dl{if $errorField == 'email'} class="wcf-formError"{/if}>
				<dt><label for="email">{lang}wcf.user.email{/lang}</label></dt>
				<dd>
					<input type="text" id="email" name="email" value="{@$email}" class="medium" />
					{if $errorField == 'email'}
						<small class="wcf-innerError">
							{if $errorType == 'notValid'}{lang}wcf.user.error.email.notValid{/lang}{/if}
							{if $errorType == 'notUnique'}{lang}wcf.user.error.email.notUnique{/lang}{/if}
						</small>
					{/if}
					<small>{lang}wcf.user.register.newActivationCode.email.description{/lang}</small>
				</dd>
				
			</dl>
				
			{if $additionalFields|isset}{@$additionalFields}{/if}
		</div>
	</div>
	
	<div class="wcf-formSubmit">
		<input type="reset" value="{lang}wcf.global.button.reset{/lang}" accesskey="r" />
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
		{@SID_INPUT_TAG}
		<input type="hidden" name="action" value="newActivationCode" />
	</div>
</form>

{include file='footer' sandbox=false}

</body>
</html>
