{include file='documentHeader'}

<head>
	<title>dynamic options</title>
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

<header class="box48 boxHeadline">
	<img src="{icon size='L'}users1{/icon}" alt="" class="icon48" />
	<hgroup>
		<h1>{lang}wcf.user.dynamicOptions.title{/lang}</h1>
	</hgroup>
</header>

{if $success|isset}
	<p class="success">{lang}wcf.global.form.success{/lang}</p>	
{/if}

<form method="post" action="{link controller='DynamicOptions'}{/link}">
	<div class="tabMenuContainer" data-active="" data-store="activeTabMenuItem">
		<nav class="tabMenu">
			<ul>
				{foreach from=$optionTree item=categoryLevel1}
					<li><a href="#{@$categoryLevel1[object]->categoryName}">{lang}wcf.user.option.category.{@$categoryLevel1[object]->categoryName}{/lang}</a></li>
				{/foreach}
			</ul>
		</nav>
		
		{foreach from=$optionTree item=categoryLevel1}
			<div id="{@$categoryLevel1[object]->categoryName}" class="tabMenuContent container shadow" data-active="" data-store="activeMenuItem">
				{*TODO: submenu*}
				<nav class="wcf-menu">
					<ul>
						{foreach from=$categoryLevel1[categories] item=$categoryLevel2}
							<li><a href="#{@$categoryLevel1[object]->categoryName}-{@$categoryLevel2[object]->categoryName}">{lang}wcf.user.option.category.{@$categoryLevel2[object]->categoryName}{/lang}</a></li>
						{/foreach}
					</ul>
				</nav>
				
				{foreach from=$categoryLevel1[categories] item=categoryLevel2}
					<div id="{@$categoryLevel1[object]->categoryName}-{@$categoryLevel2[object]->categoryName}" class="hidden containerPadding">
						{foreach from=$categoryLevel2[categories] item=categoryLevel3}
							<fieldset>
								<legend>{lang}wcf.user.option.category.{@$categoryLevel3[object]->categoryName}{/lang}</legend>
								
								{include file='userProfileOptionFieldList' options=$categoryLevel3[options] langPrefix='wcf.user.option.' sandbox=false}
							</fieldset>
						{/foreach}
					</div>
				{/foreach}
			</div>
		{/foreach}
	</div>
	
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
	</div>
</form>

{include file='footer' sandbox=false}

</body>
</html>
