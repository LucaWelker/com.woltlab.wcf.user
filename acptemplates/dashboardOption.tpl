{include file='header'}

<script type="text/javascript">
	//<![CDATA[
	$(function() {
		WCF.TabMenu.init();
	});
	//]]>
</script>

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.acp.dashboard.option{/lang}</h1>
		<h2>{lang}wcf.dashboard.objectType.{$objectType->objectType}{/lang}</h2>
	</hgroup>
</header>

<div class="contentNavigation">
	<nav>
		<ul>
			<li><a href="{link controller='DashboardList'}{/link}" title="{lang}wcf.acp.menu.link.dashboard.list{/lang}" class="button"><img src="{@$__wcf->getPath()}icon/list.svg" alt="" class="icon24" /> <span>{lang}wcf.acp.menu.link.dashboard.list{/lang}</span></a></li>
			
			{event name='largeButtons'}
		</ul>
	</nav>
</div>

<form method="post" action="{link controller='DashboardOption'}{/link}">
	<div class="tabMenuContainer">
		<nav class="tabMenu">
			<ul>
				{if $objectType->allowcontent}
					<li><a href="#dashboard-content">{lang}wcf.dashboard.boxType.content{/lang}</a></li>
				{/if}
				{if $objectType->allowsidebar}
					<li><a href="#dashboard-sidebar">{lang}wcf.dashboard.boxType.sidebar{/lang}</a></li>
				{/if}
				
				{event name='tabMenuTabs'}
			</ul>
		</nav>
		
		{if $objectType->allowcontent}
			<div id="dashboard-content" class="container containerPadding tabMenuContent hidden">
				<fieldset>
					<legend>{lang}wcf.dashboard.boxes.enabled{/lang}</legend>
					
					<div class="container containerPadding sortableListContainer">
						<ol class="sortableList">
							{foreach from=$enabledBoxes item=boxID}
								{if $boxes[$boxID]->boxType == 'content'}
									<li class="sortableList">
										<span class="sortableNodeLabel">{lang}wcf.dashboard.box.{$boxes[$boxID]->boxName}{/lang}</span>
									</li>
								{/if}
							{/foreach}
						</ol>
					</div>
				</fieldset>
				
				<fieldset>
					<legend>{lang}wcf.dashboard.boxes.available{/lang}</legend>
					
					<div class="container containerPadding sortableListContainer">
						<ol class="sortableList">
							{foreach from=$boxes item=box}
								{if $box->boxType == 'content' && !$box->boxID|in_array:$enabledBoxes}
									<li class="sortableList">
										<span class="sortableNodeLabel">{lang}wcf.dashboard.box.{$boxes[$boxID]->boxName}{/lang}</span>
									</li>
								{/if}
							{/foreach}
						</ol>
					</div>
				</fieldset>
			</div>
		{/if}
		
		{if $objectType->allowsidebar}
			<div id="dashboard-sidebar" class="container containerPadding tabMenuContent hidden">
				<fieldset>
					<legend>{lang}wcf.dashboard.boxes.enabled{/lang}</legend>
					
					<div class="container containerPadding sortableListContainer">
						<ol class="sortableList">
							{foreach from=$enabledBoxes item=boxID}
								{if $boxes[$boxID]->boxType == 'sidebar'}
									<li class="sortableList">
										<span class="sortableNodeLabel">{lang}wcf.dashboard.box.{$box->boxName}{/lang}</span>
									</li>
								{/if}
							{/foreach}
						</ol>
					</div>
				</fieldset>
				
				<fieldset>
					<legend>{lang}wcf.dashboard.boxes.available{/lang}</legend>
					
					<div class="container containerPadding sortableListContainer">
						<ol class="sortableList">
							{foreach from=$boxes item=box}
								{if $box->boxType == 'sidebar' && !$box->boxID|in_array:$enabledBoxes}
									<li class="sortableList">
										<span class="sortableNodeLabel">{lang}wcf.dashboard.box.{$box->boxName}{/lang}</span>
									</li>
								{/if}
							{/foreach}
						</ol>
					</div>
				</fieldset>
			</div>
		{/if}
	</div>
	{*foreach from=$objectTypes item=objectType}
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
	{/foreach*}
</form>

<div class="contentNavigation">
	<nav>
		<ul>
			<li><a href="{link controller='DashboardList'}{/link}" title="{lang}wcf.acp.menu.link.dashboard.list{/lang}" class="button"><img src="{@$__wcf->getPath()}icon/list.svg" alt="" class="icon24" /> <span>{lang}wcf.acp.menu.link.dashboard.list{/lang}</span></a></li>
			
			{event name='largeButtons'}
		</ul>
	</nav>
</div>

{include file='footer'}