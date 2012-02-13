{capture assign='sidebar'}

{* THIS MENU IS A DAMN PROTOTYPE, STOP MOANING ALREADY! *}
<nav id="sidebarContent" class="wcf-sidebarContent">
	{* profile *}
	<div class="wcf-menuContainer" id="profileMenuItems">
		<h1 class="wcf-menuHeader">profile</h1>
		<div class="wcf-sidebarContentGroup">
			<ul>
				<li><a href="{link controller='AccountManagement'}{/link}">account management</a></li>
				<li><a href="{link controller='AvatarEdit'}{/link}">avatar</a></li>
				<li><a href="{link controller='SignatureEdit'}{/link}">signature</a></li>
			</ul>
		</div>
	</div>
	
	{* settings *}
	<div class="wcf-menuContainer" id="settingsMenuItems">
		<h1 class="wcf-menuHeader">settings</h1>
		<div class="wcf-sidebarContentGroup">
			<ul>
				<li><a href="{link controller='DynamicOptions'}{/link}">dynamic options</a></li>
				<li><a href="{link controller='StaticOptions'}{/link}">static options?</a></li>
				<li><a href="{link controller='NotificationPreferences'}{/link}">notification preferences</a></li>
			</ul>
		</div>
	</div>
	
	{* community *}
	<div class="wcf-menuContainer" id="communityMenuItems">
		<h1 class="wcf-menuHeader">community</h1>
		<div class="wcf-sidebarContentGroup">
			<ul>
				<li><a href="{link controller='NotificationList'}{/link}">notifications</a></li>
				<li><a href="{link controller='FollowList'}{/link}">follows</a></li>
				<li><a href="{link controller='IgnoredUsers'}{/link}">ignored users</a></li>
			</ul>
		</div>
	</div>
	
	{* collapse sidebar *}			
	<span class="wcf-collapsibleSidebarButton" title="{lang}wcf.global.button.collapsible{/lang}"><span></span></span>
</nav>

{/capture}