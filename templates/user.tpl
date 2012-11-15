{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.profile{/lang} - {lang}wcf.user.members{/lang} - {PAGE_TITLE|language}</title>
	{include file='headInclude'}
	
	<script type="text/javascript" src="{@$__wcf->getPath('wcf')}js/WCF.User.js"></script>
	{event name='javascriptInclude'}
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			{if $__wcf->getUser()->userID && $__wcf->getUser()->userID != $user->userID}
				WCF.Language.addObject({
					'wcf.user.button.follow': '{lang}wcf.user.button.follow{/lang}',
					'wcf.user.button.unfollow': '{lang}wcf.user.button.unfollow{/lang}',
					'wcf.user.button.ignore': '{lang}wcf.user.button.ignore{/lang}',
					'wcf.user.button.unignore': '{lang}wcf.user.button.unignore{/lang}'
				});

				new WCF.User.Profile.Follow({$user->userID}, {if $__wcf->getUserProfileHandler()->isFollowing($user->userID)}true{else}false{/if});
				new WCF.User.Profile.IgnoreUser({@$user->userID}, {if $__wcf->getUserProfileHandler()->isIgnoredUser($user->userID)}true{else}false{/if});
			{/if}

			new WCF.User.Profile.TabMenu({@$user->userID});

			WCF.TabMenu.init();

			{* TODO: Handle admin permissions *}
			{if $__wcf->getUser()->userID == $user->userID}
				WCF.Language.addObject({
					'wcf.user.editProfile': '{lang}wcf.user.editProfile{/lang}',
				});

				WCF.User.Profile.Editor.Handler.init({$user->userID}, {if $editOnInit}true{else}false{/if});
				new WCF.User.Profile.Editor.Information({@$overviewObjectType->objectTypeID});
			{/if}
			
			{if $user->activityPoints}
				$('.activityPointsDisplay').click(function (event) {
					WCF.showAJAXDialog('detailedActivityPointList', true, {
						title: '{lang}wcf.user.activityPoints{/lang}',
						data: {
							className: 'wcf\\data\\user\\UserProfileAction',
							actionName: 'getDetailedActivityPointList',
							objectIDs: [ {$user->userID} ]
						}
					});
				});
			{/if}
			
			{if $followingCount > 10}
				var $followingList = null;
				$('#followingAll').click(function() {
					if ($followingList === null) {
						$followingList = new WCF.User.List('wcf\\data\\user\\follow\\UserFollowingAction', '{lang}wcf.user.profile.following{/lang}', { userID: {@$user->userID} });
					}
					
					$followingList.open();
				});
			{/if}
			{if $followerCount > 10}
				var $followerList = null;
				$('#followerAll').click(function() {
					if ($followerList === null) {
						$followerList = new WCF.User.List('wcf\\data\\user\\follow\\UserFollowAction', '{lang}wcf.user.profile.followers{/lang}', { userID: {@$user->userID} });
					}
					
					$followerList.open();
				});
			{/if}
			{if $visitorCount > 10}
				var $visitorList = null;
				$('#visitorAll').click(function() {
					if ($visitorList === null) {
						$visitorList = new WCF.User.List('wcf\\data\\user\\profile\\visitor\\UserProfileVisitorAction', '{lang}wcf.user.profile.visitors{/lang}', { userID: {@$user->userID} });
					}
					
					$visitorList.open();
				});
			{/if}
			
			{event name='javascriptInit'}
		});
		//]]>
	</script>
</head>

<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>

{capture assign='sidebar'}
	{include file='userSidebar'}
{/capture}

{include file='header' sidebarOrientation='left'}

<header class="boxHeadline userHeadline">
	<hgroup>
		<h1>{$user->username}{if MODULE_USER_RANK && $user->getUserTitle()} <span class="badge userTitleBadge{if $user->getRank() && $user->getRank()->cssClassName} {@$user->getRank()->cssClassName}{/if}">{$user->getUserTitle()}</span>{/if}</h1>
	</hgroup>
	
	<ul class="dataList">
		{if $user->gender}<li>{lang}wcf.user.gender.{if $user->gender == 1}male{else}female{/if}{/lang}</li>{/if}
		{if $user->getAge()}<li>{@$user->getAge()}</li>{/if}
		{if $user->location}<li>{lang}wcf.user.membersList.location{/lang}</li>{/if}
		{if $user->getOldUsername()}<li>{lang}wcf.user.profile.oldUsername{/lang}</li>{/if}
		<li>{lang}wcf.user.membersList.registrationDate{/lang}</li>
	</ul>
	<dl class="plain inlineDataList">
		<dt>{lang}wcf.user.usersOnline.lastActivity{/lang}</dt>
		<dd>{@$user->getLastActivityTime()|time}{if $user->getCurrentLocation()}, {@$user->getCurrentLocation()}{/if}</dd>
	</dl>
	
	<ul id="profileButtonContainer" class="buttonList">
	</ul>
</header>

{include file='userNotice'}

<section id="profileContent" class="marginTop tabMenuContainer" data-active="{$__wcf->getUserProfileMenu()->getActiveMenuItem()->getIdentifier()}">
	<nav class="tabMenu">
		<ul>
			{foreach from=$__wcf->getUserProfileMenu()->getMenuItems() item=menuItem}
				<li><a href="{$__wcf->getAnchor($menuItem->getIdentifier())}" title="{lang}{@$menuItem->menuItem}{/lang}">{lang}wcf.user.profile.menu.{@$menuItem->menuItem}{/lang}</a></li>
			{/foreach}
		</ul>
	</nav>
	
	{foreach from=$__wcf->getUserProfileMenu()->getMenuItems() item=menuItem}
		<div id="{$menuItem->getIdentifier()}" class="container tabMenuContent" data-menu-item="{$menuItem->menuItem}">
			{if $menuItem === $__wcf->getUserProfileMenu()->getActiveMenuItem()}
				{@$profileContent}
			{/if}
		</div>
	{/foreach}
</section>

{include file='footer'}

</body>
</html>
