{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.usersOnline{/lang} - {PAGE_TITLE|language}</title>
	{include file='headInclude' sandbox=false}
</head>

<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>

{capture assign='sidebar'}
{*TODO: sidebar content*}
<nav id="sidebarContent" class="sidebarContent">
	<div>
		<fieldset>
			<legend>sort</legend>
			
			<form method="get" action="{link controller='UsersOnlineList'}{/link}">
				<input type="hidden" name="pageNo" value="{@$pageNo}" />
				
				<dl>
					<dt><label for="sortField">sortby</label></dt>
					<dd>
						<select id="sortField" name="sortField">
							<option value="username"{if $sortField == 'username'} selected="selected"{/if}>{lang}wcf.user.username{/lang}</option>
							<option value="registrationDate"{if $sortField == 'registrationDate'} selected="selected"{/if}>{lang}wcf.user.registrationDate{/lang}</option>
						</select>
						<select name="sortOrder">
							<option value="ASC"{if $sortOrder == 'ASC'} selected="selected"{/if}>{lang}wcf.global.sortOrder.ascending{/lang}</option>
							<option value="DESC"{if $sortOrder == 'DESC'} selected="selected"{/if}>{lang}wcf.global.sortOrder.descending{/lang}</option>
						</select>
					</dd>
				</dl>
				
				<div class="formSubmit">
					<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
				</div>
			</form>
		</fieldset>
	</div>
</nav>
{/capture}

{include file='header' sandbox=false sidebarOrientation='right'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.user.usersOnline{/lang} <span class="badge">{#$items}</span></h1>
	</hgroup>
</header>

{*TODO: group users*}
<div class="container marginTop shadow">
	<ol class="containerList userList simpleUserList">
		{foreach from=$objects item=user}
			<li>
				<div class="box48">
					{if $user->userID}
						<a href="{link controller='User' object=$user}{/link}" title="{$user->username}" class="framed">{@$user->getAvatar()->getImageTag(48)}</a>
						
						<div>
							{include file='userInformation' sandbox=false}
							
							{*TODO: current location, browser, ip address*}
							{if $user->requestURI}<p>request uri: {$user->requestURI}</p>{/if}
						</div>
					{else}
						{*TODO: guests / search robots*}
						<img src="" alt="" width="48" height="48" class="framed" />
						<div>
							<hgroup class="containerHeadline">
								<h1>Guest</h1> 
							</hgroup>
							
							{if $user->requestURI}<p>request uri: {$user->requestURI}</p>{/if}
						</div>
					{/if}
				</div>
			</li>
		{/foreach}
	</ol>
</div>

{include file='footer' sandbox=false}

</body>
</html>
