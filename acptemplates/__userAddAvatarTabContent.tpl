{if $action == 'edit'}
	<div id="avatarForm" class="container containerPadding tabMenuContent hidden">
		<fieldset>
			<legend>{lang}wcf.user.avatar{/lang}</legend>
			
			<dl>
				<dd>
					<label><input type="radio" name="avatarType" value="none" {if $avatarType == 'none'}checked="checked" {/if}/> {lang}wcf.user.avatar.type.none{/lang}</label>
				</dd>
			</dl>
			
			<dl class="jsOnly{if $errorType[customAvatar]|isset} formError{/if}" id="avatarUpload">
				<dt class="framed">{if $avatarType == 'custom'}{@$userAvatar->getImageTag(96)}{else}<img src="{@$__wcf->getPath()}images/avatars/avatar-default.svg" alt="" class="icon96" />{/if}</dt>
				<dd>
					<label><input type="radio" name="avatarType" value="custom" {if $avatarType == 'custom'}checked="checked" {/if}/> {lang}wcf.user.avatar.type.custom{/lang}</label>
					
					{* placeholder for upload button: *}
					<div></div>
					
					{if $errorType[customAvatar]|isset}
						<small class="innerError">
							{if $errorType[customAvatar] == 'empty'}{lang}wcf.global.form.error.empty{/lang}{/if}
						</small>
					{/if}
				</dd>
			</dl>
			
			{if MODULE_GRAVATAR}
				<dl{if $errorType[gravatar]|isset} class="formError"{/if}>
					<dt class="framed"><img src="http://www.gravatar.com/avatar/{@$user->email|strtolower|md5}?s=96" alt="" class="icon96" /></dt>
					<dd>
						<label><input type="radio" name="avatarType" value="gravatar" {if $avatarType == 'gravatar'}checked="checked" {/if}/> {lang}wcf.user.avatar.type.gravatar{/lang}</label>
						
						{if $errorType[gravatar]|isset}
							<small class="innerError">
								{if $errorType[gravatar] == 'notFound'}{lang}wcf.user.avatar.type.gravatar.error.notFound{/lang}{/if}
							</small>
						{/if}
					</dd>
				</dl>
			{/if}
		</fieldset>
		
		<fieldset>
			<legend>{lang}wcf.acp.user.disableAvatar{/lang}</legend>
			
			<dl>
				<dd>
					<label><input type="checkbox" id="disableAvatar" name="disableAvatar" value="1" {if $disableAvatar == 1}checked="checked" {/if}/> {lang}wcf.acp.user.disableAvatar{/lang}</label>
				</dd>
			</dl>
			
			<dl>
				<dt><label for="disableAvatarReason">{lang}wcf.acp.user.disableAvatarReason{/lang}</label></dt>
				<dd>
					<textarea name="disableAvatarReason" id="disableAvatarReason" cols="40" rows="10">{$disableAvatarReason}</textarea>
				</dd>
			</dl>
		</fieldset>
		
		<script type="text/javascript" src="{@$__wcf->getPath()}js/WCF.Message{if !ENABLE_DEBUG_MODE}.min{/if}.js"></script>
		<script type="text/javascript" src="{@$__wcf->getPath()}js/WCF.User{if !ENABLE_DEBUG_MODE}.min{/if}.js"></script>
		<script type="text/javascript">
			//<![CDATA[
			$(function() {
				$('#disableAvatar').change(function (event) {
					if ($('#disableAvatar').is(':checked')) {
						$('#disableAvatarReason').attr('readonly', false);
					}
					else {
						$('#disableAvatarReason').attr('readonly', true);
					}
				});
				$('#disableAvatar').change();
				
				WCF.Language.addObject({
					'wcf.user.avatar.upload.error.invalidExtension': '{lang}wcf.user.avatar.upload.error.invalidExtension{/lang}',
					'wcf.user.avatar.upload.error.tooSmall': '{lang}wcf.user.avatar.upload.error.tooSmall{/lang}',
					'wcf.user.avatar.upload.error.tooLarge': '{lang}wcf.user.avatar.upload.error.tooLarge{/lang}',
					'wcf.user.avatar.upload.error.uploadFailed': '{lang}wcf.user.avatar.upload.error.uploadFailed{/lang}',
					'wcf.user.avatar.upload.error.badImage': '{lang}wcf.user.avatar.upload.error.badImage{/lang}',
					'wcf.user.avatar.upload.success': '{lang}wcf.user.avatar.upload.success{/lang}',
					'wcf.global.button.upload': '{lang}wcf.global.button.upload{/lang}'
				});
				
				new WCF.User.Avatar.Upload({@$user->userID});
			});
			//]]>
		</script>
	</div>
{/if}
