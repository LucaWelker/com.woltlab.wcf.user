<ol id="recentActivity" class="recentActivity">
	{foreach from=$eventList item=event}
		{assign var=__dummy value=$event->userProfile->getAvatar()->setMaxSize(48, 48)}
		<li>
			<div>
				<p class="userAvatar">
					<a href="{link controller='User' object=$event->userProfile}{/link}">{@$event->userProfile->getAvatar()}</a>
				</p>
				<p>
					<span>{@$event->text}</span>
					<span>{@$event->time|time}</span>
				</p>
			</div>
		</li>
	{/foreach}
</ol>