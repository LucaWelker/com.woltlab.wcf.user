{include file="documentHeader"}

<head>
	<title>{lang}wcf.user.lostPassword.title{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
	
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/WCF.User.Registration.js"></script>
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

<header class="mainHeading">
	{*<img src="{icon}lostPassword.svg{/icon}" alt="" />*}
	<hgroup>
		<h1>{lang}wcf.user.lostPassword.title{/lang}</h1>
	</hgroup>
</header>

{if $userMessages|isset}{@$userMessages}{/if}
	
<p class="info">{lang}wcf.user.lostPassword.description{/lang}</p>
	
{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

<form method="post" action="index.php?form=LostPassword">
	<div class="border content">
		<div class="container-1">
			<dl id="usernameDiv"{if $errorField == 'username'} class="formError{"/if}>
				<dt>
					<label for="username">{lang}wcf.user.username{/lang}</label>
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
					<label for="email">{lang}wcf.user.email{/lang}</label>
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
				
			{if $additionalFields|isset}{@$additionalFields}{/if}
			{include file='recaptcha'}
		</div>
	</div>
		
	<div class="formSubmit">
		<input type="reset" id="resetButton" value="{lang}wcf.global.button.reset{/lang}" accesskey="r" />
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
	</div>
	
	{@SID_INPUT_TAG}
</form>

{include file='footer' sandbox=false}

</body>
</html>
