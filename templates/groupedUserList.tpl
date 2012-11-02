<div class="jsPagination"></div>

{foreach from=$groupedUsers item=group}
	{if $group}
		<header class="boxHeadline">
			<hgroup>
				<h1>{$group}</h1>
			</hgroup>
		</header>
	{/if}
	
	<ol class="containerList doubleColumned">
		{if $group|count}
			{foreach from=$group item=user}
				{include file='userListItem'}
			{/foreach}
		{else}
			<small>{$group->getNoUsersMessage()}</small>
		{/if}
	</ol>
{/foreach}

<div class="jsPagination"></div>