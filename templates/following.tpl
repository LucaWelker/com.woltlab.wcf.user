{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.following{/lang} - {lang}wcf.user.usercp{/lang} - {PAGE_TITLE|language}</title>
	{include file='headInclude'}
	
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			new WCF.Action.Delete('wcf\\data\\user\\follow\\UserFollowAction', '.jsFollowing');
		});
		//]]>
	</script>
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='userMenuSidebar'}

{include file='header' sidebarOrientation='left'}

<header class="boxHeadline"> 
	<hgroup >
		<h1>{lang}wcf.user.following{/lang} <span class="badge">{#$items}</span></h1>
	</hgroup>
</header>

{include file='userNotice'}

<div class="contentNavigation">
	{pages print=true assign=pagesLinks controller='Following' link="pageNo=%d"}
</div>

{hascontent}
	<div class="container marginTop">
		<ol class="containerList doubleColumned userList">
			{content}
				{foreach from=$objects item=user}
					<li class="jsFollowing">
						<div class="box48">
							<a href="{link controller='User' object=$user}{/link}" title="{$user->username}" class="framed">{@$user->getAvatar()->getImageTag(48)}</a>
								
							<div class="details userInformation">
								{include file='userInformationHeadline'}
								
								<ul class="buttonList jsOnly">
									<li><span class="icon icon16 icon-remove pointer jsTooltip jsDeleteButton" title="{lang}wcf.user.button.unfollow{/lang}" data-object-id="{@$user->followID}"></span></li>
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
	</div>
{hascontentelse}
	<p class="info">{lang}wcf.user.following.noUsers{/lang}</p>
{/hascontent}

{include file='footer'}

</body>
</html>
