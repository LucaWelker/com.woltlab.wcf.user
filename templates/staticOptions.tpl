{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.staticOptions.title{/lang}</title>
	{include file='headInclude' sandbox=false}
	
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			WCF.TabMenu.init();
		});
		//]]>
	</script>
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='profileEditSidebar' sandbox=false}

{include file='header' sandbox=false sidebarOrientation='left'}

<header class="wcf-container wcf-mainHeading">
	<img src="{icon size='L'}users1{/icon}" alt="" class="wcf-containerIcon" />
	<hgroup class="wcf-containerContent">
		<h1>{lang}wcf.user.staticOptions.title{/lang}</h1>
	</hgroup>
</header>

{if $success|isset}
	<p class="wcf-success">{lang}wcf.global.form.success{/lang}</p>	
{/if}

<div class="wcf-contentHeader"> </div>

<form method="post" action="{link controller='StaticOptions'}{/link}">
	<div class="wcf-tabMenuContainer" data-active="" data-store="activeTabMenuItem">
		<nav class="wcf-tabMenu">
			<ul>
				<li><a href="#general">{lang}wcf.user.staticOptions.category.general{/lang}</a></li>
				<li><a href="#display">{lang}wcf.user.staticOptions.category.display{/lang}</a></li>
			</ul>
		</nav>
		
		<div id="general" class="wcf-tabMenuContainer wcf-box wcf-boxPadding wcf-shadow1 wcf-tabMenuContent">
			<hgroup class="wcf-subHeading">
				<h1>{lang}wcf.user.staticOptions.category.general{/lang}</h1>
			</hgroup>
			
			<fieldset>
				<legend>{lang}wcf.user.staticOptions.language{/lang}</legend>
				
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
			</fieldset>
		</div>
		
		<div id="display" class="wcf-tabMenuContainer wcf-box wcf-boxPadding wcf-shadow1 wcf-tabMenuContent">
			<hgroup class="wcf-subHeading">
				<h1>{lang}wcf.user.staticOptions.category.display{/lang}</h1>
			</hgroup>
			
			<fieldset>
				<legend>{lang}wcf.user.staticOptions.style{/lang}</legend>
				
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
		</div>
	</div>
	
	<div class="wcf-formSubmit">
		<input type="reset" value="{lang}wcf.global.button.reset{/lang}" accesskey="r" />
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
		{@SID_INPUT_TAG}
 	</div>
</form>

<div class="wcf-contentFooter"> </div>

{include file='footer' sandbox=false}

</body>
</html>
