{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.recentActivity{/lang} {if $pageNo > 1}- {lang}wcf.page.pageNo{/lang} {/if}- {PAGE_TITLE|language}</title>
	{include file='headInclude'}
</head>

<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>

{capture assign='sidebar'}
	{*TODO: sidebar content*}
{/capture}

{include file='header' sidebarOrientation='right'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.user.recentActivity{/lang} <span class="badge">{#$items}</span></h1>
	</hgroup>
</header>

{include file='userNotice'}

<div class="contentNavigation">
	{pages print=true assign=pagesLinks controller='RecentActivityList' link="pageNo=%d"}
</div>

<div class="container marginTop">
	{include file='recentActivities' eventList=$objects}
</div>

<div class="contentNavigation">
	{@$pagesLinks}
</div>

{include file='footer'}

</body>
</html>
