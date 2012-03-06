{include file="documentHeader"}

<head>
	<title>{lang}wcf.user.emailChange.reactivation.title{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	
	{include file='headInclude' sandbox=false}
</head>

<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{include file='header' sandbox=false}

<header class="wcf-container wcf-mainHeading">
	<img src="{icon}eMail1.svg{/icon}" alt="" class="wcf-containerIcon" />
	<hgroup class="wcf-containerContent">
		<h1>{lang}wcf.user.emailChange.reactivation.title{/lang}</h1>
	</hgroup>
</header>

{if $userMessages|isset}{@$userMessages}{/if}
	
{if $errorField}
	<p class="wcf-error">{lang}wcf.global.form.error{/lang}</p>
{/if}
	
<form method="post" action="index.php?form=EmailActivation">
	<div class="wcf-box wcf-boxPadding wcf-shadow1">
		<div>
			<dl{if $errorField == 'u'} class="wcf-formError"{/if}>
				<dt><label for="userID">{lang}wcf.user.register.activation.userID{/lang}</label></dt>
				<dd>
					<input type="text" id="userID" name="u" value="{@$u}" class="medium" />
					{if $errorField == 'u'}
						<small class="wcf-innerError">
							{if $errorType == 'notValid'}{lang}wcf.user.register.activation.error.userID.notValid{/lang}{/if}
						</small>
					{/if}
				</dd>
			</dl>
		
			<dl{if $errorField == 'a'} class="wcf-formError"{/if}>
				<dt><label for="activationCode">{lang}wcf.user.register.activation.code{/lang}</label></dt>
				<dd>
					<input type="text" id="activationCode" maxlength="9" name="a" value="{@$a}" class="long" />
					{if $errorField == 'a'}
						<small class="wcf-innerError">
							{if $errorType == 'notValid'}{lang}wcf.user.register.activation.error.code.notValid{/lang}{/if}
						</small>
					{/if}
				</dd>
			</dl>
				
			{if $additionalFields|isset}{@$additionalFields}{/if}
				
			<div class="formElement"><!-- ToDo: Def. List! -->
				<div class="formField">
					<ul>
						<li>{*<img src="{icon}eMail1.svg{/icon}" alt="" />*} <a href="index.php?form=RegisterNewActivationCode{@SID_ARG_2ND}">{lang}wcf.user.register.newActivationCode{/lang}</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	
	<div class="wcf-formSubmit">
		<input type="reset" value="{lang}wcf.global.button.reset{/lang}" accesskey="r" />
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
	</div>
	
	{@SID_INPUT_TAG}
	<input type="hidden" name="action" value="reenable" />
</form>

{include file='footer' sandbox=false}

</body>
</html>
