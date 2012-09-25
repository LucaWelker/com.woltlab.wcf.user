{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.following.title{/lang}</title>
	{include file='headInclude'}
	
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			new WCF.Action.Delete('wcf\\data\\user\\follow\\UserFollowAction', $('.jsFollowing'), $('.jsFollowingBadge'));
		});
		//]]>
	</script>
	{*TODO: css*}
	<style type="text/css">
		#followingList li {
			display: inline-block;
			position: relative;
		}
		
		#followingList span {
			opacity: 0;
			position: absolute;
			right: 2px;
			top: 2px;
		
			-o-transition: opacity .2s ease 0;
		}
		
		#followingList li:hover span {
			opacity: 1;
		}
		
		#followingList span > img {
			border-bottom-left-radius: 5px;
		}
	</style>
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='userMenuSidebar'}

{include file='header' sidebarOrientation='left'}

<header class="boxHeadline"> 
	<hgroup >
		<h1>{lang}wcf.user.following.title{/lang} <span class="badge jsFollowingBadge">{#$count}</span></h1>
	</hgroup>
</header>

{include file='userNotice'}

<section id="followingList">
	{hascontent}
		<ul>
			{content}
				{foreach from=$following item=followingUser}
					<li class="framed jsFollowing">
						<div title="{$followingUser->username}" class="jsTooltip">
							<span><img src="{icon}delete{/icon}" alt="" class="jsDeleteButton" data-object-id="{@$followingUser->userID}" /></span>
							<a href="{link controller='User' object=$followingUser}{/link}">
								{@$followingUser->getAvatar()->getImageTag(64)}
							</a>
						</div>
					</li>
				{/foreach}
			{/content}
		</ul>
	{hascontentelse}
		<!-- TODO: What should we display here? -->
		<p class="info">You are not yet following anyone</p>
	{/hascontent}
</section>

{include file='footer'}

</body>
</html>
