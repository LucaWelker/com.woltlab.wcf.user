{capture assign='sidebar'}
	{assign var=__userMenuActiveItems value=$__wcf->getUserMenu()->getActiveMenuItems()}
	
	{foreach from=$__wcf->getUserMenu()->getMenuItems('') item=menuCategory}
		<fieldset>
			<legend>{lang}{$menuCategory->menuItem}{/lang}</legend>
			
			<nav>
				<ul>
					{foreach from=$__wcf->getUserMenu()->getMenuItems($menuCategory->menuItem) item=menuItem}
						<li{if $menuItem->menuItem|in_array:$__userMenuActiveItems} class="active"{/if}><a href="{$menuItem->getProcessor()->getLink()}">{@$menuItem}</a></li>
					{/foreach}
				</ul>
			</nav>
		</fieldset>
	{/foreach}
	
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			// mobile safari hover workaround
			if ($.browser.mobile) {
				$('.sidebar').addClass('mobileSidebar').hover(function() { });
			}
		});
		//]]>
	</script> 
{/capture}
