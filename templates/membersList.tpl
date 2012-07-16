{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.members{/lang} {if $pageNo > 1}- {lang}wcf.page.pageNo{/lang} {/if}- {PAGE_TITLE|language}</title>
	{include file='headInclude'}
</head>

<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>

{capture assign='sidebar'}
{assign var=encodedLetter value=$letter|rawurlencode}
{*TODO: sidebar content*}
<nav id="sidebarContent" class="sidebarContent">
	<ul>
		<li class="sidebarContainer">
			<hgroup class="sidebarContainerHeadline">
				<h1>{lang}wcf.user.members.sort.letters{/lang}</h1>
			</hgroup>
			
			<ul class="buttonList letters">
				{foreach from=$letters item=__letter}
					<li><a href="{link controller='MembersList'}sortField={$sortField}&sortOrder={$sortOrder}&pageNo={@$pageNo}&letter={$__letter|rawurlencode}{/link}" class="button small{if $letter == $__letter} active{/if}">{$__letter}</a></li>
				{/foreach}
				{if !$letter|empty}<li><a href="{link controller='MembersList'}sortField={$sortField}&sortOrder={$sortOrder}&pageNo={@$pageNo}{/link}" class="button small">{lang}wcf.user.members.sort.letters.all{/lang}</a></li>{/if}
			</ul>
		</li>
		
		<li class="sidebarContainer">
			<form method="get" action="{link controller='MembersList'}{/link}">
				<fieldset>
					<legend>{lang}wcf.user.members.sort{/lang}</legend>
					
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
						<input type="hidden" name="pageNo" value="{@$pageNo}" />
						<input type="hidden" name="letter" value="{$letter}" />
					</div>
				</fieldset>
			</form>
		</li>
	</ul>
</nav>
{/capture}

{include file='header' sidebarOrientation='right'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.user.members{/lang} <span class="badge">{#$items}</span></h1>
	</hgroup>
</header>

{include file='userNotice'}

<div class="contentNavigation">
	{pages print=true assign=pagesLinks controller='MembersList' link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder&letter=$encodedLetter"}
</div>

<div class="container marginTop shadow">
	<ol class="containerList userList simpleUserList">
		{foreach from=$objects item=user}
			<li>
				<div class="box48">
					<a href="{link controller='User' object=$user}{/link}" title="{$user->username}" class="framed">{@$user->getAvatar()->getImageTag(48)}</a>
						
					<div class="userInformation">
						{include file='userInformation'}
						
						{*TODO: show additional user information*}
						{if $user->hobbies}<p>{lang}wcf.user.option.hobbies{/lang}: {$user->hobbies}</p>{/if}
					</div>
				</div>
			</li>
		{/foreach}
	</ol>
</div>

<div class="contentNavigation">
	{@$pagesLinks}
</div>

{include file='footer'}

</body>
</html>
