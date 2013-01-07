<header class="boxHeadline boxSubHeadline">
	<hgroup>
		<h1>{lang}wcf.user.recentActivity{/lang}</h1>
		{if $filteredByFollowedUsers}<h2>{lang}wcf.user.recentActivity.filteredByFollowedUsers{/lang}</h2>{/if}
	</hgroup>
</header>

<div class="container marginTop">
	<ul id="recentActivities" class="containerList">
		{foreach from=$eventList item=event}
			<li>
				<div class="box48">
					<a href="{link controller='User' object=$event->getUserProfile()}{/link}" title="{$event->getUserProfile()->username}" class="framed">{@$event->getUserProfile()->getAvatar()->getImageTag(48)}</a>
					
					<div>
						<hgroup class="containerHeadline">
							<h1><a href="{link controller='User' object=$event->getUserProfile()}{/link}">{$event->getUserProfile()->username}</a><small> - {@$event->time|time}</small></h1> 
							<h2><strong>{@$event->getTitle()}</strong></h2>
						</hgroup>
						
						<p>{@$event->getDescription()}</p>
					</div>
				</div>
			</li>
		{/foreach}
	</ul>
</div>
