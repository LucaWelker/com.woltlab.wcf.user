{if $categoryLevel2[object]->categoryName == 'profile.personal' && MODULE_USER_RANK}
	<dl>
		<dt><label for="userTitle">{lang}wcf.user.userTitle{/lang}</label></dt>
		<dd>
			<input type="text" id="userTitle" name="userTitle" value="{$userTitle}" class="long" maxlength="{@USER_TITLE_MAX_LENGTH}" />
			<small>{lang}wcf.user.userTitle.description{/lang}</small>
			
			{if $errorType[userTitle]|isset}
				<small class="innerError">
					{lang}wcf.user.userTitle.error.{@$errorType[userTitle]}{/lang}
				</small>
			{/if}
		</dd>
	</dl>
{/if}