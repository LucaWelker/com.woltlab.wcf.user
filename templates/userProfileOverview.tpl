{foreach from=$options key=categoryName item=userOptions}
	<hgroup class="subHeading">
		<h1>{$categoryName}</h1>
	</hgroup>
	<ul>
		{foreach from=$userOptions item=userOption}
			<li>{$userOption->optionName} = {@$userOption->optionValue}</li>
		{/foreach}
	</ul>
{/foreach}
