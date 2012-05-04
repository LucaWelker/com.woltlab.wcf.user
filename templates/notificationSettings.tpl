{include file="documentHeader"}

<head>
	<title>{lang}wcf.user.notification.settings.title{/lang} - {lang}wcf.user.usercp{/lang} - {PAGE_TITLE|language}</title>
	
	{include file='headInclude' sandbox=false}
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='userMenuSidebar' sandbox=false}

{include file='header' sandbox=false sidebarOrientation='left'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.user.menu.settings{/lang}: {lang}wcf.user.notification.settings.title{/lang}</h1>
	</hgroup>
</header>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

<form method="post" action="{link controller='NotificationSettings'}{/link}">
	<div class="container containerPadding marginTop shadow">
		{foreach from=$events key=eventCategory item=eventList}
			<fieldset>
				<legend>{lang}wcf.user.notification.{$eventCategory}{/lang}</legend>
				
				<ul>
					{foreach from=$eventList item=event}
						<li>
							<input id="settings{@$event->eventID}" type="checkbox" name="settings[{@$event->eventID}][enabled]" value="1"{if $settings[$event->eventID][enabled]} checked="checked"{/if} />
							<label for="settings{@$event->eventID}">{lang}wcf.user.notification.{$eventCategory}.{$event->eventName}{/lang}</label>
							<select name="settings[{@$event->eventID}][type]">
								<option value="0"></option>
								{foreach from=$types item=type}
									<option value="{@$type->objectTypeID}"{if $settings[$event->eventID][type] == $type->objectTypeID} selected="selected"{/if}>{lang}wcf.user.notification.{$type->objectType}{/lang}</option>
								{/foreach}
							</select>
						</li>
					{/foreach}
				</ul>
			</fieldset>
		{/foreach}
	</div>
	
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
	</div>
</form>

{include file='footer' sandbox=false}

</body>
</html>
