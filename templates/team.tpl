{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.team{/lang} - {PAGE_TITLE|language}</title>
	
	{include file='headInclude'}
	
	<script type="text/javascript">
		//<![CDATA[
			$(function() {
				WCF.Icon.addObject({
					'wcf.icon.add': '{icon}add{/icon}',
					'wcf.icon.enabled': '{icon}enabled{/icon}',
					'wcf.icon.disabled': '{icon}disabled{/icon}',
					'wcf.icon.remove': '{icon}remove{/icon}'
				})
				
				WCF.Language.addObject({
					'wcf.user.button.follow': '{lang}wcf.user.button.follow{/lang}',
					'wcf.user.button.ignore': '{lang}wcf.user.button.ignore{/lang}',
					'wcf.user.button.unfollow': '{lang}wcf.user.button.unfollow{/lang}',
					'wcf.user.button.unignore': '{lang}wcf.user.button.unignore{/lang}'
				})
				
				new WCF.User.Action.Follow($('.userList > li'));
				new WCF.User.Action.Ignore($('.userList > li'));
			});
		//]]>
	</script>
</head>

<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>

{include file='header' sidebarOrientation='right'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.user.team{/lang}</h1>
	</hgroup>
</header>

{include file='userNotice'}

{foreach from=$objects->getTeams() item=team}
	<header class="boxHeadline boxSubHeadline">
		<hgroup>
			<h1>{$team->groupName|language} <span class="badge">{#$team->getMembers()|count}</span></h1>
		</hgroup>
	</header>
		
	<div class="container marginTop">
		<ol class="containerList doubleColumned userList">
			{foreach from=$team->getMembers() item=user}
				{include file='userListItem'}
			{/foreach}
		</ol>
	</div>
{/foreach}

{include file='footer'}

</body>
</html>
