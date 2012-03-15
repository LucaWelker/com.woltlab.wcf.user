{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.usersOnline{/lang} - {PAGE_TITLE|language}</title>
	{include file='headInclude' sandbox=false}
</head>

<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>

{capture assign='sidebar'}
<nav id="sidebarContent" class="wcf-sidebarContent">
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
		<h1>{lang}wcf.user.usersOnline{/lang} <span class="wcf-badge">{#$items}</span></h1>
	</hgroup>
</header>

<div class="wcf-contentHeader">
</div>

<div>
	<ol class="wcf-userList">
		{foreach from=$objects item=user}
			<li class="wcf-listBox">
				<div class="wcf-container">
					{if $user->userID}
						<a href="{link controller='User' object=$user}{/link}" title="{$user->username}" class="wcf-containerIcon wcf-userAvatarFramed">{@$user->getAvatar()->getImageTag(48)}</a>
						
						<div class="wcf-containerContent">
							<h1 class="wcf-username"><a href="{link controller='User' object=$user}{/link}" title="{$user->username}">{$user->username}</a></h1> <span class="wcf-badge wcf-label">Administrator</span>
							
							<p class="wcf-userJoinDate">{lang}wcf.user.membersList.registrationDate{/lang}{if $user->gender}, {lang}wcf.user.gender.{if $user->gender == 1}male{else}female{/if}{/lang}{/if}{if $user->getAge()}, {@$user->getAge()}{/if}{if $user->location}, {lang}wcf.user.membersList.location{/lang}{/if}</p>
							<p><a href="">Posts: 12.324</a>, <a href="">Likes received: 27.300</a></p>
							{if $user->hobbies}<p>{lang}wcf.user.option.hobbies{/lang}: {$user->hobbies}</p>{/if}
						</div>
					{else}
					guest
					{/if}
				</div>
			</li>
		{/foreach}
	</ol>
</div>

<div class="wcf-contentFooter">
</div>

{include file='footer' sandbox=false}

</body>
</html>
