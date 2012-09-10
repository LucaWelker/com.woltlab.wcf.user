{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.profile{/lang} - {lang}wcf.user.members{/lang} - {PAGE_TITLE|language}</title>
	{include file='headInclude'}
	
	<script type="text/javascript" src="{@$__wcf->getPath('wcf')}js/WCF.User.js"></script>
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
		});
		//]]>
	</script>
</head>

<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>

{capture assign='sidebar'}

<nav id="sidebarContent" class="sidebarContent">
	<ul>
		<li class="sidebarContainer">
			<div class="userAvatar">{@$user->getAvatar()->getImageTag()}</div>
		</li>
		
		<li class="sidebarContainer">
			<dl class="statsDataList">
				{event name='statistics'}
				
				<dt>{lang}wcf.user.profileHits{/lang}</dt>
				<dd{if $user->getProfileAge() > 1} title="{lang}wcf.user.profileHits.hitsPerDay{/lang}"{/if}>{#$user->profileHits}</dd>
			</dl>
		</li>
		
		{if $followingCount}
			<li class="sidebarContainer">
				<hgroup class="sidebarContainerHeadline">
					<h1>{lang}wcf.user.profile.following{/lang} <span class="badge">{#$followingCount}</span></h1>
				</hgroup>
				
				<div>
					<ul class="framedIconList">
						{foreach from=$following item=followingUser}
							<li><a href="{link controller='User' object=$followingUser}{/link}" title="{$followingUser->username}" class="framed jsTooltip">{@$followingUser->getAvatar()->getImageTag(48)}</a></li>
						{/foreach}
					</ul>
					
					{if $followingCount > 10}
						<a id="followingAll" class="button more javascriptOnly">{lang}wcf.user.profile.following.all{/lang}</a>
					{/if}
				</div>
			</li>
		{/if}
		
		{if $followerCount}
			<li class="sidebarContainer">
				<hgroup class="sidebarContainerHeadline">
					<h1>{lang}wcf.user.profile.followers{/lang} <span class="badge">{#$followerCount}</span></h1>
				</hgroup>
				
				<div>
					<ul class="framedIconList">
						{foreach from=$followers item=follower}
							<li><a href="{link controller='User' object=$follower}{/link}" title="{$follower->username}" class="framed jsTooltip">{@$follower->getAvatar()->getImageTag(48)}</a></li>
						{/foreach}
					</ul>
						
					{if $followerCount > 10}
						<a id="followerAll" class="button more javascriptOnly">{lang}wcf.user.profile.followers.all{/lang}</a>
					{/if}
				</div>
			</li>
		{/if}
		
		{if $visitorCount}
			<li class="sidebarContainer">
				<hgroup class="sidebarContainerHeadline">
					<h1>{lang}wcf.user.profile.visitors{/lang} <span class="badge">{#$visitorCount}</span></h1>
				</hgroup>
				
				<div>
					<ul class="framedIconList">
						{foreach from=$visitors item=visitor}
							<li><a href="{link controller='User' object=$visitor}{/link}" title="{$visitor->username} ({@$visitor->time|plainTime})" class="framed jsTooltip">{@$visitor->getAvatar()->getImageTag(48)}</a></li>
						{/foreach}
					</ul>
						
					{if $visitorCount > 10}
						<a id="followerAll" class="button more javascriptOnly">{lang}wcf.user.profile.visitors.all{/lang}</a>
					{/if}
				</div>
			</li>
		{/if}
		
		{* placeholder *}
	</ul>
</nav>

{/capture}

{include file='header' sidebarOrientation='left'}

<header class="boxHeadline userHeadline">
	<hgroup>
		<h1>{$user->username}{if MODULE_USER_RANK && $user->getUserTitle()} <span class="badge userTitleBadge{if $user->getRank() && $user->getRank()->cssClassName} {@$user->getRank()->cssClassName}{/if}">{$user->getUserTitle()}</span>{/if}</h1>
		<h2><ul class="dataList">
			{if $user->gender}<li>{lang}wcf.user.gender.{if $user->gender == 1}male{else}female{/if}{/lang}</li>{/if}
			{if $user->getAge()}<li>{@$user->getAge()}</li>{/if}
			{if $user->location}<li>{lang}wcf.user.membersList.location{/lang}</li>{/if}
			<li>{lang}wcf.user.membersList.registrationDate{/lang}</li>
		</ul></h2>
		<h3>{*TODO: last activity*}Letzte Aktivitaet: {@TIME_NOW|time}, Benutzerprofil von: Marcel Werk</h3>
	</hgroup>
	
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
		<div id="{$menuItem->getIdentifier()}" class="container tabMenuContent shadow" data-menu-item="{$menuItem->menuItem}">
			{if $menuItem === $__wcf->getUserProfileMenu()->getActiveMenuItem()}
				{@$profileContent}
			{/if}
		</div>
	{/foreach}
</section>

{include file='footer'}

</body>
</html>
