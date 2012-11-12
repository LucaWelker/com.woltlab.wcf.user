<div class="userNotificationDetails">
	<header class="box32">
		<a href="{link controller='User' object=$author}{/link}" title="{$author->username}" class="framed">
			{@$author->getAvatar()->getImageTag(32)}
		</a>
		<hgroup class="containerHeadline">
			<h1><a href="{link controller='User' object=$author}{/link}">{$author->username}</a></h1>
			<h2><small>{@$time|time}</small></h2>
		</hgroup>
	</header>
	<section>
		{@$message}
	</section>
	{if $buttons|count}
		<nav>
			<ul>
				{foreach from=$buttons item=button}
					<li data-action="{$button[actionName]}" data-class-name="{$button[className]}" data-object-id="{@$button[objectID]}" class="button">{$button[label]}</li>
				{/foreach}
			</ul>
		</nav>
	{/if}
</div>
