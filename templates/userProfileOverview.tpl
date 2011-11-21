{foreach from=$options item=category}
	{foreach from=$category[categories] item=optionCategory}
		<hgroup class="subHeading">
			<h1>{$optionCategory[object]->categoryName}</h1>
		</hgroup>
		<ul>
			{foreach from=$optionCategory[options] item=userOption}
				<li>{$userOption[object]->optionName} = {@$userOption[object]->optionValue}</li>
			{/foreach}
		</ul>
	{/foreach}
{/foreach}
