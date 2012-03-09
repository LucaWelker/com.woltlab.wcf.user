{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.signature.title{/lang} - {PAGE_TITLE|language}</title>
	
	{include file='headInclude' sandbox=false}
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='profileEditSidebar' sandbox=false}

{include file='header' sandbox=false sidebarOrientation='left'}

<header class="wcf-container wcf-mainHeading">
	<img src="{icon size='L'}add1{/icon}" alt="" class="wcf-containerIcon" />
	<hgroup class="wcf-containerContent">
		<h1>{lang}wcf.user.signature.title{/lang}</h1>
	</hgroup>
</header>

{if $errorField}
	<p class="wcf-error">{lang}wcf.global.form.error{/lang}</p>
{/if}

<form method="post" action="{link controller='SignatureEdit'}{/link}">
	<div class="wcf-box wcf-marginTop wcf-boxPadding wcf-shadow1">
		{if $signatureCache}
			<fieldset>
				<legend>{lang}wcf.user.signature.current{/lang}</legend>
				
				{@$signatureCache}
			</fieldset>
		{/if}
		
		{if $signaturePreview}
			<fieldset>
				<legend>{lang}wcf.user.signature.preview{/lang}</legend>
				
				{@$signaturePreview}
			</fieldset>
		{/if}
		
		<fieldset>
			<legend>{lang}wcf.user.signature.text{/lang}</legend>
				
			<dl class="wcf-wideEditor{if $errorField == 'text'} wcf-formError{/if}">
				<dt><label for="text">{lang}wcf.user.signature.text{/lang}</label></dt>
				<dd>
					<textarea id="text" name="text" rows="20" cols="40">{$text}</textarea>
					{if $errorField == 'text'}
						<small class="wcf-innerError">
							{if $errorType == 'empty'}
								{lang}wcf.global.form.error.empty{/lang}
							{elseif $errorType == 'tooLong'}
								{lang}wcf.message.error.tooLong{/lang}
							{else}
								{lang}wbb.post.message.error.{@$errorType}{/lang}
							{/if}
						</small>
					{/if}
				</dd>
			</dl>
		</fieldset>
		
		{include file='messageFormTabs'}
	</div>
	
	<div class="wcf-formSubmit">
		<input type="reset" value="{lang}wcf.global.button.reset{/lang}" accesskey="r" />
		<input type="submit" name="showPreview" value="{lang}wcf.global.button.preview{/lang}" accesskey="p" />
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
		<input type="hidden" name="tmpHash" value="{$tmpHash}" />
		{@SID_INPUT_TAG}
 	</div>
</form>

{include file='footer' sandbox=false}
{include file='wysiwyg'}

</body>
</html>