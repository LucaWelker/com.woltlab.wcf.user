{include file='documentHeader'}

<head>
	<title>User list page</title>
	{include file='headInclude' sandbox=false}
</head>

<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>

{capture assign='sidebar'}
<nav id="sidebarContent" class="wcf-sidebarContent">
	<div>
		<fieldset>
			<legend>sort</legend>
			
			<form method="get" action="{link controller='UserList'}{/link}">
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
				
				<div class="wcf-formSubmit">
					<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
					{@SID_INPUT_TAG}
				</div>
			</form>
		</fieldset>
	</div>
</nav>
{/capture}

{include file='header' sandbox=false sidebarOrientation='right'}

<header class="wcf-container wcf-mainHeading">
	<img src="{icon size='L'}users1{/icon}" alt="" class="wcf-containerIcon" />
	<hgroup class="wcf-containerContent">
		<h1>User list page</h1>
		<h2>Total: {#$items}</h2>
	</hgroup>
</header>

<div class="wcf-contentHeader">
	{pages print=true assign=pagesLinks controller='UserList' link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}
</div>

<ol>
	{foreach from=$objects item=user}
		<li class="wcf-container wcf-border" style="float: left; padding: 7px; width: 48%; margin-right: 5px">
			<a href="{link controller='User' object=$user}{/link}" title="{$user->username}" class="wcf-containerIcon wcf-userAvatarFramed">{@$user->getAvatar()->getImageTag(48)}</a>
			
			<div class="wcf-containerContent" style="line-height: 1.5">
				<p class="wcf-username"><a href="{link controller='User' object=$user}{/link}">{$user->username}</a></p>
				
				<p style="font-size: .85em;">Placeholder 1</p>
				<p style="font-size: .85em;">Placeholder 1</p>
				<p></p>
			<div>
		</li>
	{/foreach}
</ol>
<br style="clear: both" />

<div class="wcf-contentFooter">
	{@$pagesLinks}
</div>

{include file='footer' sandbox=false}

</body>
</html>
