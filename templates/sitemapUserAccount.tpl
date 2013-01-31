<ul class="sitemapList">
	{if $__wcf->getUser()->userID}
		{assign var=__userMenuActiveItems value=$__wcf->getUserMenu()->getActiveMenuItems()}
		{foreach from=$__wcf->getUserMenu()->getMenuItems('') item=menuCategory}
			<li>
				<hgroup>
					<h1>{lang}{$menuCategory->menuItem}{/lang}</h1>
				</hgroup>
				<ul>
					{foreach from=$__wcf->getUserMenu()->getMenuItems($menuCategory->menuItem) item=menuItem}
						<li><a href="{$menuItem->getLink()}">{lang}{$menuItem->menuItem}{/lang}</a></li>
					{/foreach}
				</ul>
			</li>
		{/foreach}
	{else}
		<li>
			<hgroup>
				<h1><a href="{link controller='Login'}{/link}">{lang}wcf.user.login{/lang}</a></h1>
			</hgroup>
		</li>
		<li>
			<hgroup>
				<h1><a href="{link controller='Register'}{/link}">{lang}wcf.user.register{/lang}</a></h1>
			</hgroup>
		</li>
	{/if}
</ul>