<div id="userActivityPointListContainer" class="tabularBox marginTop">
	<table class="table">
		<thead>
			<tr>
				<th>{lang}wcf.user.activityPoints.objects{/lang}</th>
				<th>{lang}wcf.user.activityPoints.objectType{/lang}</th>
				<th>{lang}wcf.user.activityPoints.pointsPerObject{/lang}</th>
				<th>{lang}wcf.user.activityPoints.sum{/lang}</th>
			</tr>
		</thead>
		<tbody>
			{assign var='activityPointSum' value=0}
			{foreach from=$activityPointObjectTypes item='objectType'}
				{if $objectType->activityPoints > 0 && $objectType->points > 0}
					<tr>
						<td class="columnDigits">
							{#$objectType->activityPoints/$objectType->points} ×
						</td>
						<td class="columnTitle">
							{lang}wcf.user.activityPoints.objectType.{$objectType->objectType}{/lang}
						</td>
						<td class="columnDigits">
							{#$objectType->points}
						</td>
						<td class="columnDigits">
							{#$objectType->activityPoints}
						</td>
						{assign var='activityPointSum' value=$activityPointSum + $objectType->activityPoints}
					</tr>
				{/if}
			{/foreach}
			
			{if $user->activityPoints - $activityPointSum > 0}
				<tr>
					<td class="columnTitle right" colspan="3">{lang}wcf.user.activityPoints.notInDependency{/lang}</td>
					<td class="columnDigits">{#$user->activityPoints - $activityPointSum}</td>
				</tr>
			{/if}
			<tr>
				<td class="columnTitle focus right" colspan="3">Σ</td>
				<td class="columnDigits focus"><span class="badge">{#$user->activityPoints}</span></td>
			</tr>
		</tbody>
	</table>
</div>