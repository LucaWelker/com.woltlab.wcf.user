<ul class="buttonList">
	{if $user->homepage}
		<li><a class="jsTooltip" href="{@$user->homepage}" title="{lang}wcf.user.option.homepage{/lang}"><img src="{icon}home{/icon}" alt="" /></a></li>
	{/if}
	{if $__wcf->user->userID && $user->userID != $__wcf->user->userID}
		{if $__wcf->getUserProfileHandler()->isFollowing($user->userID)}
			<li><a data-following="1" data-object-id="{@$user->userID}" class="jsFollowButton jsTooltip" title="{lang}wcf.user.button.unfollow{/lang}"><img src="{icon}remove{/icon}" alt="" class="icon16" /></a></li>
		{else}
			<li><a data-following="0" data-object-id="{@$user->userID}" class="jsFollowButton jsTooltip" title="{lang}wcf.user.button.follow{/lang}"><img src="{icon}add{/icon}" alt="" class="icon16" /></a></li>
		{/if}
		
		{if $__wcf->getUserProfileHandler()->isIgnoredUser($user->userID)}
			<li><a data-ignored="1" data-object-id="{@$user->userID}" class="jsIgnoreButton jsTooltip" title="{lang}wcf.user.button.unignore{/lang}"><img src="{icon}enabled{/icon}" alt="" class="icon16" /></a></li>
		{else}
			<li><a data-ignored="0" data-object-id="{@$user->userID}" class="jsIgnoreButton jsTooltip" title="{lang}wcf.user.button.ignore{/lang}"><img src="{icon}disabled{/icon}" alt="" class="icon16" /></a></li>
		{/if}
	{/if}
	
	{event name='buttons'}
</ul>