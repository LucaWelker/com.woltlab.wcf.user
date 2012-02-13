{capture assign='sidebar'}

<nav id="sidebarContent" class="wcf-sidebarContent">
	{foreach from=$__wcf->getUserMenu()->getMenuItems('') item=menuCategory}
		<div class="wcf-menuContainer" id="{'.'|str_replace:'_':$menuCategory->menuItem}Content">
			<h1 class="wcf-menuHeader">{lang}{$menuCategory->menuItem}{/lang}</h1>
			<div class="wcf-sidebarContentGroup">
				<ul>
					{foreach from=$__wcf->getUserMenu()->getMenuItems($menuCategory->menuItem) item=menuItem}
						<li><a href="{$menuItem->getLink()}">{lang}{$menuItem->menuItem}{/lang}</a></li>
					{/foreach}
				</ul>
			</div>
		</div>
	{/foreach}
	
	{* collapse sidebar *}			
	<span class="wcf-collapsibleSidebarButton" title="{lang}wcf.global.button.collapsible{/lang}"><span></span></span>
</nav>

{/capture}