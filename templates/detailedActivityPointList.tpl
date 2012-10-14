{capture assign='activityPoints'}
	<div id="userTableContainer" class="tabularBox marginTop shadow">
		<table class="table jsClipboardContainer">
			<thead>
				<tr>
					<th>{lang}wcf.user.activity.point.objects{/lang}</th>
					<th>{lang}wcf.user.activity.point.objectType{/lang}</th>
					<th>{lang}wcf.user.activity.point.pointsPerObject{/lang}</th>
					<th>{lang}wcf.user.activity.point.sum{/lang}</th>
				</tr>
			</thead>
			<tbody>
				{assign var='activityPointSum' value=0}
				{foreach from=$activityPointObjectTypes item='objectType'}
					{if $objectType->activityPoints > 0 && $objectType->points > 0}
						<tr>
							<td class="columnText">
								{#$objectType->activityPoints/$objectType->points} ×
							</td>
							<td class="columnTitle">
								{lang}wcf.user.activity.point.objectType.{$objectType->objectType}{/lang}
							</td>
							<td class="columnDigits">
								{#$objectType->points}
							</td>
							<td class="columnDigits">
								{#$objectType->activityPoints}
							</td>
							{assign var='activityPointSum' value=$activityPointSum + $objectType->activityPoints}
						</tr>
					{/if}
				{/foreach}
				
				{if $user->activityPoints - $activityPointSum > 0}
					<tr>
						<td class="columnTitle right" colspan="3">{lang}wcf.user.activity.point.notInDependency{/lang}</td>
						<td class="columnDigits">{#$user->activityPoints - $activityPointSum}</td>
					</tr>
				{/if}
				<tr>
					<td class="columnTitle focus right" colspan="3">Σ</td>
					<td class="columnDigits focus"><span class="badge">{#$user->activityPoints}</span></td>
				</tr>
			</tbody>
		</table>
	</div>
{/capture}
{if $ajax}
{@$activityPoints}
{else}
	{include file='documentHeader'}
	
	<head>
		<title>{lang}wcf.user.activity.point{/lang} - {lang}wcf.user.profile{/lang} - {lang}wcf.user.members{/lang} - {PAGE_TITLE|language}</title>
		{include file='headInclude'}
	</head>
	<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
	
	{capture assign='sidebar'}
		<fieldset>
			<legend class="invisible">user avatar {* @todo: language variable*}</legend>
			
			<div class="userAvatar">{@$user->getAvatar()->getImageTag()}</div>
		</fieldset>
		
		<fieldset>
			<legend class="invisible">stats {* @todo: language variable*}</legend>
			
			<dl class="plain statsDataList">
				{event name='statistics'}
				
				<dt>{lang}wcf.user.profileHits{/lang}</dt>
				<dd{if $user->getProfileAge() > 1} title="{lang}wcf.user.profileHits.hitsPerDay{/lang}"{/if}>{#$user->profileHits}</dd>
				
				<dt><a class="activityPointsDisplay" href="{link controller='DetailedActivityPointList' object=$user}{/link}">{lang}wcf.user.activity.point{/lang}</a></dt>
				<dd><a class="activityPointsDisplay" href="{link controller='DetailedActivityPointList' object=$user}{/link}">{#$user->activityPoints}</a></dd>
			</dl>
		</fieldset>
		
		{if $followingCount}
			<fieldset>
				<legend>{lang}wcf.user.profile.following{/lang} <span class="badge">{#$followingCount}</legend>
				
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
			</fieldset>
		{/if}
		
		{if $followerCount}
			<fieldset>
				<legend>{lang}wcf.user.profile.followers{/lang} <span class="badge">{#$followerCount}</legend>
				
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
			</fieldset>
		{/if}
		
		{if $visitorCount}
			<fieldset>
				<legend>{lang}wcf.user.profile.visitors{/lang} <span class="badge">{#$visitorCount}</span></legend>
				
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
			</fieldset>
		{/if}
		
		{* @todo: placeholder *}
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
	
	{@$activityPoints}
	
	{include file='footer'}
	
	</body>
	</html>
{/if}