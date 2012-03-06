{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.login{/lang} - {PAGE_TITLE|language}</title>
	
	{include file='headInclude' sandbox=false}
	
	<script type="text/javascript" src="{@$__wcf->getPath('wcf')}js/WCF.User.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			new WCF.User.Login(false);
		})
		//]]>
	</script>
</head>

<body id="tpl{$templateName|ucfirst}">
{include file='header' sandbox=false __disableLoginLink=true}

<header class="wcf-container wcf-mainHeading">
	<img src="{icon size='L'}logIn1{/icon}" alt="" class="wcf-containerIcon" />
	<hgroup class="wcf-containerContent">
		<h1>{lang}wcf.user.login{/lang}</h1>
	</hgroup>
</header>

{if $errorField}
	<p class="wcf-error">{lang}wcf.global.form.error{/lang}</p>
{/if}

<form method="post" action="{link controller='Login'}{/link}" id="loginForm">
	<div class="wcf-border wcf-content">
		<fieldset>
			<legend>{lang}wcf.user.login.data{/lang}</legend>
	
			<dl>
				<dt><label for="username">{lang}wcf.user.usernameOrEmail{/lang}</label></dt>
				<dd>
					<input type="text" id="username" name="username" value="{$username}" required="required" class="medium" />
					{if $errorField == 'username'}
						<small class="wcf-innerError">
							{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							{if $errorType == 'notFound'}{lang}wcf.user.error.username.notFound{/lang}{/if}
							{if $errorType == 'notEnabled'}{lang}wcf.user.login.error.username.notEnabled{/lang}{/if}
						</small>
					{/if}
				</dd>
			</dl>
			
			<dl>
				<dt>{lang}wcf.user.login.action{/lang}</dt>
				<dd><label><input type="radio" name="action" value="register" /> {lang}wcf.user.login.action.register{/lang}</label></dd>
				<dd><label><input type="radio" name="action" value="login" checked="checked" /> {lang}wcf.user.login.action.login{/lang}</label></dd>
			</dl>
			
			<dl>
				<dt><label for="password">{lang}wcf.user.password{/lang}</label></dt>
				<dd>
					<input type="password" id="password" name="password" value="{$password}" class="medium" />
					{if $errorField == 'password'}
						<small class="wcf-innerError">
							{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							{if $errorType == 'false'}{lang}wcf.user.login.error.password.false{/lang}{/if}
						</small>
					{/if}
				</dd>
			</dl>
			
			{if $supportsPersistentLogins}
				<dl>
					<dd>
						<label for="useCookies"><input type="checkbox" id="useCookies" name="useCookies" value="1" {if $useCookies}checked="checked" {/if}/> {lang}wcf.user.useCookies{/lang}</label>
					</dd>
				</dl>
			{/if}
		</fieldset>
	</div>
	
	<div class="wcf-formSubmit">
		<input type="reset" value="{lang}wcf.global.button.reset{/lang}" accesskey="r" />
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
		{@SID_INPUT_TAG}
 	</div>
</form>

{include file='footer' sandbox=false}

</body>
</html>