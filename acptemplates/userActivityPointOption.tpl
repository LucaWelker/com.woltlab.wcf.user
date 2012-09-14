{include file='header'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.acp.user.activityPoint.option{/lang}</h1>
	</hgroup>
</header>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset}
	<p class="success">TODO</p>
{/if}


<form method="post" action="{link controller='UserActivityPointOption'}{/link}">
	<div class="container containerPadding marginTop shadow">
		<fieldset>
			<legend>{lang}wcf.global.form.data{/lang}</legend>
			{foreach from=$objectTypes item='objectType'}
				<dl{if $errorField == $objectType->objectTypeID} class="formError"{/if}>
					<dt><label for="{$objectType->objectType}">{$objectType->objectType}</label></dt>
					<dd>
						<input type="text" id="{$objectType->objectType}" name="points[{$objectType->objectTypeID}]" value="{$points[$objectType->objectTypeID]}" required="required" pattern="^[0-9]+$" class="medium" />
						{if $errorField == $objectType->objectTypeID}
							<small class="innerError">
								{lang}wcf.acp.user.activityPoint.option.invalid{/lang}
							</small>
						{/if}
					</dd>
				</dl>
			{/foreach}
		</fieldset>
	</div>
	
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
 		{if $cronjobID|isset}<input type="hidden" name="id" value="{@$cronjobID}" />{/if}
	</div>
</form>

{include file='footer'}