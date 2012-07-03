{include file='header'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.acp.dashboard.option{/lang}</h1>
	</hgroup>
</header>

<div class="contentNavigation"></div>

<form method="post" action="{link controller='DashboardOption'}{/link}" class="marginTop">
	{foreach from=$objectTypes item=objectType}
		<fieldset>
			<legend>{lang}wcf.dashboard.objectType.{$objectType->objectType}{/lang}</legend>
			
			<div class="container containerPadding sortableListContainer">
				<ol class="sortableList">
					{if $options[$objectType->objectTypeID]|isset}
						{foreach from=$options[$objectType->objectTypeID] item=box}
							<li class="sortableList">
								<label class="sortableNodeLabel">{lang}wcf.dashboard.box.{$box->boxName}{/lang}</label>
							</li>
						{/foreach}
					{/if}
				</ol>
			</div>
		</fieldset>
	{/foreach}
</form>

<div class="contentNavigation"></div>

{include file='footer'}