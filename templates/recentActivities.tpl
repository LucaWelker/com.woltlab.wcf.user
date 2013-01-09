{hascontent}
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			new WCF.User.RecentActivityLoader({@$userID});
		});
		//]]>
	</script>
	
	<ul id="recentActivities" class="containerList" data-last-event-time="{@$lastEventTime}">
		{content}
			{include file='recentActivityListItem'}
		{/content}
	</ul>
{hascontentelse}
	<div class="containerPadding">
		{if $placeholder|isset}{$placeholder}{/if}
	</div>
{/hascontent}
