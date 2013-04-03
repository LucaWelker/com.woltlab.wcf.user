{include file='header' pageTitle='wcf.acp.dashboard.list'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.acp.dashboard.list{/lang}</h1>
	</hgroup>
</header>

<div class="contentNavigation">
	{hascontent}
		<nav>
			<ul>
				{content}
					{event name='contentNavigationButtonsTop'}
				{/content}
			</ul>
		</nav>
	{/hascontent}
</div>

<div class="tabularBox tabularBoxTitle marginTop">
	<hgroup>
		<h1>{lang}wcf.acp.dashboard.list{/lang} <span class="badge badgeInverse">{#$objectTypes|count}</span></h1>
	</hgroup>
	
	<table class="table">
		<thead>
			<tr>
				<th colspan="2" class="columnID">{lang}wcf.global.objectID{/lang}</th>
				<th class="columnText">{lang}wcf.dashboard.objectType{/lang}</th>
				
				{event name='columnHeads'}
			</tr>
		</thead>
		
		<tbody>
			{foreach from=$objectTypes item=$objectType}
				<tr>
					<td class="columnIcon">
						<a href="{link controller='DashboardOption' id=$objectType->objectTypeID}{/link}" title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip"><span class="icon icon16 icon-pencil"></span></a>
						
						{event name='rowButtons'}
					</td>
					<td class="columnID"><p>{#$objectType->objectTypeID}</p></td>
					<td class="columnText">
						<p><a href="{link controller='DashboardOption' id=$objectType->objectTypeID}{/link}">{lang}wcf.dashboard.objectType.{$objectType->objectType}{/lang}</a></p>
					</td>
					
					{event name='columns'}
				</tr>
			{/foreach}
		</tbody>
	</table>
</div>

<div class="contentNavigation">
	{hascontent}
		<nav>
			<ul>
				{content}
					{event name='contentNavigationButtonsBottom'}
				{/content}
			</ul>
		</nav>
	{/hascontent}
</div>

{include file='footer'}