<div class="box128 userProfilePreview">
	<a href="{link controller='User' object=$user}{/link}" title="{$user->username}">{@$user->getAvatar()->getImageTag(128)}</a>
	
	<div class="userInformation">
		{include file='userInformation' sandbox=false}
		
		{*TODO: show additional fields*}
		{hascontent}
			<dl class="dataList">
				{content}
					{if $user->occupation}
						<dt>{lang}wcf.user.option.occupation{/lang}</dt>
						<dd>{$user->occupation}</dd>
					{/if}
					{if $user->hobbies}
						<dt>{lang}wcf.user.option.hobbies{/lang}</dt>
						<dd>{$user->hobbies}</dd>
					{/if}
				{/content}
			</dl>
		{/hascontent}
	</div>
</div>