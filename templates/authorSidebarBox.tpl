<fieldset>
	<legend class="invisible">{lang}wcf.user.author{/lang}</legend>
	
	<div class="box96 framed">
		{@$userProfile->getAvatar()->getImageTag(96)}
		
		<div>
			<hgroup class="containerHeadline">
				<h1><a href="{link controller='User' object=$userProfile}{/link}">{$userProfile->username}</a></h1>
				{if MODULE_USER_RANK && $userProfile->getUserTitle()}<h2><span class="badge userTitleBadge{if $userProfile->getRank() && $userProfile->getRank()->cssClassName} {@$userProfile->getRank()->cssClassName}{/if}">{$userProfile->getUserTitle()}</span></h2>{/if}
			</hgroup>
			
			{include file='userInformationStatistics' user=$userProfile}
		</div>
	</div>
</fieldset>