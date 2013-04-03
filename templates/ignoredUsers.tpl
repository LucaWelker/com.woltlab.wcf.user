{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.ignoredUsers{/lang} - {lang}wcf.user.usercp{/lang} - {PAGE_TITLE|language}</title>
	{include file='headInclude'}
	
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			new WCF.Action.Delete('wcf\\data\\user\\ignore\\UserIgnoreAction', '.jsIgnoredUser');
		});
		//]]>
	</script>
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='userMenuSidebar'}

{include file='header' sidebarOrientation='left'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.user.ignoredUsers{/lang} <span class="badge">{#$items}</span></h1>
	</hgroup>
</header>

{include file='userNotice'}

<div class="contentNavigation">
	{pages print=true assign=pagesLinks controller='IgnoredUsers' link="pageNo=%d"}
	
	{hascontent}
		<nav>
			<ul>
				{content}
					{event name='contentNavigationButtonsTop'}
				{/content}
			</ul>
		</nav>
	{/hascontent}
</div>

{hascontent}
	<div class="container marginTop">
		<ol class="containerList doubleColumned userList">
			{content}
				{foreach from=$objects item=user}
					<li class="jsIgnoredUser">
						<div class="box48">
							<a href="{link controller='User' object=$user}{/link}" title="{$user->username}" class="framed">{@$user->getAvatar()->getImageTag(48)}</a>
								
							<div class="details userInformation">
								{include file='userInformationHeadline'}
								
								<ul class="buttonList jsOnly">
									<li><span class="icon icon16 icon-remove pointer jsTooltip jsDeleteButton" title="{lang}wcf.user.button.unignore{/lang}" data-object-id="{@$user->ignoreID}"></span></li>
									{event name='userButtons'}
								</ul>
								
								{include file='userInformationStatistics'}
							</div>
						</div>
					</li>
				{/foreach}
			{/content}
		</ol>
	</div>
	
	<div class="contentNavigation">
		{@$pagesLinks}
		
		{hascontent}
			<nav>
				<ul>
					{content}
						{event name='contentNavigationButtonsBottom'}
					{/content}
				</ul>
			</nav>
		{/hascontent}
	</div>
{hascontentelse}
	<p class="info">{lang}wcf.user.ignoredUsers.noUsers{/lang}</p>
{/hascontent}

{include file='footer'}

</body>
</html>
