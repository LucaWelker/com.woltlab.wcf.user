{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.ignoredUsers.title{/lang}</title>
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
		<h1>{lang}wcf.user.dynamicOptions.title{/lang}</h1>
	</hgroup>
</header>

{if $success|isset}
	<p class="wcf-success">{lang}wcf.global.form.success{/lang}</p>	
{/if}

<div class="wcf-contentHeader"> </div>

<form method="post" action="{link controller='DynamicOptions'}{/link}">
	<div class="wcf-tabMenuContainer" data-active="" data-store="activeTabMenuItem">
		<nav class="wcf-tabMenu">
			<ul>
				{foreach from=$optionTree item=categoryLevel1}
					<li><a href="#{@$categoryLevel1[object]->categoryName}">{lang}wcf.user.option.category.{@$categoryLevel1[object]->categoryName}{/lang}</a></li>
				{/foreach}
			</ul>
		</nav>
		
		{foreach from=$optionTree item=categoryLevel1}
			<div id="{@$categoryLevel1[object]->categoryName}" class="wcf-tabMenuContainer wcf-box wcf-boxPadding wcf-shadow1 wcf-tabMenuContent" data-active="" data-store="activeMenuItem">
				<nav class="wcf-menu">
					<ul>
						{foreach from=$categoryLevel1[categories] item=$categoryLevel2}
							<li><a href="#{@$categoryLevel1[object]->categoryName}-{@$categoryLevel2[object]->categoryName}">{lang}wcf.user.option.category.{@$categoryLevel2[object]->categoryName}{/lang}</a></li>
						{/foreach}
					</ul>
				</nav>
				
				{foreach from=$categoryLevel1[categories] item=categoryLevel2}
					<div id="{@$categoryLevel1[object]->categoryName}-{@$categoryLevel2[object]->categoryName}" class="hidden">
						<hgroup class="wcf-subHeading">
							<h1>{lang}wcf.user.option.category.{@$categoryLevel2[object]->categoryName}{/lang}</h1>
						</hgroup>
						
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
	
	<div class="wcf-formSubmit">
		<input type="reset" value="{lang}wcf.global.button.reset{/lang}" accesskey="r" />
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
 	</div>
</form>

<div class="wcf-contentFooter"> </div>

{include file='footer' sandbox=false}

</body>
</html>
