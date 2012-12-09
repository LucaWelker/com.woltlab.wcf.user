{include file='header'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.acp.user.rank.{$action}{/lang}</h1>
	</hgroup>
</header>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset}
	<p class="success">{lang}wcf.global.form.{$action}.success{/lang}</p>	
{/if}

<div class="contentNavigation">
	<nav>
		<ul>
			<li><a href="{link controller='UserRankList'}{/link}" title="{lang}wcf.acp.menu.link.user.rank.list{/lang}" class="button"><img src="{@$__wcf->getPath()}icon/list.svg" alt="" class="icon24" /> <span>{lang}wcf.acp.menu.link.user.rank.list{/lang}</span></a></li>
		</ul>
	</nav>
</div>

<form method="post" action="{if $action == 'add'}{link controller='UserRankAdd'}{/link}{else}{link controller='UserRankEdit' id=$rankID}{/link}{/if}">
	<div class="container containerPadding marginTop">
		<fieldset>
			<legend>{lang}wcf.acp.user.rank.general{/lang}</legend>
			
			<dl{if $errorField == 'rankTitle'} class="formError"{/if}>
				<dt><label for="rankTitle">{lang}wcf.acp.user.rank.title{/lang}</label></dt>
				<dd>
					<input type="text" id="rankTitle" name="rankTitle" value="{$rankTitle}" required="required" autofocus="autofocus" class="long" />
					{if $errorField == 'rankTitle'}
						<small class="innerError">
							{if $errorType == 'empty'}
								{lang}wcf.global.form.error.empty{/lang}
							{else if $errorType == 'multilingual'}
								{lang}wcf.global.form.error.multilingual{/lang}
							{else}
								{lang}wcf.acp.user.rank.title.error.{@$errorType}{/lang}
							{/if}
						</small>
					{/if}
				</dd>
			</dl>
			{include file='multipleLanguageInputJavascript' elementIdentifier='rankTitle'}
			
			<dl{if $errorField == 'cssClassName'} class="formError"{/if}>
				<dt><label for="cssClassName">{lang}wcf.acp.user.rank.cssClassName{/lang}</label></dt>
				<dd>
					<ul id="labelList">
						{foreach from=$availableCssClassNames item=className}
							{if $className == 'custom'}
								<li class="labelCustomClass"><label><input type="radio" name="cssClassName" value="custom"{if $cssClassName == 'custom'} checked="checked"{/if} /> <span><input type="text" id="customCssClassName" name="customCssClassName" value="{$customCssClassName}" class="long" /></span></label></li>
							{else}
								<li><label><input type="radio" name="cssClassName" value="{$className}"{if $cssClassName == $className} checked="checked"{/if} /> <span class="badge label{if $className != 'none'} {$className}{/if}">{lang}wcf.acp.user.rank.title{/lang}</span></label></li>
							{/if}
						{/foreach}
					</ul>
					
					{if $errorField == 'cssClassName'}
						<small class="innerError">
							{lang}wcf.acp.user.rank.cssClassName.error.{@$errorType}{/lang}
						</small>
					{/if}
					<small>{lang}wcf.acp.user.rank.cssClassName.description{/lang}</small>
				</dd>
			</dl>
		</fieldset>
		
		<fieldset>
			<legend>{lang}wcf.acp.user.rank.image{/lang}</legend>
			
			<dl{if $errorField == 'rankImage'} class="formError"{/if}>
				<dt><label for="rankImage">{lang}wcf.acp.user.rank.image{/lang}</label></dt>
				<dd>
					<input type="text" id="rankImage" name="rankImage" value="{$rankImage}" class="long" />
					{if $errorField == 'rankImage'}
						<small class="innerError">
							{lang}wcf.acp.user.rank.image.error.{@$errorType}{/lang}
						</small>
					{/if}
					<small>{lang}wcf.acp.user.rank.rankImage.description{/lang}</small>
				</dd>
			</dl>
			
			<dl{if $errorField == 'repeatImage'} class="formError"{/if}>
				<dt><label for="repeatImage">{lang}wcf.acp.user.rank.repeatImage{/lang}</label></dt>
				<dd>
					<input type="number" id="repeatImage" name="repeatImage" value="{@$repeatImage}" min="1" class="short" />
					{if $errorField == 'rankImage'}
						<small class="innerError">
							{lang}wcf.acp.user.rank.repeatImage.error.{@$errorType}{/lang}
						</small>
					{/if}
					<small>{lang}wcf.acp.user.rank.repeatImage.description{/lang}</small>
				</dd>
			</dl>
			
			{if $action == 'edit' && $rank->rankImage}
				<dl>
					<dt><label>{lang}wcf.acp.user.rank.currentImage{/lang}</label></dt>
					<dd>{@$rank->getImage()}</dd>
				</dl>
			{/if}
		</fieldset>
		
		<fieldset>
			<legend>{lang}wcf.acp.user.rank.requirement{/lang}</legend>
		
			<dl{if $errorField == 'groupID'} class="formError"{/if}>
				<dt><label for="groupID">{lang}wcf.user.group{/lang}</label></dt>
				<dd>
					<select id="groupID" name="groupID">
						{foreach from=$availableGroups item=group}
							<option value="{@$group->groupID}"{if $group->groupID == $groupID} selected="selected"{/if}>{$group->groupName|language}</option>
						{/foreach}
					</select>
					{if $errorField == 'groupID'}
						<small class="innerError">
							{if $errorType == 'empty'}
								{lang}wcf.global.form.error.empty{/lang}
							{else}
								{lang}wcf.acp.user.rank.userGroup.error.{@$errorType}{/lang}
							{/if}
						</small>
					{/if}
					<small>{lang}wcf.acp.user.rank.userGroup.description{/lang}</small>
				</dd>
			</dl>
			
			<dl{if $errorField == 'gender'} class="formError"{/if}>
				<dt><label for="gender">{lang}wcf.user.option.gender{/lang}</label></dt>
				<dd>
					<select id="gender" name="gender">
						<option value="0"></option>
						<option value="1"{if $gender == 1} selected="selected"{/if}>{lang}wcf.user.gender.male{/lang}</option>
						<option value="2"{if $gender == 2} selected="selected"{/if}>{lang}wcf.user.gender.female{/lang}</option>
					</select>
					{if $errorField == 'gender'}
						<small class="innerError">
							{lang}wcf.acp.user.rank.gender.error.{@$errorType}{/lang}
						</small>
					{/if}
					<small>{lang}wcf.acp.user.rank.gender.description{/lang}</small>
				</dd>
			</dl>
			
			<dl{if $errorField == 'neededPoints'} class="formError"{/if}>
				<dt><label for="neededPoints">{lang}wcf.acp.user.rank.neededPoints{/lang}</label></dt>
				<dd>
					<input type="number" id="neededPoints" name="neededPoints" value="{@$neededPoints}" min="0" class="medium" />
					{if $errorField == 'neededPoints'}
						<small class="innerError">
							{lang}wcf.acp.user.rank.neededPoints.error.{@$errorType}{/lang}
						</small>
					{/if}
					<small>{lang}wcf.acp.user.rank.neededPoints.description{/lang}</small>
				</dd>
			</dl>
			
			
		</fieldset>
	</div>
	
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
	</div>
</form>


{include file='footer'}
