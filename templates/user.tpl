{include file='documentHeader'}

<head>
	<title>User profile page</title>
	{include file='headInclude' sandbox=false}

	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/WCF.User.Profile.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			WCF.Language.addObject({
				'wcf.user.profile.friend.acceptRequest': 'accept friend request',
				'wcf.user.profile.friend.cancelRequest': 'cancel friend request',
				'wcf.user.profile.friend.createRequest': 'create friend request',
				'wcf.user.profile.friend.deleteFriend': 'evaporate friend',
				'wcf.user.profile.friend.ignoreRequest': 'ignore friend request',
				'wcf.user.profile.friend.rejectRequest': 'reject friend request',
				'wcf.user.profile.ignoreUser': 'ignore user',
				'wcf.user.profile.unignoreUser': 'unignore user'
			});

			var $isFriend = {if $__wcf->getUserProfileHandler()->isFriend($user->userID)}true{else}false{/if};
			var $isRequestedFriend = {if $__wcf->getUserProfileHandler()->isRequestedFriend($user->userID)}true{else}false{/if};
			var $isRequestingFriend = {if $__wcf->getUserProfileHandler()->isRequestingFriend($user->userID)}true{else}false{/if};
			
			new WCF.User.Profile.Friend({$user->userID}, $isFriend, $isRequestedFriend, $isRequestingFriend);
			new WCF.User.Profile.IgnoreUser({@$user->userID}, {if $__wcf->getUserProfileHandler()->isIgnoredUser($user->userID)}true{else}false{/if});
		});
		//]]>
	</script>
	<style type="text/css">
		div#profileButtonContainer {
			margin: 7px;
		}

		div#profileButtonContainer button {
			background-image: -o-linear-gradient(top, rgb(192, 192, 192), rgb(224, 224, 224));
			border: 1px solid rgb(192, 192, 192);
			border-radius: 3px;
			cursor: pointer;
			margin-right: 3px;
			height: 60px;
			padding: 3px;
		}

		div#profileButtonContainer button:hover {
			background-image: -o-linear-gradient(top, rgb(224, 224, 224), rgb(192, 192, 192));
		}
	</style>
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

<div id="profileButtonContainer"></div>

<button id="ignoreUser">{if $__wcf->getUserProfileHandler()->isIgnoredUser($user->userID)}un{/if}ignore user</button>

{include file='footer' sandbox=false}

</body>
</html>
