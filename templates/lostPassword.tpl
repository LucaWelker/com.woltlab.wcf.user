{include file="documentHeader"}

<head>
	<title>{lang}wcf.user.lostPassword.title{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
	
	<script type="text/javascript" src="{@$__wcf->getPath('wcf')}js/WCF.User.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			new WCF.User.Registration.LostPassword();
		});
		//]]>
	</script>
</head>

<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>

{include file='header' sandbox=false}

<header class="box48 boxHeadline">
	<img src="{icon}logIn1{/icon}" alt="" class="icon48" />
	<hgroup>
		<h1>{lang}wcf.user.lostPassword.title{/lang}</h1>
	</hgroup>
</header>

<p class="info">{lang}wcf.user.lostPassword.description{/lang}</p>
	
{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

<form method="post" action="{link controller='LostPassword'}{/link}">
	<div class="container containerPadding marginTop shadow">
		<fieldset>
			<legend>{lang}wcf.user.lostPassword.title{/lang}</legend>
			
			<dl id="usernameDiv"{if $errorField == 'username'} class="formError"{/if}>
				<dt>
					<label for="usernameInput">{lang}wcf.user.username{/lang}</label>
				</dt>
				<dd>
					<input type="text" id="usernameInput" name="username" value="{$username}" class="medium" />
					{if $errorField == 'username'}
						<small class="innerError">
							{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							{if $errorType == 'notFound'}{lang}wcf.user.error.username.notFound{/lang}{/if}
						</small>
					{/if}
				</dd>
			</dl>
			
			<dl id="emailDiv"{if $errorField == 'email'} class="formError"{/if}>
				<dt>
					<label for="emailInput">{lang}wcf.user.email{/lang}</label>
				</dt>
				<dd>
					<input type="email" id="emailInput" name="email" value="{$email}" class="medium" />
					{if $errorField == 'email'}
						<small class="innerError">
							{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							{if $errorType == 'notFound'}{lang}wcf.user.lostPassword.error.email.notFound{/lang}{/if}
						</small>
					{/if}
				</dd>
			</dl>
		</fieldset>
			
		{include file='recaptcha'}
	</div>
		
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
	</div>
</form>

{include file='footer' sandbox=false}

</body>
</html>
