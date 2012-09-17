<li>
	<div class="box48">
		<a href="{link controller='User' object=$user}{/link}" title="{$user->username}" class="framed">{@$user->getAvatar()->getImageTag(48)}</a>
			
		<div class="userInformation">
			{include file='userInformation'}
			
			{*TODO: show additional user information*}
			{if $user->hobbies}<p>{lang}wcf.user.option.hobbies{/lang}: {$user->hobbies}</p>{/if}
		</div>
	</div>
</li>