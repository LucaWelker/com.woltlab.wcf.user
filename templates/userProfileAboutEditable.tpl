<div class="containerPadding">
	{foreach from=$optionTree item=categoryLevel1}
		{foreach from=$categoryLevel1[categories] item=categoryLevel2}
			<fieldset>
				<legend>{lang}wcf.user.option.category.{@$categoryLevel2[object]->categoryName}{/lang}</legend>
				
				{if $categoryLevel2[object]->categoryName == 'settings.general' && $availableLanguages|count > 1}
					<dl>
						<dt><label for="languageID">{lang}wcf.user.language{/lang}</label></dt>
						<dd>
							{htmlOptions options=$availableLanguages selected=$languageID name=languageID id=languageID disableEncoding=true}
						</dd>
					</dl>
					
					{if $availableContentLanguages|count > 1}
						<dl>
							<dt>
								{lang}wcf.user.visibleLanguages{/lang}
							</dt>
							<dd>
								<fieldset>
									<legend>{lang}wcf.user.visibleLanguages{/lang}</legend>
									<dl>
										<dd>
											{foreach from=$availableContentLanguages key=availableLanguageID item=availableLanguage}
												<label><input type="checkbox" name="visibleLanguages[]" value="{@$availableLanguageID}"{if $availableLanguageID|in_array:$visibleLanguages} checked="checked"{/if} /> {@$availableLanguage}</label>
											{/foreach}
										</dd>
									</dl>
								</fieldset>
							</dd>
						</dl>
					{/if}
				{/if}
				
				{include file='userProfileOptionFieldList' options=$categoryLevel2[options] langPrefix='wcf.user.option.'}
			</fieldset>
		{/foreach}
	{/foreach}
</div>