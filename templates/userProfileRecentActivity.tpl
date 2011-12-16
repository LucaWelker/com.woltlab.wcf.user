<ol id="recentActivity" class="recentActivity">
	{foreach from=$eventList item=event}
		{assign var=__dummy value=$event->userProfile->getAvatar()->setMaxSize(48, 48)}
		<li>
			<a href="{link controller='User' object=$event->userProfile}{/link}" title="{$event->getUserProfile()->username}" class="userAvatar">{@$event->userProfile->getAvatar()}</a>
			
			<div class="recentActivityContent">
				<p class="userName"><a href="{link controller='User' id=$event->userID}{/link}">{$event->getUserProfile()->username}</a> - {@$event->time|time}</p>
				<p class="userActivity">{@$event->text}</p>
			<div>
		</li>
	{/foreach}
</ol>