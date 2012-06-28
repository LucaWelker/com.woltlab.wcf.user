<ul class="sitemapList">
	{if $__wcf->getUser()->userID}
		<!-- TODO: Dummy entries, make use of the original structure -->
		{assign var=__userMenuActiveItems value=$__wcf->getUserMenu()->getActiveMenuItems()}
		{foreach from=$__wcf->getUserMenu()->getMenuItems('') item=menuCategory}
			<li class="sitemapCategory">
				<hgroup>
					<h1>{lang}{$menuCategory->menuItem}{/lang}</h1>
				</hgroup>
				<ul>
					{foreach from=$__wcf->getUserMenu()->getMenuItems($menuCategory->menuItem) item=menuItem}
						<li class="sitemapEntry"><a href="{$menuItem->getLink()}">{lang}{$menuItem->menuItem}{/lang}</a></li>
					{/foreach}
				</ul>
			</li>
		{/foreach}
	{else}
		<li class="sitemapEntry">
			<hgroup>
				<h1><a href="{link controller='Login'}{/link}">{lang}wcf.user.login{/lang}</a></h1>
			</hgroup>
		</li>
		<li class="sitemapEntry">
			<hgroup>
				<h1><a href="{link controller='Register'}{/link}">{lang}wcf.user.register{/lang}</a></h1>
			</hgroup>
		</li>
	{/if}
</ul>