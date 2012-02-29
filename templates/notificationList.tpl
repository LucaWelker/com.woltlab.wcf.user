{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.notificationList.title{/lang}</title>
	{include file='headInclude' sandbox=false}
	
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			new WCF.Notification.List();
		});
		//]]>
	</script>
	<style type="text/css">
		/* ToDo */
		
		#notificationList > ul > li {
			background-image: -o-linear-gradient(rgba(192, 192, 192, .3), rgba(224, 224, 224, .3));
			padding: 7px;
		}
		
		#notificationList > ul > li:first-child {
			border-top-left-radius: 5px;
			border-top-right-radius: 5px;
		}
		
		#notificationList >ul > li:last-child {
			border-bottom-left-radius: 5px;
			border-bottom-right-radius: 5px;
		}
		
		#notificationList li a {
			width: 48px;
		}
		
		#notificationList li p {
			display: inline-block;
			padding-left: 7px;
			vertical-align: middle;
		}
		
		#notificationList li p > small {
			color: rgb(153, 153, 153);
			display: block;
			font-size: 80%;
			padding-top: 3px;
		}
		
		#notificationList li > ul {
			display: inline-block;
			padding-left: 14px;
		}
		
		#notificationList li > ul > li {
			background-image: -o-linear-gradient(rgba(224, 224, 224, .3), rgba(192, 192, 192, .3));
			border: 1px solid rgb(192, 192, 192);
			border-radius: 3px;
			cursor: pointer;
			padding: 7px;
		}
		
		#notificationList li > ul > li:hover {
			background-image: -o-linear-gradient(rgba(216, 231, 245, .3), rgba(51, 102, 153, .3));
		}
		
		#notificationList ul.jsNotificationAction {
			opacity: 0;
			
			-o-transition: opacity .2s ease 0;
		}
		
		#notificationList > ul > li:hover ul.jsNotificationAction {
			opacity: 1.0;
		}
	</style>
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='profileEditSidebar' sandbox=false}

{include file='header' sandbox=false sidebarOrientation='left'}

<header class="wcf-container wcf-mainHeading">
	<img src="{icon size='L'}users1{/icon}" alt="" class="wcf-containerIcon" />
	<hgroup class="wcf-containerContent">
		<h1>{lang}wcf.user.notificationList.title{/lang} <span class="wcf-badge jsNotificationsBadge">{#$notifications[count]}</span></h1>
	</hgroup>
</header>

<div class="wcf-contentHeader"> </div>

<section id="notificationList">
	{hascontent}
		<ul>
			{content}
				{foreach from=$notifications[notifications] item=$notification}
					<li class="wcf-userAvatarFramed jsNotificationItem" data-notification-id="{@$notification[notificationID]}">
						<a href="{link controller='User' object=$notification[author]}{/link}" title="{$notification[author]->username}" class="jsTooltip">{@$notification[author]->getAvatar()->getImageTag(48)}</a>
						<p>
							{@$notification[message]}
							<small>{@$notification[time]|time}</small>
							
							<ul class="jsNotificationAction" data-notification-id="{@$notification[notificationID]}">
								{foreach from=$notification[buttons] item=button}
									<li data-action-name="{$button[actionName]}" data-class-name="{$button[className]}" data-object-id="{@$button[objectID]}">{$button[label]}</li>
								{/foreach}
							</ul>
						</p>
					</li>
				{/foreach}
			{/content}
		</ul>
	{hascontentelse}
		<!-- TODO: What should we display here? -->
		<p>Y U NO HAZ NOTIFICATIONS?</p>
	{/hascontent}
</section>

<div class="wcf-contentFooter"> </div>

{include file='footer' sandbox=false}

</body>
</html>
