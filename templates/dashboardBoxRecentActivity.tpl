{hascontent}
	<ul>
		{foreach from=$recentActivityList item=recentActivityEvent}
			<li class="sidebarBox box24">
				<a href="{link controller='User' object=$recentActivityEvent->getUserProfile()}{/link}" title="{$recentActivityEvent->getUserProfile()->username}" class="framed">{@$recentActivityEvent->getUserProfile()->getAvatar()->getImageTag(24)}</a>
				
				<hgroup class="sidebarBoxHeadline">
					<h1><a href="{link controller='User' object=$recentActivityEvent->getUserProfile()}{/link}" class="userLink" data-user-id="{@$recentActivityEvent->getUserProfile()->userID}">{$recentActivityEvent->getUserProfile()->username}</a><small> - {@$recentActivityEvent->time|time}</small></h1> 
					<h2><small>{@$recentActivityEvent->getTitle()}</small></h2>
				</hgroup>
			</li>
		{/foreach}
	</ul>
{/hascontent}