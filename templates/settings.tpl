{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.option.category.settings.{$category}{/lang} - {lang}wcf.user.menu.settings{/lang} - {PAGE_TITLE|language}</title>
	{include file='headInclude'}
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='userMenuSidebar'}

{include file='header' sidebarOrientation='left'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.user.menu.settings{/lang}: {lang}wcf.user.option.category.settings.{$category}{/lang}</h1>
	</hgroup>
</header>

{if $success|isset}
	<p class="success">{lang}wcf.global.form.success{/lang}</p>	
{/if}

<form method="post" action="{link controller='Settings'}{/link}">
	<div class="container containerPadding marginTop shadow">
		{if $category == 'general'}
			<fieldset>
				<dl>
					<dt><label for="languageID">{lang}wcf.user.staticOptions.language{/lang}</label></dt>
					<dd>
						<select id="languageID" name="languageID">
							{foreach from=$availableLanguages item=language}
								<option value="{@$language->languageID}"{if $language->languageID == $languageID} selected="selected"{/if}>{$language}</option>
							{/foreach}
						</select>
					</dd>
				</dl>
				
				{hascontent}
					<dl>
						<dt><label>{lang}wcf.user.staticOptions.contentLanguages{/lang}</label></dt>
						{content}
							{foreach from=$availableContentLanguages item=language}
								<dd>
									<label><input name="contentLanguageID[]" type="checkbox" value="{@$language->languageID}"{if $language->languageID|in_array:$contentLanguageIDs} checked="checked"{/if} /> {$language}</label>
								</dd>
							{/foreach}
						{/content}
					</dl>
				{/hascontent}
				
				<dl>
					<dt><label for="styleID">{lang}wcf.user.staticOptions.style{/lang}</label></dt>
					<dd>
						<!-- TODO: Add some fancy JavaScript to display preview images, this should be common enough to use it in boardAdd.tpl too! -->
						<select id="styleID" name="styleID">
							<option value="0"></option>
							{foreach from=$availableStyles item=style}
								<option value="{@$style->styleID}"{if $style->styleID == $styleID} selected="selected"{/if}>{$style->styleName}</option>
							{/foreach}
						</select>
					</dd>
				</dl>
			</fieldset>
		{/if}
		
		{foreach from=$optionTree[0][categories][0][categories] item=optionCategory}
			<fieldset>
				<legend>{lang}wcf.user.option.category.{@$optionCategory[object]->categoryName}{/lang}</legend>
			
				{include file='userProfileOptionFieldList' options=$optionCategory[options] langPrefix='wcf.user.option.'}
			</fieldset>
		{/foreach}
	</div>
	
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
		{if $category != 'general'}<input type="hidden" name="category" value="{$category}" />{/if}
	</div>
</form>

{include file='footer'}

</body>
</html>
