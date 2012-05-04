{capture assign='sidebar'}

{*TODO: css classes*}
<nav id="sidebarContent" class="sidebarContent">
	<ul>
		{assign var=__userMenuActiveItems value=$__wcf->getUserMenu()->getActiveMenuItems()}
		{foreach from=$__wcf->getUserMenu()->getMenuItems('') item=menuCategory}
			<li class="menuGroup" id="{'.'|str_replace:'_':$menuCategory->menuItem}Content">
				<h1>{lang}{$menuCategory->menuItem}{/lang}</h1>
				<div class="menuGroupItems">
					<ul>
						{foreach from=$__wcf->getUserMenu()->getMenuItems($menuCategory->menuItem) item=menuItem}
							<li{if $menuItem->menuItem|in_array:$__userMenuActiveItems} class="active"{/if}><a href="{$menuItem->getLink()}">{lang}{$menuItem->menuItem}{/lang}</a></li>
						{/foreach}
					</ul>
				</div>
			</li>
		{/foreach}
	</ul>
</nav>

{/capture}