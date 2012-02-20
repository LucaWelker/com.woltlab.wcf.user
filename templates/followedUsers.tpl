{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.followedUsers.title{/lang}</title>
	{include file='headInclude' sandbox=false}
	
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			new WCF.Action.Delete('wcf\\data\\user\\follow\\UserFollowAction', $('.jsFollowedUser'), $('.jsFollowedUsersBadge'));
		});
		//]]>
	</script>
	<style type="text/css">
		#followedUsersList li {
			display: inline-block;
			position: relative;
		}
		
		#followedUsersList span {
			opacity: 0;
			position: absolute;
			right: 2px;
			top: 2px;
		
			-o-transition: opacity .2s ease 0;
		}
		
		#followedUsersList li:hover span {
			opacity: 1;
		}
		
		#followedUsersList span > img {
			border-bottom-left-radius: 5px;
		}
	</style>
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='profileEditSidebar' sandbox=false}

{include file='header' sandbox=false sidebarOrientation='left'}

<header class="wcf-container wcf-mainHeading">
	<img src="{icon size='L'}followedUsers1{/icon}" alt="" class="wcf-containerIcon" />
	<hgroup class="wcf-containerContent">
		<h1>{lang}wcf.user.followedUsers.title{/lang} <span class="wcf-badge jsFollowedUsersBadge">{#$count}</span></h1>
	</hgroup>
</header>

<div class="wcf-contentHeader"> </div>

<section id="followedUsersList">
	{hascontent}
		<ul>
			{content}
				{foreach from=$followedUsers item=followedUser}
					<li class="userAvatar jsFollowedUser">
						<div title="{$followedUser->username}" class="jsTooltip">
							<span><img src="{icon size='S'}delete1{/icon}" alt="" class="jsDeleteButton" data-object-id="{@$followedUser->followUserID}" /></span>
							<a href="{link controller='User' id=$followedUser->followUserID}{/link}">
								{@$followedUser->getAvatar()->getImageTag(64)}
							</a>
						</div>
					</li>
				{/foreach}
			{/content}
		</ul>
	{hascontentelse}
		<!-- TODO: What should we display here? -->
		<p>Y U NO HAZ FRIENDS?</p>
	{/hascontent}
</section>

<div class="wcf-contentFooter"> </div>

{include file='footer' sandbox=false}

</body>
</html>
