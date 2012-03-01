{foreach from=$options item=category}
	{foreach from=$category[categories] item=optionCategory}
		<hgroup class="wcf-subHeading">
			<h1>{lang}wcf.user.option.category.{@$optionCategory[object]->categoryName}{/lang}</h1>
		</hgroup>
		<dl>
			{foreach from=$optionCategory[options] item=userOption}
				<dt>{lang}wcf.user.option.{@$userOption[object]->optionName}{/lang}</dt>
				<dd>{@$userOption[object]->optionValue}</dd>
			{/foreach}
		</dl>
	{/foreach}
{/foreach}
