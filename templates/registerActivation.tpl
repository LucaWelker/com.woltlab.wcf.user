{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.registerActivation{/lang} - {PAGE_TITLE|language}</title>
	{include file='headInclude'}
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='header'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.user.registerActivation{/lang}</h1>
	</hgroup>
</header>

{include file='userNotice'}

{if $__wcf->user->userID && $__wcf->user->activationCode}<p class="info">{lang}wcf.user.registerActivation.info{/lang}</p>{/if}

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

<form method="post" action="{link controller='RegisterActivation'}{/link}">
	<div class="container containerPadding marginTop shadow">
		<fieldset>
			<legend><label for="userID">{lang}wcf.user.registerActivation{/lang}</label></legend>
		
			<dl{if $errorField == 'u'} class="formError"{/if}>
				<dt><label for="userID">{lang}wcf.user.userID{/lang}</label></dt>
				<dd>
					<input type="text" id="userID" name="u" value="{@$u}" required="required" class="medium" />
					{if $errorField == 'u'}
						<small class="innerError">
							{if $errorType == 'notValid'}{lang}wcf.user.userID.error.invalid{/lang}{/if}
						</small>
					{/if}
				</dd>
			</dl>
		
			<dl{if $errorField == 'a'} class="formError"{/if}>
				<dt><label for="activationCode">{lang}wcf.user.activationCode{/lang}</label></dt>
				<dd>
					<input type="text" id="activationCode" maxlength="9" name="a" value="{@$a}" required="required" class="medium" />
					{if $errorField == 'a'}
						<small class="innerError">
							{if $errorType == 'notValid'}{lang}wcf.user.activationCode.error.notValid{/lang}{/if}
						</small>
					{/if}
				</dd>
			</dl>
			
			{event name='fields'}
			
			<dl>
				<dd>
					<ul class="buttonList">
						<li><a class="button small" href="{link controller='RegisterNewActivationCode'}{/link}"><img src="{icon size='S'}eMail{/icon}" alt="" class="icon16" /> <span>{lang}wcf.user.newActivationCode{/lang}</span></a></li>
						{event name='buttons'}
					</ul>
				</dd>
			</dl>
		</fieldset>
		
		{event name='fieldsets'}
	</div>	
	
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
	</div>
</form>

{include file='footer'}
</body>
</html>
