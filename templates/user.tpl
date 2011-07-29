{include file='documentHeader'}
<head>
	<title>User profile page</title>
	
	{include file='headInclude' sandbox=false}
</head>
<body>
{include file='header' sandbox=false}

<ul>
	{foreach from=$__wcf->getBreadcrumbs()->get() item=$breadcrumb}
		<li>
			{if $breadcrumb->getURL()}<a href="{$breadcrumb->getURL()}">{/if}<span>{$breadcrumb->getLabel()}</span>{if $breadcrumb->getURL()}</a>{/if} &raquo;
		</li>
	{/foreach}
</ul>

<p>Welcome {if $__wcf->user->userID}{$__wcf->user->username}{else}guest{/if}</p>

<p>{$user->username}</p>


{if $__wcf->getUserProfileHandler()->isFriend($user->userID)}
	- delete friend
{else}
	{if $__wcf->getUserProfileHandler()->isRequestedFriend($user->userID)}
		- cancel friend request
	{else}
		{if $__wcf->getUserProfileHandler()->isRequestingFriend($user->userID)}
			- accept friend request
			- reject friend request
			- ignore friend request
		{else}
			- create friend request
		{/if}
	{/if}
{/if}

{include file='footer' sandbox=false}

</body>
</html>