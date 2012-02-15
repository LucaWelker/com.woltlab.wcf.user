{foreach from=$options item=category}
	{foreach from=$category[categories] item=optionCategory}
		<hgroup class="wcf-subHeading">
			<h1>{$optionCategory[object]->categoryName}</h1>
		</hgroup>
		<dl>
			{foreach from=$optionCategory[options] item=userOption}
				<dt>{$userOption[object]->optionName}</dt>
				<dd>{@$userOption[object]->optionValue}</dd>
			{/foreach}
		</dl>
	{/foreach}
{/foreach}
