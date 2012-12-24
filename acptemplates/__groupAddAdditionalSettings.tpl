<dl{if $errorType.priority|isset} class="formError"{/if}>
	<dt><label for="priority">{lang}wcf.acp.group.priority{/lang}</label></dt>
	<dd>
		<input type="number" id="priority" name="priority" value="{@$priority}" class="medium" />
		{if $errorType.priority|isset}
			<small class="innerError">
				{lang}wcf.acp.group.priority.error.{@$errorType.priority}{/lang}
			</small>
		{/if}
		<small>{lang}wcf.acp.group.priority.description{/lang}</small>
	</dd>
</dl>

{if MODULE_USERS_ONLINE}
	<dl{if $errorType.userOnlineMarking|isset} class="formError"{/if}>
		<dt><label for="userOnlineMarking">{lang}wcf.acp.group.userOnlineMarking{/lang}</label></dt>
		<dd>
			<input type="text" id="userOnlineMarking" name="userOnlineMarking" value="{$userOnlineMarking}" class="long" />
			{if $errorType.userOnlineMarking|isset}
				<small class="innerError">
					{lang}wcf.acp.group.userOnlineMarking.error.{@$errorType.priority}{/lang}
				</small>
			{/if}
			<small>{lang}wcf.acp.group.userOnlineMarking.description{/lang}</small>
		</dd>
	</dl>
{/if}

{if MODULE_TEAM_PAGE && ($action == 'add' || $group->groupType > 3)}
	<dl>
		<dd>
			<label><input type="checkbox" id="showOnTeamPage" name="showOnTeamPage" value="1" {if $showOnTeamPage}checked="checked" {/if}/> {lang}wcf.acp.group.showOnTeamPage{/lang}</label>
		</dd>
	</dl>
{/if}