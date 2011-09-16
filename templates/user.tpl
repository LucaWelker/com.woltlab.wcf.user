{include file='documentHeader'}

<head>
	<title>User profile page</title>
	{include file='headInclude' sandbox=false}

	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/WCF.User.Profile.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			WCF.Language.addObject({
				'wcf.user.profile.ignoreUser': 'ignore user',
				'wcf.user.profile.unignoreUser': 'unignore user'
			});

			new WCF.User.Profile.IgnoreUser({@$user->userID}, {if $__wcf->getUserProfileHandler()->isIgnoredUser($user->userID)}true{else}false{/if});
		});
		//]]>
	</script>
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

<p>Hello {if $__wcf->user->userID}{$__wcf->user->username}{else}guest{/if}</p>

<p>You have {#$__wcf->getUserNotificationHandler()->getNotificationCount()} outstanding notifications!</p>

<p>{$user->username}</p>

{if $__wcf->getUserProfileHandler()->isFriend($user->userID)}
	- delete friend
{else}
	{if $__wcf->getUserProfileHandler()->isRequestedFriend($user->userID)}
		<button id="foobar">cancel friend request</button>
		<script type="text/javascript">
		//<![CDATA[
		$(function() {
			new WCF.Action.SimpleProxy({
				action: 'cancel',
				className: 'wcf\\data\\user\\friend\\request\\UserFriendRequestAction',
				elements: $('#foobar')
			}, {
				init: function(proxy) {
					proxy.options.data['parameters'] = {
						friendUserID: {@$user->userID}
					};
				},
				success: function(data, statusText, jqXHR) {
					alert('success');
				}
			});
		});
		//]]>
		</script>
	{else}
		{if $__wcf->getUserProfileHandler()->isRequestingFriend($user->userID)}
			- accept friend request
			- reject friend request
			- ignore friend request
		{else}
			<button id="foobar">create friend request</button>
			<script type="text/javascript">
			//<![CDATA[
			$(function() {
				new WCF.Action.SimpleProxy({
					action: 'create',
					className: 'wcf\\data\\user\\friend\\request\\UserFriendRequestAction',
					elements: $('#foobar')
				}, {
					init: function(proxy) {
						proxy.options.data['parameters'] = {
							data: {
								friendUserID: {@$user->userID}
							}
						};
					},
					success: function(data, statusText, jqXHR) {
						alert('success');
					}
				});
			});
			//]]>
		</script>
		{/if}
	{/if}
{/if}

<button id="ignoreUser">{if $__wcf->getUserProfileHandler()->isIgnoredUser($user->userID)}un{/if}ignore user</button>

{include file='footer' sandbox=false}

</body>
</html>
