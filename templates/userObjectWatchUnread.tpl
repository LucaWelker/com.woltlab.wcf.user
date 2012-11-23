{foreach from=$watchedObjects item=watchedObject}
	<li>
		{include file=$watchedObject->getTemplateName() application=$watchedObject->getApplication()}
	</li>
{/foreach}