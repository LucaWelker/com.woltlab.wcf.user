{include file="documentHeader"}

<head>
	<title>{lang}wcf.user.notification.settings.title{/lang} - {lang}wcf.user.usercp{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	
	{include file='headInclude' sandbox=false}
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='profileEditSidebar' sandbox=false}

{include file='header' sandbox=false sidebarOrientation='left'}

<header class="wcf-mainHeading">
	<img src="{icon size='L'}notificationSettings1{/icon}" alt="" />
	<hgroup>
		<h1>{lang}wcf.user.notification.settings.title{/lang}</h1>
	</hgroup>
</header>

{if $errorField}
	<p class="wcf-error">{lang}wcf.global.form.error{/lang}</p>
{/if}

<form method="post" action="{link controller='NotificationSettings'}{/link}">
	{foreach from=$events key=eventCategory item=eventList}
		<fieldset>
			<legend>{lang}{$eventCategory}{/lang}</legend>
			
			<ul>
				{foreach from=$eventList key=testig item=event}
					<li>
						<input id="settings{@$event->eventID}" type="checkbox" name="settings[{@$event->eventID}][enabled]" value="1"{if $settings[$event->eventID][enabled]} checked="checked"{/if} />
						<label for="settings{@$event->eventID}">{lang}{$eventCategory}.{$event->eventName}{/lang}</label>
						<select name="settings[{@$event->eventID}][type]">
							<option value="0"></option>
							{foreach from=$types item=type}
								<option value="{@$type->objectTypeID}"{if $settings[$event->eventID][type] == $type->objectTypeID} selected="selected"{/if}>{lang}{$type->objectType}{/lang}</option>
							{/foreach}
						</select>
					</li>
				{/foreach}
			</ul>
		</fieldset>
	{/foreach}
		
	<div class="wcf-formSubmit">
		{@SID_INPUT_TAG}
		<input type="reset" value="{lang}wcf.global.button.reset{/lang}" accesskey="r" />
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
	</div>
</form>

{include file='footer' sandbox=false}

</body>
</html>
