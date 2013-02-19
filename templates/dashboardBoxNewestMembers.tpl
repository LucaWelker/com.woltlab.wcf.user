<ul class="sidebarBoxList">
	{foreach from=$newestMembers item=newMember}
		<li class="box24">
			<a href="{link controller='User' object=$newMember}{/link}" class="framed">{@$newMember->getAvatar()->getImageTag(24)}</a>
			
			<hgroup class="sidebarBoxHeadline">
				<h1><a href="{link controller='User' object=$newMember}{/link}" class="userLink" data-user-id="{@$newMember->userID}">{$newMember->username}</a></h1>
				<h2><small>{@$newMember->registrationDate|time}</small></h2>
			</hgroup>
		</li>
	{/foreach}
</ul>