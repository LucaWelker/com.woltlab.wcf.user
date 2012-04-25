<hgroup class="containerHeadline">
	<h1><a href="{link controller='User' object=$user}{/link}" class="userLink" data-user-id="{@$user->userID}">{$user->username}</a> <span class="badge">Administrator{*TODO: show user title / rank*}</span></h1> 
	<h2><ul class="dataList">
		{if $user->gender}<li>{lang}wcf.user.gender.{if $user->gender == 1}male{else}female{/if}{/lang}</li>{/if}
		{if $user->getAge()}<li>{@$user->getAge()}</li>{/if}
		{if $user->location}<li>{lang}wcf.user.membersList.location{/lang}</li>{/if}
		<li>{lang}wcf.user.membersList.registrationDate{/lang}</li>
	</ul></h2>
</hgroup>

{*TODO: show action buttons (follow, ignore etc.)*}
<ul class="buttonList">
	<li><a class="jsTooltip" title="Homepage"><img src="{icon}home1{/icon}" alt="" /></a></li>
	<li><a class="jsTooltip" title="Follow"><img src="{icon}add1{/icon}" alt="" /></a></li>
	<li><a class="jsTooltip" title="Start conversation"><img src="{icon}message1{/icon}" alt="" /></a></li>
	<li><a class="jsTooltip" title="Search"><img src="{icon}search1{/icon}" alt="" /></a></li>
</ul>

<dl class="inlineDataList">
	{event name='statistics'}
</dl>