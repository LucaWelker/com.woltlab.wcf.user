{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.recentActivity{/lang} {if $pageNo > 1}- {lang}wcf.page.pageNo{/lang} {/if}- {PAGE_TITLE|language}</title>
	{include file='headInclude' sandbox=false}
</head>

<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>

{capture assign='sidebar'}
	{*TODO: sidebar content*}
{/capture}

{include file='header' sandbox=false sidebarOrientation='right'}

<header class="box48 boxHeadline">
	<img src="{icon size='L'}users1{/icon}" alt="" class="icon48" />
	<hgroup>
		<h1>{lang}wcf.user.recentActivity{/lang} <span class="badge">{#$items}</span></h1>
	</hgroup>
</header>

<div class="contentNavigation">
	{pages print=true assign=pagesLinks controller='RecentActivityList' link="pageNo=%d"}
</div>

<div class="container marginTop shadow">
	{include file='recentActivities' eventList=$objects}
</div>

<div class="contentNavigation">
	{@$pagesLinks}
</div>

{include file='footer' sandbox=false}

</body>
</html>
