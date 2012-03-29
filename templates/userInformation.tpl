{*TODO: show action buttons (follow, ignore etc.)*}
{*TODO: show user title / rank*}
<hgroup class="containerHeadline">
	<h1><a href="{link controller='User' object=$user}{/link}" title="{$user->username}">{$user->username}</a> <span class="badge">Administrator</span></h1> 
	<h2><ul class="dataList">
		{if $user->gender}<li>{lang}wcf.user.gender.{if $user->gender == 1}male{else}female{/if}{/lang}</li>{/if}
		{if $user->getAge()}<li>{@$user->getAge()}</li>{/if}
		{if $user->location}<li>{lang}wcf.user.membersList.location{/lang}</li>{/if}
		<li>{lang}wcf.user.membersList.registrationDate{/lang}</li>
	</ul></h2>
</hgroup>

<dl class="dataList">
	{event name='statistics'}
</dl>