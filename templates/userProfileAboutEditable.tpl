<div class="containerPadding">
	{foreach from=$optionTree item=categoryLevel1}
		{foreach from=$categoryLevel1[categories] item=categoryLevel2}
			<fieldset>
				<legend>{lang}wcf.user.option.category.{@$categoryLevel2[object]->categoryName}{/lang}</legend>
				
				{if $categoryLevel2[object]->categoryName == 'profile.personal' && $__wcf->session->getPermission('user.profile.canEditUserTitle')}
					<dl>
						<dt><label for="__userTitle">{lang}wcf.user.userTitle{/lang}</label></dt>
						<dd>
							<input type="text" id="__userTitle" name="values[__userTitle]" value="{$__userTitle}" class="long" />
							<small>{lang}wcf.user.userTitle.description{/lang}</small>
							
							{if $errorType[__userTitle]|isset}
								<small class="innerError">
									{lang}wcf.user.userTitle.error.{@$errorType[__userTitle]}{/lang}
								</small>
							{/if}
						</dd>
					</dl>
				{/if}
				
				{include file='userProfileOptionFieldList' options=$categoryLevel2[options] langPrefix='wcf.user.option.'}
			</fieldset>
		{/foreach}
	{/foreach}
	
	<div class="formSubmit">
		<button class="primaryButton" accesskey="s" data-type="save">{lang}wcf.global.button.save{/lang}</button>
		<button data-type="restore">{lang}wcf.global.button.cancel{/lang}</button>
	</div>
</div>