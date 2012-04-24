<div class="box128">
	<a href="{link controller='User' object=$user}{/link}" title="{$user->username}" class="framed">{@$user->getAvatar()->getImageTag(128)}</a>
	
	<div>
		{include file='userInformation' sandbox=false}
		
		{*TODO: current location, browser, ip address*}
		{if $user->requestURI}<p>request uri: {$user->requestURI}</p>{/if}
	</div>
</div>