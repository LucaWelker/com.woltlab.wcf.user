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
		<h1 class="wcf-menuHeader wcf-username">{$user->username}</h1>
		<div class="wcf-sidebarContentGroup">
			<ul>
				<li>
					{if $user->getAvatar()}
						<div class="wcf-userAvatar" title="{$user->username}">{@$user->getAvatar()->getImageTag()}</div>
					{/if}
				</li>
			</ul>
		</div>
	</div>
	
	{* following *}
	{if $followingCount}
	<div class="wcf-menuContainer userFollowing">
		<h1>{lang}wcf.user.profile.following{/lang} <span class="wcf-badge">{#$followingCount}</span></h1>
		<div class="wcf-sidebarContentGroup">
			<ul>
				{foreach from=$following item=followingUser}
					<li><a href="{link controller='User' object=$followingUser}{/link}" title="{$followingUser->username}" class="wcf-userAvatarFramed jsTooltip">{@$followingUser->getAvatar()->getImageTag(32)}</a></li>
				{/foreach}
			</ul>
			{if $followingCount > 0}
				<p><a id="followingAll" class="wcf-badge wcf-badgeButton javascriptOnly">{lang}wcf.user.profile.following.all{/lang}</a></p>
			{/if}
		</div>
	</div>
	{/if}
	
	{* followers *}
	{if $followerCount}
	<div class="wcf-menuContainer userFollowers">
		<h1>{lang}wcf.user.profile.followers{/lang} <span class="wcf-badge">{#$followerCount}</span></h1>
		<div class="wcf-sidebarContentGroup">
			<ul>
				{foreach from=$followers item=follower}
					<li><a href="{link controller='User' object=$follower}{/link}" title="{$follower->username}" class="wcf-userAvatarFramed jsTooltip">{@$follower->getAvatar()->getImageTag(32)}</a></li>
				{/foreach}
			</ul>
			{if $followerCount > 0}
				<p><a id="followerAll" class="wcf-badge wcf-badgeButton javascriptOnly">{lang}wcf.user.profile.followers.all{/lang}</a></p>
			{/if}
		</div>
	</div>
	{/if}
				
	{* profile visitors *}
	
	{* placeholder *}
	
	{* collapse sidebar *}			
	<span class="wcf-collapsibleSidebarButton" title="{lang}wcf.global.button.collapsible{/lang}"><span></span></span>
</nav>

{/capture}

{include file='header' sandbox=false sidebarOrientation='left'}

<div class="wcf-contentHeader">
	<nav>
		<ul class="wcf-largeButtons" id="profileButtonContainer">
		
		</ul>
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
		<div id="{$menuItem->getIdentifier()}" class="wcf-box wcf-boxPadding wcf-tabMenuContent wcf-shadow1" data-menu-item="{$menuItem->menuItem}">
			{if $menuItem === $__wcf->getUserProfileMenu()->getActiveMenuItem()}
				{@$profileContent}
			{/if}
		</div>
	{/foreach}
</section>

<div class="wcf-contentFooter">
	<!-- ToDo -->
</div>

{include file='footer' sandbox=false}

</body>
</html>
