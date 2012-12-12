{include file='header' pageTitle='wcf.acp.user.rank.list'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.acp.user.rank.list{/lang}</h1>
	</hgroup>
	
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			new WCF.Action.Delete('wcf\\data\\user\\rank\\UserRankAction', $('.jsUserRankRow'), $('.tabularBox > hgroup > .badge'));
			
			$('#updateUserRanks').click(function () {
				$('#updateUserRanks').unbind('click');
				new WCF.ACP.Worker('updateUserRanks', 'wcf\\system\\worker\\UserRankUpdateWorker');
			});
		});
		//]]>
	</script>
</header>

<div class="contentNavigation">
	{pages print=true assign=pagesLinks controller="UserRankList" link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}
	
	<nav>
		<ul>
			<li><a id="updateUserRanks" title="{lang}wcf.acp.user.rank.updateRanks{/lang}" class="button"><img src="{@$__wcf->getPath()}icon/update.svg" alt="" class="icon24" /> <span>{lang}wcf.acp.user.rank.updateRanks{/lang}</span></a></li>
			<li><a href="{link controller='UserRankAdd'}{/link}" title="{lang}wcf.acp.user.rank.add{/lang}" class="button"><img src="{@$__wcf->getPath()}icon/add.svg" alt="" class="icon24" /> <span>{lang}wcf.acp.user.rank.add{/lang}</span></a></li>
			
			{event name='largeButtonsTop'}
		</ul>
	</nav>
</div>

{hascontent}
	<div class="tabularBox tabularBoxTitle marginTop">
		<hgroup>
			<h1>{lang}wcf.acp.user.rank.list{/lang} <span class="badge badgeInverse" title="{lang}wcf.acp.user.rank.list.count{/lang}">{#$items}</span></h1>
		</hgroup>
		
		<table class="table">
			<thead>
				<tr>
					<th class="columnID columnRankID{if $sortField == 'rankID'} active{/if}" colspan="2"><a href="{link controller='UserRankList'}pageNo={@$pageNo}&sortField=rankID&sortOrder={if $sortField == 'rankID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.global.objectID{/lang}{if $sortField == 'rankID'} <img src="{@$__wcf->getPath()}icon/sort{@$sortOrder}.svg" alt="" />{/if}</a></th>
					<th class="columnTitle columnRankTitle{if $sortField == 'rankTitle'} active{/if}"><a href="{link controller='UserRankList'}pageNo={@$pageNo}&sortField=rankTitle&sortOrder={if $sortField == 'rankTitle' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.acp.user.rank.title{/lang}{if $sortField == 'rankTitle'} <img src="{@$__wcf->getPath()}icon/sort{@$sortOrder}.svg" alt="" />{/if}</a></th>
					<th class="columnText columnRankImage{if $sortField == 'rankImage'} active{/if}"><a href="{link controller='UserRankList'}pageNo={@$pageNo}&sortField=rankImage&sortOrder={if $sortField == 'rankImage' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.acp.user.rank.image{/lang}{if $sortField == 'rankImage'} <img src="{@$__wcf->getPath()}icon/sort{@$sortOrder}.svg" alt="" />{/if}</a></th>
					<th class="columnText columnGroupID{if $sortField == 'groupID'} active{/if}"><a href="{link controller='UserRankList'}pageNo={@$pageNo}&sortField=groupID&sortOrder={if $sortField == 'groupID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.user.group{/lang}{if $sortField == 'groupID'} <img src="{@$__wcf->getPath()}icon/sort{@$sortOrder}.svg" alt="" />{/if}</a></th>
					<th class="columnText columnRequiredGender{if $sortField == 'requiredGender'} active{/if}"><a href="{link controller='UserRankList'}pageNo={@$pageNo}&sortField=requiredGender&sortOrder={if $sortField == 'requiredGender' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.user.option.gender{/lang}{if $sortField == 'requiredGender'} <img src="{@$__wcf->getPath()}icon/sort{@$sortOrder}.svg" alt="" />{/if}</a></th>
					<th class="columnDigits columnRequiredPoints{if $sortField == 'requiredPoints'} active{/if}"><a href="{link controller='UserRankList'}pageNo={@$pageNo}&sortField=requiredPoints&sortOrder={if $sortField == 'requiredPoints' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.acp.user.rank.requiredPoints{/lang}{if $sortField == 'requiredPoints'} <img src="{@$__wcf->getPath()}icon/sort{@$sortOrder}.svg" alt="" />{/if}</a></th>
					
					{event name='headColumns'}
				</tr>
			</thead>
			
			<tbody>
				{content}
					{foreach from=$objects item=userRank}
						<tr class="jsUserRankRow">
							<td class="columnIcon">
								<a href="{link controller='UserRankEdit' id=$userRank->rankID}{/link}"><img src="{@$__wcf->getPath()}icon/edit.svg" alt="" title="{lang}wcf.global.button.edit{/lang}" class="icon16 jsTooltip" /></a>
								<img src="{@$__wcf->getPath()}icon/delete.svg" alt="" title="{lang}wcf.global.button.delete{/lang}" class="icon16 jsDeleteButton jsTooltip pointer" data-object-id="{@$userRank->rankID}" data-confirm-message="{lang}wcf.acp.user.rank.delete.sure{/lang}" />
								
								{event name='buttons'}
							</td>
							<td class="columnID columnRankID"><p>{@$userRank->rankID}</p></td>
							<td class="columnTitle columnRankTitle"><p><a href="{link controller='UserRankEdit' id=$userRank->rankID}{/link}" title="{lang}wcf.acp.user.rank.edit{/lang}" class="badge label{if $userRank->cssClassName} {$userRank->cssClassName}{/if}">{$userRank->rankTitle|language}</a></p></td>
							<td class="columnText columnRankImage">{if $userRank->rankImage}<p>{@$userRank->getImage()}</p>{/if}</td>
							<td class="columnText columnGroupID"><p>{$userRank->groupName|language}</p></td>
							<td class="columnText columnRequiredGender">{if $userRank->requiredGender}<p>{if $userRank->requiredGender == 1}{lang}wcf.user.gender.male{/lang}{else}{lang}wcf.user.gender.female{/lang}{/if}</p>{/if}</td>
							<td class="columnDigits columnRequiredPoints"><p>{#$userRank->requiredPoints}</p></td>
							
							{event name='columns'}
						</tr>
					{/foreach}
				{/content}
			</tbody>
		</table>
		
	</div>
	
	<div class="contentNavigation">
		{@$pagesLinks}
		
		<nav>
			<ul>
				<li><a href="{link controller='UserRankAdd'}{/link}" title="{lang}wcf.acp.user.rank.add{/lang}" class="button"><img src="{@$__wcf->getPath()}icon/add.svg" alt="" class="icon24" /> <span>{lang}wcf.acp.user.rank.add{/lang}</span></a></li>
				
				{event name='largeButtonsBottom'}
			</ul>
		</nav>
	</div>
{hascontentelse}
	<p class="info">{lang}wcf.acp.user.rank.noneAvailable{/lang}</p>
{/hascontent}

{include file='footer'}
