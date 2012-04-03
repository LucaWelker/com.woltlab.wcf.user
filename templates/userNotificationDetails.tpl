<div class="userNotificationDetails">
	<header class="box64 boxHeadline">
		<a href="{link controller='User' object=$author}{/link}" title="{$author->username}" class="framed">
			{@$author->getAvatar()->getImageTag(64)}
		</a>
		<hgroup>
			<h1 class="wcf-username">{$author->username}</h1>
			<h2>{@$time|time}</h2>
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
