{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.login{/lang} - {PAGE_TITLE|language}</title>
	
	{include file='headInclude'}
	
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
{include file='header' __disableLoginLink=true}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.user.login{/lang}</h1>
	</hgroup>
</header>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

<form method="post" action="{link controller='Login'}{/link}" id="loginForm">
	<div class="container containerPadding marginTop shadow">
		<fieldset>
			<legend>{lang}wcf.user.login.data{/lang}</legend>
	
			<dl{if $errorField == 'username'} class="formError"{/if}>
				<dt><label for="username">{lang}wcf.user.usernameOrEmail{/lang}</label></dt>
				<dd>
					<input type="text" id="username" name="username" value="{$username}" required="required" class="long" />
					{if $errorField == 'username'}
						<small class="innerError">
							{if $errorType == 'empty'}
								{lang}wcf.global.form.error.empty{/lang}
							{else}
								{lang}wcf.user.username.error.{@$errorType}{/lang}
							{/if}
						</small>
					{/if}
				</dd>
			</dl>
			
			<dl>
				<dt>{lang}wcf.user.login.action{/lang}</dt>
				<dd><label><input type="radio" name="action" value="register" /> {lang}wcf.user.login.action.register{/lang}</label></dd>
				<dd><label><input type="radio" name="action" value="login" checked="checked" /> {lang}wcf.user.login.action.login{/lang}</label></dd>
			</dl>
			
			<dl{if $errorField == 'password'} class="formError"{/if}>
				<dt><label for="password">{lang}wcf.user.password{/lang}</label></dt>
				<dd>
					<input type="password" id="password" name="password" value="{$password}" class="long" />
					{if $errorField == 'password'}
						<small class="innerError">
							{if $errorType == 'empty'}
								{lang}wcf.global.form.error.empty{/lang}
							{else}
								{lang}wcf.user.password.error.{@$errorType}{/lang}
							{/if}
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
			
			<dl>
				<dd>
					<ul class="buttonList">
						<li><a class="button small" href="{link controller='LostPassword'}{/link}"><img src="{icon size='S'}key{/icon}" alt="" class="icon16" /> <span>{lang}wcf.user.lostPassword{/lang}</span></a></li>
						{if !REGISTER_DISABLED && REGISTER_ACTIVATION_METHOD == 1}<li><a class="button small" href="{link controller='RegisterActivation'}{/link}"><img src="{icon size='S'}check{/icon}" alt="" class="icon16" /> <span>{lang}wcf.user.registerActivation{/lang}</span></a></li>{/if}
					</ul>
				</dd>
			</dl>
			
			{event name='additionalFields'}
		</fieldset>
	</div>
	
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
	</div>
</form>

{include file='footer'}

</body>
</html>