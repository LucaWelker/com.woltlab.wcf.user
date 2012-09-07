{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.usersOnline{/lang} - {PAGE_TITLE|language}</title>
	{include file='headInclude'}
</head>

<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>

{capture assign='sidebar'}
{*TODO: sidebar content*}
<nav id="sidebarContent" class="sidebarContent">
	<ul>
		<li class="sidebarContainer">
			<form method="get" action="{link controller='UsersOnlineList'}{/link}">
				<fieldset>
					<legend>{lang}wcf.user.members.sort{/lang}</legend>
					
					<dl>
						<dd>
							<select id="sortField" name="sortField">
								<option value="username"{if $sortField == 'username'} selected="selected"{/if}>{lang}wcf.user.username{/lang}</option>
								<option value="lastActivityTime"{if $sortField == 'lastActivityTime'} selected="selected"{/if}>{lang}wcf.user.usersOnline.lastActivity{/lang}</option>
								<option value="requestURI"{if $sortField == 'requestURI'} selected="selected"{/if}>{lang}wcf.user.usersOnline.location{/lang}</option>
								
								{if $__wcf->session->getPermission('admin.user.canViewIpAddress')}
									<option value="ipAddress"{if $sortField == 'ipAddress'} selected="selected"{/if}>{lang}wcf.user.usersOnline.ipAddress{/lang}</option>
									<option value="userAgent"{if $sortField == 'userAgent'} selected="selected"{/if}>{lang}wcf.user.usersOnline.userAgent{/lang}</option>
								{/if}
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
				</fieldset>
			</form>
		</li>
	</ul>
</nav>
{/capture}

{include file='header' sidebarOrientation='right'}

{include file='userNotice'}

{assign var=usersOnlineList value=''}
{assign var=usersOnline value=0}
{assign var=robotsOnlineList value=''}
{assign var=robotsOnline value=0}
{assign var=guestsOnlineList value=''}
{assign var=guestsOnline value=0}
{foreach from=$objects item=user}
	{capture assign=sessionData}
		{if $user->getLocation()}
			<dl class="inlineDataList">
				<dt>{lang}wcf.user.usersOnline.location{/lang}</dt>
				<dd>{@$user->getLocation()}</dd>
			</dl>
		{/if}
		<dl class="inlineDataList">
			<dt>{lang}wcf.user.usersOnline.lastActivity{/lang}</dt>
			<dd>{@$user->lastActivityTime|time}</dd>
		</dl>
		
		{if $__wcf->session->getPermission('admin.user.canViewIpAddress')}
			<dl class="inlineDataList">
				<dt>{lang}wcf.user.usersOnline.ipAddress{/lang}</dt>
				<dd>{$user->getFormattedIPAddress()}</dd>
				<dt>{lang}wcf.user.usersOnline.userAgent{/lang}</dt>
				<dd title="{$user->userAgent}">{$user->getBrowser()}</dd>
			</dl>
		{/if}
	{/capture}
	
	{if $user->userID}
		{* member *}
		{capture append=usersOnlineList}
			<li>
				<div class="box48">
					<a href="{link controller='User' object=$user}{/link}" title="{$user->username}" class="framed">{@$user->getAvatar()->getImageTag(48)}</a>
					
					<div class="userInformation">
						<hgroup class="containerHeadline">
							<h1><a href="{link controller='User' object=$user}{/link}">{@$user->getFormattedUsername()}</a>{if MODULE_USER_RANK && $user->getUserTitle()} <span class="badge userTitleBadge{if $user->getRank() && $user->getRank()->cssClassName} {@$user->getRank()->cssClassName}{/if}">{$user->getUserTitle()}</span>{/if}</h1> 
							<h2>{@$sessionData}</h2>
						</hgroup>
						
						{include file='userInformationButtons'}
					</div>
				</div>
			</li>
		{/capture}
	
		{assign var=usersOnline value=$usersOnline+1}
	{elseif $user->spiderID}
		{* search robot *}
		{capture append=robotsOnlineList}
			<li>
				<div class="box48">
					{*todo: we need an avatar placeholder for search robots here*}
					<p class="framed"><img src="{$__wcf->getPath()}images/avatars/avatar-default.svg" alt="" class="icon48" /></p>
					
					<div class="userInformation">
						<hgroup class="containerHeadline">
							<h1><a href="{link controller='User' object=$user}{/link}" class="userLink" data-user-id="{@$user->userID}">Robot</a></h1> 
							<h2>{@$sessionData}</h2>
						</hgroup>
					</div>
				</div>
			</li>
		{/capture}
	
		{assign var=robotsOnline value=$robotsOnline+1}
	{else}
		{* unregistered *}
		{capture append=guestsOnlineList}
			<li>
				<div class="box48">
					{*todo: we need an avatar placeholder for guests here*}
					<p class="framed"><img src="{$__wcf->getPath()}images/avatars/avatar-default.svg" alt="" class="icon48" /></p>
					
					<div class="userInformation">
						<hgroup class="containerHeadline">
							<h1>{lang}wcf.user.guest{/lang}</h1> 
							<h2>{@$sessionData}</h2>
						</hgroup>
					</div>
				</div>
			</li>
		{/capture}
	
		{assign var=guestsOnline value=$guestsOnline+1}
	{/if}
{/foreach}

{if $usersOnline}
	<header class="boxHeadline">
		<hgroup>
			<h1>{lang}wcf.user.usersOnline{/lang} <span class="badge">{#$usersOnline}</span></h1>
		</hgroup>
	</header>
	
	<div class="container marginTop shadow">
		<ol class="containerList userList simpleUserList">
			{@$usersOnlineList}
		</ol>
	</div>
{/if}

{if $guestsOnline}
	<header class="boxHeadline">
		<hgroup>
			<h1>{lang}wcf.user.usersOnline.guests{/lang} <span class="badge">{#$guestsOnline}</span></h1>
		</hgroup>
	</header>
	
	<div class="container marginTop shadow">
		<ol class="containerList userList simpleUserList">
			{@$guestsOnlineList}
		</ol>
	</div>
{/if}

{if $robotsOnline}
	<header class="boxHeadline">
		<hgroup>
			<h1>{lang}wcf.user.usersOnline.robots{/lang} <span class="badge">{#$robotsOnline}</span></h1>
		</hgroup>
	</header>
	
	<div class="container marginTop shadow">
		<ol class="containerList userList simpleUserList">
			{@$robotsOnlineList}
		</ol>
	</div>
{/if}

{include file='footer'}

</body>
</html>
