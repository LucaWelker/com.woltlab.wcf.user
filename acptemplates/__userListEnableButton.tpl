{if $__wcf->session->getPermission('admin.user.canEnableUser')}
	{if $user->userID != $__wcf->user->userID}
		<span class="icon icon16 icon-{if !$user->activationCode}circle-blank{else}off{/if} jsEnableButton jsTooltip pointer" title="{lang}wcf.acp.user.{if !$user->activationCode}disable{else}enable{/if}{/lang}" data-object-id="{@$user->userID}" data-enable-message="{lang}wcf.acp.user.enable{/lang}" data-disable-message="{lang}wcf.acp.user.disable{/lang}" data-enabled="{if !$user->activationCode}true{else}false{/if}"></span>
	{else}
		<span class="icon icon16 icon-{if !$user->activationCode}circle-blank{else}off{/if} disabled" title="{lang}wcf.acp.user.{if !$user->activationCode}disable{else}enable{/if}{/lang}"></span>
	{/if}
{/if}
