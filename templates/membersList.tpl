{include file='documentHeader'}

<head>
	<title>{if $searchID}{lang}wcf.user.search.results{/lang}{else}{lang}wcf.user.members{/lang}{/if} {if $pageNo > 1}- {lang}wcf.page.pageNo{/lang} {/if}- {PAGE_TITLE|language}</title>
	
	{include file='headInclude'}
	
	<script type="text/javascript">
		//<![CDATA[
			$(function() {
				WCF.Language.addObject({
					'wcf.user.button.follow': '{lang}wcf.user.button.follow{/lang}',
					'wcf.user.button.ignore': '{lang}wcf.user.button.ignore{/lang}',
					'wcf.user.button.unfollow': '{lang}wcf.user.button.unfollow{/lang}',
					'wcf.user.button.unignore': '{lang}wcf.user.button.unignore{/lang}'
				});
				
				new WCF.User.Action.Follow($('.userList > li'));
				new WCF.User.Action.Ignore($('.userList > li'));
				
				new WCF.Search.User('#username', function(data) {
					var $link = '{link controller='User' id=2147483646 title='wcfTitlePlaceholder' encode=false}{/link}';
					window.location = $link.replace('2147483646', data.objectID).replace('wcfTitlePlaceholder', data.label);
				}, false, [ ], false);
			});
		//]]>
	</script>
</head>

<body id="tpl{$templateName|ucfirst}">

{capture assign='sidebar'}
{assign var=encodedLetter value=$letter|rawurlencode}
{*TODO: sidebar content*}
	<div class="javascriptOnly">
		<form method="get" action="{if $searchID}{link controller='MembersList' id=$searchID}{/link}{else}{link controller='MembersList'}{/link}{/if}">
			<fieldset>
				<legend><label for="username">{lang}wcf.user.search{/lang}</label></legend>
				
				<dl>
					<dd>
						<input type="text" id="username" name="username" class="long" placeholder="{lang}wcf.user.username{/lang}" />
					</dd>
				</dl>
			</fieldset>
		</form>
	</div>
	
	<fieldset>
		<legend>{lang}wcf.user.members.sort.letters{/lang}</legend>
				
		<ul class="buttonList letters">
			{foreach from=$letters item=__letter}
				<li><a href="{if $searchID}{link controller='MembersList' id=$searchID}sortField={$sortField}&sortOrder={$sortOrder}&pageNo={@$pageNo}&letter={$__letter|rawurlencode}{/link}{else}{link controller='MembersList'}sortField={$sortField}&sortOrder={$sortOrder}&pageNo={@$pageNo}&letter={$__letter|rawurlencode}{/link}{/if}" class="button small{if $letter == $__letter} active{/if}">{$__letter}</a></li>
			{/foreach}
			{if !$letter|empty}<li><a href="{if $searchID}{link controller='MembersList' id=$searchID}sortField={$sortField}&sortOrder={$sortOrder}&pageNo={@$pageNo}{/link}{else}{link controller='MembersList'}sortField={$sortField}&sortOrder={$sortOrder}&pageNo={@$pageNo}{/link}{/if}" class="button small">{lang}wcf.user.members.sort.letters.all{/lang}</a></li>{/if}
		</ul>
	</fieldset>
		
	<div>
		<form method="get" action="{if $searchID}{link controller='MembersList' id=$searchID}{/link}{else}{link controller='MembersList'}{/link}{/if}">
			<fieldset>
				<legend>{lang}wcf.user.members.sort{/lang}</legend>
				
				<dl>
					<dd>
						<select id="sortField" name="sortField">
							<option value="username"{if $sortField == 'username'} selected="selected"{/if}>{lang}wcf.user.username{/lang}</option>
							<option value="registrationDate"{if $sortField == 'registrationDate'} selected="selected"{/if}>{lang}wcf.user.registrationDate{/lang}</option>
							<option value="activityPoints"{if $sortField == 'activityPoints'} selected="selected"{/if}>{lang}wcf.user.activityPoint{/lang}</option>
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
		<h1>{if $searchID}{lang}wcf.user.search.results{/lang}{else}{lang}wcf.user.members{/lang}{/if} <span class="badge">{#$items}</span></h1>
	</hgroup>
</header>

{include file='userNotice'}

<div class="contentNavigation">
	{if $searchID}
		{pages print=true assign=pagesLinks controller='MembersList' id=$searchID link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder&letter=$encodedLetter"}
	{else}
		{pages print=true assign=pagesLinks controller='MembersList' link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder&letter=$encodedLetter"}
	{/if}
</div>

<div class="container marginTop">
	<ol class="containerList doubleColumned userList">
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
