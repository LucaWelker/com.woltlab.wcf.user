{foreach from=$groupedUsers item=group}
	{if $group}
		<header class="boxHeadline">
			<hgroup>
				<h1>{$group}</h1>
			</hgroup>
		</header>
	{/if}
	
	{if $group|count}
		<div class="container marginTop">
			<ol class="containerList doubleColumned">
				{foreach from=$group item=user}
					{include file='userListItem'}
				{/foreach}
			</ol>
		</div>
	{else}
		<p class="marginTop">{$group->getNoUsersMessage()}</p>
	{/if}
{/foreach}

<div class="contentNavigation"><div class="jsPagination"></div></div>