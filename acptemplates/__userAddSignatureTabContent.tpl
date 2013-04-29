{if MODULE_USER_SIGNATURE}
	<div id="signatureManagement" class="container containerPadding tabMenuContent hidden">
		<fieldset>
			<legend>{lang}wcf.user.signature{/lang}</legend>
			
			<dl>
				<dt><label for="signature">{lang}wcf.user.signature{/lang}</label></dt>
				<dd>
					<textarea name="signature" id="signature" cols="40" rows="10">{$signature}</textarea>
				</dd>
			</dl>
			
			<dl>
				<dt>{lang}wcf.message.settings{/lang}</dt>
				<dd>
					<label><input id="signatureEnableSmilies" name="signatureEnableSmilies" type="checkbox" value="1"{if $signatureEnableSmilies} checked="checked"{/if} /> {lang}wcf.message.settings.enableSmilies{/lang}</label>
					<label><input id="signatureEnableBBCodes" name="signatureEnableBBCodes" type="checkbox" value="1"{if $signatureEnableBBCodes} checked="checked"{/if} /> {lang}wcf.message.settings.enableBBCodes{/lang}</label>
					<label><input id="signatureEnableHtml" name="signatureEnableHtml" type="checkbox" value="1"{if $signatureEnableHtml} checked="checked"{/if} /> {lang}wcf.message.settings.enableHtml{/lang}</label>
				</dd>
			</dl>
		</fieldset>
		
		<fieldset>
			<legend>{lang}wcf.acp.user.disableSignature{/lang}</legend>
			
			<dl>
				<dd>
					<label><input type="checkbox" id="disableSignature" name="disableSignature" value="1" {if $disableSignature == 1}checked="checked" {/if}/> {lang}wcf.acp.user.disableSignature{/lang}</label>
				</dd>
			</dl>
			
			<dl>
				<dt><label for="disableSignatureReason">{lang}wcf.acp.user.disableSignatureReason{/lang}</label></dt>
				<dd>
					<textarea name="disableSignatureReason" id="disableSignatureReason" cols="40" rows="10">{$disableSignatureReason}</textarea>
				</dd>
			</dl>
		</fieldset>
		
		<script type="text/javascript">
			//<![CDATA[
			$('#disableSignature').change(function (event) {
				if ($('#disableSignature').is(':checked')) {
					$('#disableSignatureReason').attr('readonly', false);
				}
				else {
					$('#disableSignatureReason').attr('readonly', true);
				}
			});
			$('#disableSignature').change();
			//]]>
		</script>
	</div>
{/if}
