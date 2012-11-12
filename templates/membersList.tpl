{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.members{/lang} {if $pageNo > 1}- {lang}wcf.page.pageNo{/lang} {/if}- {PAGE_TITLE|language}</title>
	
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
				
				new WCF.User.Action.Follow($('.simpleUserList > li'));
				new WCF.User.Action.Ignore($('.simpleUserList > li'));
			});
		//]]>
	</script>
</head>

<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>

{capture assign='sidebar'}
{assign var=encodedLetter value=$letter|rawurlencode}
{*TODO: sidebar content*}
	<fieldset>
		<legend>{lang}wcf.user.members.sort.letters{/lang}</legend>
				
		<ul class="buttonList letters">
			{foreach from=$letters item=__letter}
				<li><a href="{link controller='MembersList'}sortField={$sortField}&sortOrder={$sortOrder}&pageNo={@$pageNo}&letter={$__letter|rawurlencode}{/link}" class="button small{if $letter == $__letter} active{/if}">{$__letter}</a></li>
			{/foreach}
			{if !$letter|empty}<li><a href="{link controller='MembersList'}sortField={$sortField}&sortOrder={$sortOrder}&pageNo={@$pageNo}{/link}" class="button small">{lang}wcf.user.members.sort.letters.all{/lang}</a></li>{/if}
		</ul>
	</fieldset>
		
	<div>
		<form method="get" action="{link controller='MembersList'}{/link}">
			<fieldset>
				<legend>{lang}wcf.user.members.sort{/lang}</legend>
				
				<dl>
					<dd>
						<select id="sortField" name="sortField">
							<option value="username"{if $sortField == 'username'} selected="selected"{/if}>{lang}wcf.user.username{/lang}</option>
							<option value="registrationDate"{if $sortField == 'registrationDate'} selected="selected"{/if}>{lang}wcf.user.registrationDate{/lang}</option>
							{event name='sortField'}
						</select>
						<select name="sortOrder">
							<option value="ASC"{if $sortOrder == 'ASC'} selected="selected"{/if}>{lang}wcf.global.sortOrder.ascending{/lang}</option>
							<option value="DESC"{if $sortOrder == 'DESC'} selected="selected"{/if}>{lang}wcf.global.sortOrder.descending{/lang}</option>
						</select>
					</dd>
				</dl>
			</fieldset>
			
			<div class="formSubmit">
				<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
				<input type="hidden" name="pageNo" value="{@$pageNo}" />
				<input type="hidden" name="letter" value="{$letter}" />
			</div>
		</form>
	</div>
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

<div class="container marginTop">
	<ol class="containerList doubleColumned">
		{foreach from=$objects item=user}
			{include file='userListItem'}
		{/foreach}
	</ol>
</div>

<div class="contentNavigation">
	{@$pagesLinks}
</div>

{include file='footer'}

</body>
</html>
