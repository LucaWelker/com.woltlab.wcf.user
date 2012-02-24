<ol id="recentActivity" class="wcf-recentActivity">
	{foreach from=$eventList item=event}
		<li class="wcf-container">
			<a href="{link controller='User' object=$event->getUserProfile()}{/link}" title="{$event->getUserProfile()->username}" class="wcf-containerIcon wcf-userAvatarFramed">{@$event->getUserProfile()->getAvatar()->getImageTag(48)}</a>
			
			<div class="wcf-containerContent wcf-recentActivityContent">
				<p class="wcf-username"><a href="{link controller='User' object=$event->getUserProfile()}{/link}">{$event->getUserProfile()->username}</a> - {@$event->time|time}</p>

				{if $event->getIcon()}
					<p class="userActivityIcon"><img src="{@$event->getIcon()}" alt="" /></p>
				{/if}
				<p class="userActivityShort">{@$event->getTitle()}</p>
				<p class="userActivity">{@$event->getDescription()}</p>
			<div>
		</li>
	{/foreach}
</ol>
