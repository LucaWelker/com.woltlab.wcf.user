{capture assign='activityPoints'}
	{assign var='activityPointSum' value=0}
	{foreach from=$activityPointObjectTypes item='objectType'}
		{#$objectType->activityPoints/$objectType->points} × {$objectType->objectType} × {#$objectType->points} Points per {$objectType->objectType}  = {#$objectType->activityPoints}<br />
		
		{assign var='activityPointSum' value=$activityPointSum + $objectType->activityPoints}
	{/foreach}
	{if $user->activityPoints - $activityPointSum > 0} + {#$user->activityPoints - $activityPointSum} not in dependency list{/if}
	Σ {#$user->activityPoints}
{/capture}
{if $ajax}
{@$activityPoints}
{else}
	{include file='documentHeader'}
	
	<head>
		<title>ACTIVITY POINTZ - {lang}wcf.user.profile{/lang} - {lang}wcf.user.members{/lang} - {PAGE_TITLE|language}</title>
		{include file='headInclude'}
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
					<dt><a class="activityPointsDisplay" href="{link controller='DetailedActivityPointList' object=$user}{/link}">ACTIVITY POINTZ</a></dt>
					<dd><a class="activityPointsDisplay" href="{link controller='DetailedActivityPointList' object=$user}{/link}">{#$user->activityPoints}</a></dd>
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
	
	<header class="boxHeadline">
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
	</header>
	
	{include file='userNotice'}
	
	<div class="container marginTop shadow containerPadding">
		{@$activityPoints}
	</div>
	
	{include file='footer'}
	
	</body>
	</html>
{/if}