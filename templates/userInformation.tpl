<hgroup class="containerHeadline">
	<h1><a href="{link controller='User' object=$user}{/link}">{$user->username}</a>{if MODULE_USER_RANK && $user->getUserTitle()} <span class="badge userTitleBadge{if $user->getRank() && $user->getRank()->cssClassName} {@$user->getRank()->cssClassName}{/if}">{$user->getUserTitle()}</span>{/if}</h1> 
	<h2><ul class="dataList">
		{if $user->gender}<li>{lang}wcf.user.gender.{if $user->gender == 1}male{else}female{/if}{/lang}</li>{/if}
		{if $user->getAge()}<li>{@$user->getAge()}</li>{/if}
		{if $user->location}<li>{lang}wcf.user.membersList.location{/lang}</li>{/if}
		<li>{lang}wcf.user.membersList.registrationDate{/lang}</li>
	</ul></h2>
</hgroup>

{include file='userInformationButtons'}

<dl class="plain inlineDataList">
	{event name='statistics'}
	
	<dt>{lang}wcf.user.activityPoints{/lang}</dt>
	<dd>{#$user->activityPoints}</dd>
</dl>