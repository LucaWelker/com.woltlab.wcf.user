{include file='header' pageTitle='wcf.acp.dashboard.list'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.acp.dashboard.list{/lang}</h1>
	</hgroup>
</header>

<div class="contentNavigation"></div>

<div class="tabularBox tabularBoxTitle marginTop">
	<hgroup>
		<h1>{lang}wcf.acp.dashboard.list{/lang} <span class="badge badgeInverse" title="{lang}wcf.acp.package.list.count{/lang}">{#$objectTypes|count}</span></h1>
	</hgroup>
	
	<table class="table">
		<thead>
			<tr>
				<th colspan="2" class="columnID">{lang}wcf.global.objectID{/lang}</th>
				<th class="columnText">{lang}wcf.dashboard.objectType{/lang}</th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$objectTypes item=$objectType}
				<tr>
					<td class="columnIcon">
						<a href="{link controller='DashboardOption' id=$objectType->objectTypeID}{/link}"><img src="{@RELATIVE_WCF_DIR}icon/edit.svg" alt="" title="{lang}wcf.global.button.edit{/lang}" class="icon16 jsTooltip" /></a>
					</td>
					<td class="columnID"><p>{#$objectType->objectTypeID}</p></td>
					<td class="columnText">
						<p><a href="{link controller='DashboardOption' id=$objectType->objectTypeID}{/link}">{lang}wcf.dashboard.objectType.{$objectType->objectType}{/lang}</a></p>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
</div>

<div class="contentNavigation"></div>

{include file='footer'}