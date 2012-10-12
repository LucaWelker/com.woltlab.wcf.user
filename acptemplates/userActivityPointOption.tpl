{include file='header'}

<script type="text/javascript">
	//<![CDATA[
	$(function() {
		$('#updateCache').click(function () {
			$('#updateCache').unbind('click');
			new WCF.ACP.Worker('cache', 'wcf\\system\\worker\\UserActivityPointUpdateCacheWorker');
		});
		$('#updateEvents').click(function () {
			$('#updateEvents').unbind('click');
			new WCF.ACP.Worker('events', 'wcf\\system\\worker\\UserActivityPointUpdateEventsWorker');
		});
	});
	//]]>
</script>

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.acp.user.activityPoint.option{/lang}</h1>
	</hgroup>
</header>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset}
	<p class="success">TODO</p>
{/if}

<div class="contentNavigation">
	<nav>
		<ul>
			<li><a id="updateCache" title="{lang}wcf.acp.user.activityPoint.updateCache{/lang}" class="button"><img src="{@$__wcf->getPath()}icon/update.svg" alt="" class="icon24" /> <span>{lang}wcf.acp.user.activityPoint.updateCache{/lang}</span></a></li>
			<li><a id="updateEvents" title="{lang}wcf.acp.user.activityPoint.updateEvents{/lang}" class="button"><img src="{@$__wcf->getPath()}icon/update.svg" alt="" class="icon24" /> <span>{lang}wcf.acp.user.activityPoint.updateEvents{/lang}</span></a></li>
			
			{event name='largeButtons'}
		</ul>
	</nav>
</div>

<form method="post" action="{link controller='UserActivityPointOption'}{/link}">
	<div class="container containerPadding marginTop shadow">
		<fieldset>
			<legend>{lang}wcf.user.activity.point.pointsPerObject{/lang}</legend>
			{foreach from=$objectTypes item='objectType'}
				<dl{if $errorField == $objectType->objectTypeID} class="formError"{/if}>
					<dt><label for="{$objectType->objectType}">{lang}wcf.user.activity.point.objectType.{$objectType->objectType}{/lang}</label></dt>
					<dd>
						<input type="text" id="{$objectType->objectType}" name="points[{$objectType->objectTypeID}]" value="{$points[$objectType->objectTypeID]}" required="required" pattern="^[0-9]+$" class="medium" />
						{if $errorField == $objectType->objectTypeID}
							<small class="innerError">
								{lang}wcf.acp.user.activityPoint.option.invalid{/lang}
							</small>
						{/if}
					</dd>
				</dl>
			{/foreach}
		</fieldset>
	</div>
	
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
 		{if $cronjobID|isset}<input type="hidden" name="id" value="{@$cronjobID}" />{/if}
	</div>
</form>

{include file='footer'}