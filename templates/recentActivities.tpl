{hascontent}
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			new WCF.User.RecentActivityLoader({@$userID});
		});
		//]]>
	</script>
	<ul id="recentActivities" class="containerList">
		{content}
			{foreach from=$eventList item=event}
				<li>
					<div class="box48">
						<a href="{link controller='User' object=$event->getUserProfile()}{/link}" title="{$event->getUserProfile()->username}" class="framed">{@$event->getUserProfile()->getAvatar()->getImageTag(48)}</a>
						
						<div>
							<hgroup class="containerHeadline">
								<h1><a href="{link controller='User' object=$event->getUserProfile()}{/link}">{$event->getUserProfile()->username}</a><small> - {@$event->time|time}</small></h1> 
								<h2><strong>{@$event->getTitle()}</strong></h2>
							</hgroup>
							
							<p>{@$event->getDescription()}</p>
						</div>
					</div>
				</li>
			{/foreach}
		{/content}
	</ul>
{hascontentelse}
	<div class="containerPadding"></div>
{/hascontent}
