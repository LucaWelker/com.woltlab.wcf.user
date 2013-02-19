{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.recentActivity{/lang} - {PAGE_TITLE|language}</title>
	{include file='headInclude'}
	
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			WCF.Language.addObject({
				'wcf.user.recentActivity.more': '{lang}wcf.user.recentActivity.more{/lang}',
				'wcf.user.recentActivity.noMoreEntries': '{lang}wcf.user.recentActivity.noMoreEntries{/lang}'
			});
			
			new WCF.User.RecentActivityLoader(null);
		});
		//]]>
	</script>
</head>

<body id="tpl{$templateName|ucfirst}">

{capture assign='sidebar'}
	{@$__boxSidebar}
{/capture}

{include file='header' sidebarOrientation='right'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.user.recentActivity{/lang}</h1>
	</hgroup>
</header>

{include file='userNotice'}

<div class="contentNavigation"></div>

<div class="container marginTop">
	<ul id="recentActivities" class="containerList recentActivityList" data-last-event-time="{@$lastEventTime}">
		{include file='recentActivityListItem'}
	</ul>
</div>

<div class="contentNavigation"></div>

{include file='footer'}

</body>
</html>
