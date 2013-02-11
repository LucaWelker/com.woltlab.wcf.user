<ul class="sidebarBoxList">
	{foreach from=$mostActiveMembers item=activeMember}
		<li class="box24">
			<a href="{link controller='User' object=$activeMember}{/link}" class="framed">{@$activeMember->getAvatar()->getImageTag(24)}</a>
			
			<hgroup class="sidebarBoxHeadline">
				<h1><a href="{link controller='User' object=$activeMember}{/link}" class="userLink" data-user-id="{@$activeMember->userID}">{$activeMember->username}</a></h1>
				<h2><small>{#$activeMember->activityPoints} {lang}wcf.user.activityPoint{/lang}</small></h2>
			</hgroup>
		</li>
	{/foreach}
</ul>