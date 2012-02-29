{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.ignoredUsers.title{/lang}</title>
	{include file='headInclude' sandbox=false}
	
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			new WCF.Action.Delete('wcf\\data\\user\\ignore\\UserIgnoreAction', $('.jsIgnoredUser'), $('.jsIgnoredUsersBadge'));
		});
		//]]>
	</script>
	<style type="text/css">
		#ignoredUsersList li {
			display: inline-block;
			position: relative;
		}
		
		#ignoredUsersList span {
			opacity: 0;
			position: absolute;
			right: 2px;
			top: 2px;
		
			-o-transition: opacity .2s ease 0;
		}
		
		#ignoredUsersList li:hover span {
			opacity: 1;
		}
		
		#ignoredUsersList span > img {
			border-bottom-left-radius: 5px;
		}
	</style>
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='profileEditSidebar' sandbox=false}

{include file='header' sandbox=false sidebarOrientation='left'}

<header class="wcf-container wcf-mainHeading">
	<img src="{icon size='L'}users1{/icon}" alt="" class="wcf-containerIcon" />
	<hgroup class="wcf-containerContent">
		<h1>{lang}wcf.user.ignoredUsers.title{/lang} <span class="wcf-badge jsIgnoredUsersBadge">{#$count}</span></h1>
	</hgroup>
</header>

<div class="wcf-contentHeader"> </div>

<section id="ignoredUsersList">
	{hascontent}
		<ul>
			{content}
				{foreach from=$ignoredUsers item=ignoredUser}
					<li class="wcf-userAvatarFramed jsIgnoredUser">
						<div title="{$ignoredUser->username}" class="jsTooltip">
							<span><img src="{icon size='S'}delete1{/icon}" alt="" class="jsDeleteButton" data-object-id="{@$ignoredUser->ignoreUserID}" /></span>
							<a href="{link controller='User' id=$ignoredUser->ignoreUserID}{/link}">
								{@$ignoredUser->getAvatar()->getImageTag(64)}
							</a>
						</div>
					</li>
				{/foreach}
			{/content}
		</ul>
	{hascontentelse}
		<!-- TODO: What should we display here? -->
		<p>Y U NO HAZ IGNORED RETARDS?</p>
	{/hascontent}
</section>

<div class="wcf-contentFooter"> </div>

{include file='footer' sandbox=false}

</body>
</html>
