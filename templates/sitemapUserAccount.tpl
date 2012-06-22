<ul class="sitemapList">
	{if $__wcf->getUser()->userID}
		<!-- TODO: Dummy entries, make use of the original structure -->
		<li class="sitemapCategory">
			<hgroup>
				<h1>{lang}wcf.sitemap.userAccount.profile{/lang}</h1>
			</hgroup>
			
			<ul>
				<li class="sitemapEntry">
					<a href="{link controller='AccountManagement'}{/link}">{lang}wcf.sitemap.userAccount.profile.accountManagement{/lang}</a>
				</li>
			</ul>
		</li>
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