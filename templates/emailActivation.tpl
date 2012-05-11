{include file="documentHeader"}

<head>
	<title>{lang}wcf.user.emailChange.reactivation.title{/lang} - {PAGE_TITLE|language}</title>
	
	{include file='headInclude' sandbox=false}
</head>

<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{include file='header' sandbox=false}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.user.emailChange.reactivation.title{/lang}</h1>
	</hgroup>
</header>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}
	
<form method="post" action="{link controller='EmailActivation'}{/link}">
	<div class="container containerPadding marginTop shadow">
		<dl{if $errorField == 'u'} class="formError"{/if}>
			<dt><label for="userID">{lang}wcf.user.register.activation.userID{/lang}</label></dt>
			<dd>
				<input type="text" id="userID" name="u" value="{@$u}" required="true" class="medium" />
				{if $errorField == 'u'}
					<small class="innerError">
						{if $errorType == 'notValid'}{lang}wcf.user.register.activation.error.userID.notValid{/lang}{/if}
					</small>
				{/if}
			</dd>
		</dl>
	
		<dl{if $errorField == 'a'} class="formError"{/if}>
			<dt><label for="activationCode">{lang}wcf.user.register.activation.code{/lang}</label></dt>
			<dd>
				<input type="text" id="activationCode" maxlength="9" name="a" value="{@$a}" equired="true" class="medium" />
				{if $errorField == 'a'}
					<small class="innerError">
						{if $errorType == 'notValid'}{lang}wcf.user.register.activation.error.code.notValid{/lang}{/if}
					</small>
				{/if}
			</dd>
		</dl>
			
		{if $additionalFields|isset}{@$additionalFields}{/if}
			
		<div class="formElement"><!-- ToDo: Def. List! -->
			<div class="formField">
				<ul>
					<li>{*<img src="{icon}eMail.svg{/icon}" alt="" />*} <a href="index.php?form=RegisterNewActivationCode{@SID_ARG_2ND}">{lang}wcf.user.register.newActivationCode{/lang}</a></li>
				</ul>
			</div>
		</div>
	</div>
	
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
		<input type="hidden" name="action" value="reenable" />
	</div>
</form>

{include file='footer' sandbox=false}

</body>
</html>
