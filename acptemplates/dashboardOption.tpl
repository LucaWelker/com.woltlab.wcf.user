{include file='header'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.acp.dashboard.option{/lang}</h1>
	</hgroup>
</header>

<div class="contentNavigation"></div>

<form method="post" action="{link controller='DashboardOption'}{/link}" class="marginTop">
	{foreach from=$objectTypes item=objectType}
		{hascontent}
			<fieldset>
				<legend>{lang}wcf.dashboard.objectType.{$objectType->objectType}{/lang}</legend>
				
				{content}
					{if $objectType->allowcontent}
						<div class="container containerPadding sortableListContainer">
							<p>{lang}wcf.dashboard.boxType.content{/lang}</p>
							<ol class="sortableList">
								{foreach from=$boxes item=box}
									<li class="sortableNode">
										<label class="sortableNodeLabel"><input type="checkbox" name="options[{@$objectType->objectTypeID}][{@$box->boxID}]" value="1"{if $options[$objectType->objectTypeID][$box->boxID]} checked="checked"{/if} /> {lang}wcf.dashboard.box.{$box->boxName}{/lang}</label>
									</li>
								{/foreach}
							</ol>
						</div>
					{/if}
					
					{if $objectType->allowsidebar}
						<div class="container containerPadding sortableListContainer marginTop">
							<p>{lang}wcf.dashboard.boxType.sidebar{/lang}</p>
							<ol class="sortableList">
								{foreach from=$boxes item=box}
									<li class="sortableNode">
										<label class="sortableNodeLabel"><input type="checkbox" name="options[{@$objectType->objectTypeID}][{@$box->boxID}]" value="1"{if $options[$objectType->objectTypeID][$box->boxID]} checked="checked"{/if} /> {lang}wcf.dashboard.box.{$box->boxName}{/lang}</label>
									</li>
								{/foreach}
							</ol>
						</div>
					{/if}
				{/content}
			</fieldset>
		{/hascontent}
	{/foreach}
</form>

<div class="contentNavigation"></div>

{include file='footer'}