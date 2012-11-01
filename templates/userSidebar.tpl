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
		
		{if $user->activityPoints}
			<dt class="jsOnly"><a class="activityPointsDisplay">{lang}wcf.user.activity.point{/lang}</a></dt>
			<dd class="jsOnly"><a class="activityPointsDisplay">{#$user->activityPoints}</a></dd>
		{/if}
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