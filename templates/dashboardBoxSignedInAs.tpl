<fieldset class="dashboardBox dashboardBoxSignedInAs">
	<legend class="invisible">{lang}wcf.dashboard.box.{$box->boxName}{/lang}</legend>
	
	<div class="box96 framed">
		{@$__wcf->getUserProfileHandler()->getAvatar()->getImageTag(96)}
		
		<div>
			<hgroup class="containerHeadline">
				<h1><a href="{link controller='User' object=$__wcf->user}{/link}">{$__wcf->user->username}</a></h1>
				{if MODULE_USER_RANK && $__wcf->getUserProfileHandler()->getUserTitle()}<h2><span class="badge userTitleBadge{if $__wcf->getUserProfileHandler()->getRank() && $__wcf->getUserProfileHandler()->getRank()->cssClassName} {@$__wcf->getUserProfileHandler()->getRank()->cssClassName}{/if}">{$__wcf->getUserProfileHandler()->getUserTitle()}</span></h2>{/if}
			</hgroup>
			
			{include file='userInformationStatistics' user=$__wcf->user}
		</div>
	</div>
</fieldset>