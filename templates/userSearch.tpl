{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.search{/lang} - {PAGE_TITLE|language}</title>
	
	{include file='headInclude'}
</head>

<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>

{include file='header' sidebarOrientation='right'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.user.search{/lang}</h1>
	</hgroup>
</header>

{include file='userNotice'}

{if $errorField == 'search'}
	<p class="error">{lang}wcf.user.search.error.noMatches{/lang}</p>
{/if}

<form method="post" action="{link controller='UserSearch'}{/link}">
	<div class="container containerPadding marginTop">
		<fieldset>
			<legend>{lang}wcf.acp.user.search.conditions.general{/lang}</legend>
			
			<dl>
				<dt><label for="username">{lang}wcf.user.username{/lang}</label></dt>
				<dd>
					<input type="text" id="username" name="username" value="{$username}" class="medium" />
				</dd>
			</dl>
			
			<dl>
				<dt><label for="email">{lang}wcf.user.email{/lang}</label></dt>
				<dd>
					<input type="email" id="email" name="email" value="{$email}" class="medium" />
				</dd>
			</dl>
		</fieldset>
		
		{foreach from=$optionTree[0][categories] item=category}
			<fieldset>
				<legend>{lang}wcf.user.option.category.{@$category[object]->categoryName}{/lang}</legend>
				{hascontent}<h2>{content}{lang __optional=true}wcf.user.option.category.{@$category[object]->categoryName}.description{/lang}{/content}</h2>{/hascontent}
				
				{include file='userOptionFieldList' options=$category[options] langPrefix='wcf.user.option.'}
			</fieldset>
		{/foreach}
	</div>
	
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
	</div>
</form>

{include file='footer'}

</body>
</html>
