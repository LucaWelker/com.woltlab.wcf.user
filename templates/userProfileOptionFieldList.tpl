{foreach from=$options item=optionData}
	{assign var=option value=$optionData[object]}
	<dl class="{$option->optionName}Input">
		<dt{if $optionData[cssClassName]} class="{$optionData[cssClassName]}"{/if}><label for="{$option->optionName}">{lang}{@$langPrefix}{$option->optionName}{/lang}</label></dt>
		<dd>{@$optionData[html]}
			<small>{lang __optional=true}{@$langPrefix}{$option->optionName}.description{/lang}</small>
		</dd>
	</dl>
{/foreach}
