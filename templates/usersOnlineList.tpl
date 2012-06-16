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
					<legend>sort</legend>
					
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
		{*TODO: current location, browser, ip address*}
		<dl class="inlineDataList">
			<dt>request uri</dt>
			<dd>{$user->requestURI}</dd>
			<dt>ip</dt>
			<dd>{$user->ipAddress}</dd>
			<dt>user agent</dt>
			<dd>{$user->userAgent}</dd>
		</dl>
	{/capture}
	
	{if $user->userID}
		{* member *}
		{capture append=usersOnlineList}
			<li>
				<div class="box48">
					<a href="{link controller='User' object=$user}{/link}" title="{$user->username}" class="framed">{@$user->getAvatar()->getImageTag(48)}</a>
					
					<div class="userInformation">
						<hgroup class="containerHeadline">
							<h1><a href="{link controller='User' object=$user}{/link}" class="userLink" data-user-id="{@$user->userID}">{$user->username}</a> <span class="badge">Administrator{*TODO: show user title / rank*}</span></h1> 
							<h2>{@$sessionData}</h2>
						</hgroup>
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
					<p class="framed"><img src="" alt="" class="icon48" /></p>
					
					<div class="userInformation">
						<hgroup class="containerHeadline">
							<h1><a href="{link controller='User' object=$user}{/link}" class="userLink" data-user-id="{@$user->userID}">Robot #1234</a></h1> 
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
					<p class="framed"><img src="" alt="" class="icon48" /></p>
					
					<div class="userInformation">
						<hgroup class="containerHeadline">
							<h1>Guest #1234</h1> 
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
