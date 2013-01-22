<ul class="buttonList">
	{if $user->homepage}
		<li><a class="jsTooltip" href="{@$user->homepage}" title="{lang}wcf.user.option.homepage{/lang}"{if EXTERNAL_LINK_REL_NOFOLLOW} rel="nofollow"{/if}{if EXTERNAL_LINK_TARGET_BLANK} target="_blank"{/if}><span class="icon icon16 icon-home"></span></a></li>
	{/if}
	
	{if $user->userID != $__wcf->user->userID}
		{if $user->isAccessible('canViewEmailAddress')}
			<li><a class="jsTooltip" href="mailto:{@$user->getEncodedEmail()}" title="{lang}wcf.user.button.mail{/lang}"><span class="icon icon16 icon-envelope-alt"></span></a></li>
		{elseif $user->isAccessible('canMail') && $__wcf->session->getPermission('user.profile.canMail')}
			<li><a class="jsTooltip" href="{link controller='Mail' object=$user}{/link}" title="{lang}wcf.user.button.mail{/lang}"><span class="icon icon16 icon-envelope-alt"></span></a></li>
		{/if}
	{/if}
	
	{if $__wcf->user->userID && $user->userID != $__wcf->user->userID}
		{if $__wcf->getUserProfileHandler()->isFollowing($user->userID)}
			<li><a data-following="1" data-object-id="{@$user->userID}" class="jsFollowButton jsTooltip" title="{lang}wcf.user.button.unfollow{/lang}"><span class="icon icon16 icon-remove"></span></a></li>
		{else}
			<li><a data-following="0" data-object-id="{@$user->userID}" class="jsFollowButton jsTooltip" title="{lang}wcf.user.button.follow{/lang}"><span class="icon icon16 icon-plus"></span></a></li>
		{/if}
		
		{if $__wcf->getUserProfileHandler()->isIgnoredUser($user->userID)}
			<li><a data-ignored="1" data-object-id="{@$user->userID}" class="jsIgnoreButton jsTooltip" title="{lang}wcf.user.button.unignore{/lang}"><span class="icon icon16 icon-circle-blank"></span></a></li>
		{else}
			<li><a data-ignored="0" data-object-id="{@$user->userID}" class="jsIgnoreButton jsTooltip" title="{lang}wcf.user.button.ignore{/lang}"><span class="icon icon16 icon-off"></span></a></li>
		{/if}
	{/if}
	
	{event name='buttons'}
</ul>