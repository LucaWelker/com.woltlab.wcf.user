{foreach from=$watchedObjects item=watchedObject}
	<li>
		<a href="{$watchedObject->getLink()}" class="box24">
			<div class="framed">
				{@$watchedObject->getUserProfile()->getAvatar()->getImageTag(24)}
			</div>
			<hgroup>
				<h1>{$watchedObject->getTitle()}</h1>
				<h2><small>{$watchedObject->getUserProfile()->username} - {@$watchedObject->getLastUpdateTime()|time}</small></h2>
			</hgroup>
		</a>
	</li>
{/foreach}