{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.recentActivity{/lang} {if $pageNo > 1}- {lang}wcf.page.pageNo{/lang} {/if}- {PAGE_TITLE|language}</title>
	{include file='headInclude' sandbox=false}
</head>

<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>

{capture assign='sidebar'}

{/capture}

{include file='header' sandbox=false sidebarOrientation='right'}

<header class="wcf-container wcf-mainHeading">
	<img src="{icon size='L'}users1{/icon}" alt="" class="wcf-containerIcon" />
	<hgroup class="wcf-containerContent">
		<h1>{lang}wcf.user.recentActivity{/lang} <span class="wcf-badge">{#$items}</span></h1>
	</hgroup>
</header>

<div class="wcf-contentHeader">
	{pages print=true assign=pagesLinks controller='RecentActivityList' link="pageNo=%d"}
</div>

<div>{include file='recentActivities' eventList=$objects}</div>

<div class="wcf-contentFooter">
	{@$pagesLinks}
</div>

{include file='footer' sandbox=false}

</body>
</html>
