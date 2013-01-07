<ul class="sidebarBoxList">
	{foreach from=$eventList item=event}
		<li class="box24">
			<a href="{link controller='User' object=$event->getUserProfile()}{/link}" title="{$event->getUserProfile()->username}" class="framed">{@$event->getUserProfile()->getAvatar()->getImageTag(24)}</a>
			
			<hgroup class="sidebarBoxHeadline">
				<h1><a href="{link controller='User' object=$event->getUserProfile()}{/link}" class="userLink" data-user-id="{@$event->getUserProfile()->userID}">{$event->getUserProfile()->username}</a><small> - {@$event->time|time}</small></h1> 
				<h2><small>{@$event->getTitle()}</small></h2>
			</hgroup>
		</li>
	{/foreach}
</ul>
