<div class="userNotificationDetails"><!-- ToDo: Style-prefixes! -->
	<header>
		<div class="row">
			<a href="{link controller='User' object=$author}{/link}" title="{$author->username}" class="wcf-userAvatarFramed">
				{@$author->getAvatar()->getImageTag(128)}
			</a>
			<hgroup>
				<h1 class="wcf-username">{$author->username}</h1>
				<h2>{@$time|time}</h2>
			</hgroup>
		</div>
	</header>
	<section>
		{@$message}
	</section>
	{if $buttons|count}
		<nav>
			<ul class="small-buttons"><!-- ToDo: Class-name written wrongly to prevent inheritance -->
				{foreach from=$buttons item=button}
					<li data-action="{$button[actionName]}" data-class-name="{$button[className]}" data-object-id="{@$button[objectID]}">{$button[label]}</li>
				{/foreach}
			</ul>
		</nav>
	{/if}
</div>
