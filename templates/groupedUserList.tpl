<div class="contentNavigation"><div class="jsPagination"></div></div>

{foreach from=$groupedUsers item=group}
	{if $group}
		<header class="boxHeadline">
			<hgroup>
				<h1>{$group}</h1>
			</hgroup>
		</header>
	{/if}
	
	<div class="container">
		<ol class="containerList doubleColumned">
			{if $group|count}
				{foreach from=$group item=user}
					{include file='userListItem'}
				{/foreach}
			{else}
				<small>{$group->getNoUsersMessage()}</small>
			{/if}
		</ol>
	</div>
{/foreach}

<div class="contentNavigation"><div class="jsPagination"></div></div>