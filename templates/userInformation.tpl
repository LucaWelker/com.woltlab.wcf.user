{*TODO: show action buttons (follow, ignore etc.)*}
{*TODO: show user title / rank*}
<hgroup class="containerHeadline">
	<h1><a href="{link controller='User' object=$user}{/link}" title="{$user->username}">{$user->username}</a> <span class="badge">Administrator</span></h1> 
	<h2>{lang}wcf.user.membersList.registrationDate{/lang}{if $user->gender}, {lang}wcf.user.gender.{if $user->gender == 1}male{else}female{/if}{/lang}{/if}{if $user->getAge()}, {@$user->getAge()}{/if}{if $user->location}, {lang}wcf.user.membersList.location{/lang}{/if}</h2>
</hgroup>

<dl class="dataList">
	{event name='statistics'}
</dl>