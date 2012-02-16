{include file='documentHeader'}

<head>
	<title>User profile page</title>
	{include file='headInclude' sandbox=false}

	<script type="text/javascript" src="{@$__wcf->getPath('wcf')}js/WCF.User.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			{if $__wcf->getUser()->userID != $user->userID}
				WCF.Language.addObject({
					'wcf.user.profile.followUser': 'follow',
					'wcf.user.profile.unfollowUser': 'unfollow',
					'wcf.user.profile.ignoreUser': 'ignore user',
					'wcf.user.profile.unignoreUser': 'unignore user'
				});

				new WCF.User.Profile.Follow({$user->userID}, {if $__wcf->getUserProfileHandler()->isFollowing($user->userID)}true{else}false{/if});
				new WCF.User.Profile.IgnoreUser({@$user->userID}, {if $__wcf->getUserProfileHandler()->isIgnoredUser($user->userID)}true{else}false{/if});
			{/if}

			new WCF.User.Profile.TabMenu({@$user->userID});

			WCF.TabMenu.init();

			{* TODO: Handle admin permissions *}
			{if $__wcf->getUser()->userID == $user->userID}
				WCF.User.Profile.Editor.Handler.init({$user->userID});
				new WCF.User.Profile.Editor.Information({@$overviewObjectType->objectTypeID});
			{/if}
		});
		//]]>
	</script>
</head>

<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>

{capture assign='sidebar'}

<nav id="sidebarContent" class="wcf-sidebarContent">
	{* user *}
	<div class="wcf-menuContainer">
		<h1 class="wcf-menuHeader">{$user->username}</h1>
		<div class="wcf-sidebarContentGroup">
			<ul>
				<li>
					{if $user->getAvatar()}
						<div class="userAvatarOriginal" title="{$user->username}">{@$user->getAvatar()->getImageTag()}</div>
					{/if}
				</li>
			</ul>
		</div>
	</div>
	
	{* following *}
	{if $followingCount}
	<div class="wcf-menuContainer userFollowing collapsibleMenus">
		<h1>Following <span class="wcf-badge">{#$followingCount}</span></h1>
		<div class="wcf-sidebarContentGroup">
			<ul>
				{foreach from=$following item=followingUser}
					<li class="userAvatar jsTooltip" title="{$followingUser->username}"><a href="{link controller='User' object=$followingUser}{/link}">{@$followingUser->getAvatar()->getImageTag(32)}</a></li>
				{/foreach}
			</ul>
			{if $followingCount > 1}
				<p><a id="followingAll" class="wcf-badge wcf-badgeButton javascriptOnly">Show all following</a></p>
			{/if}
		</div>
	</div>
	{/if}
	
	{* followers *}
	{if $followerCount}
	<div class="wcf-menuContainer userFollowers collapsibleMenus">
		<h1>Followers <span class="wcf-badge">{#$followerCount}</span></h1>
		<ul>
			{foreach from=$followers item=follower}
				<li class="userAvatar jsTooltip" title="{$follower->username}"><a href="{link controller='User' object=$follower}{/link}">{@$follower->getAvatar()->getImageTag(32)}</a></li>
			{/foreach}
		</ul>
		{if $followerCount > 1}
			<p><a id="followerAll" class="wcf-badge wcf-badgeButton javascriptOnly">Show all followers</a></p>
		{/if}
	</div>
	{/if}
				
	{* profile visitors *}
	
	{* placeholder *}
	
	{* collapse sidebar *}			
	<span class="wcf-collapsibleSidebarButton" title="{lang}wcf.global.button.collapsible{/lang}"><span></span></span>
</nav>

{/capture}

{include file='header' sandbox=false sidebarOrientation='left' skipBreadcrumbs=true}

<div class="wcf-contentHeader">
	<!-- ToDo: Wouldn't it be better to generate a Large Button List out of all that here? -->
	<nav id="profileButtonContainer">
	</nav>
</div>
	
<section id="profileContent" class="wcf-tabMenuContainer" data-active="{$__wcf->getUserProfileMenu()->getActiveMenuItem()->getIdentifier()}">
	<nav class="wcf-tabMenu">
		<ul>
			{foreach from=$__wcf->getUserProfileMenu()->getMenuItems() item=menuItem}
				<li><a href="#{$menuItem->getIdentifier()}" title="{lang}{@$menuItem->menuItem}{/lang}">{lang}{@$menuItem->menuItem}{/lang}</a></li>
			{/foreach}
		</ul>
	</nav>

	{foreach from=$__wcf->getUserProfileMenu()->getMenuItems() item=menuItem}
		<div id="{$menuItem->getIdentifier()}" class="wcf-border wcf-tabMenuContent" data-menu-item="{$menuItem->menuItem}">
			{if $menuItem === $__wcf->getUserProfileMenu()->getActiveMenuItem()}
				{@$profileContent}
			{/if}
		</div>
	{/foreach}
</section>

<div class="wcf-contentFooter">
	<!-- ToDo -->
</div>

{include file='footer' sandbox=false skipBreadcrumbs=true}

</body>
</html>
