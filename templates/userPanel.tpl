{if $__wcf->user->userID}
	{* avatar *}

	{* user note *}
	<p>Welcome {$__wcf->user->username}!</p>

	{* functions (login, registration) *}
	<ul>
		<li><a href="{link}index.php?action=Logout&t={@SECURITY_TOKEN}{/link}" onclick="return confirm('Are you sure?')">Logout</a></li>
	</ul>
{else}
	{* user note *}
	<p>Welcome guest!</p>
	
	{* login box *}
	<div style="display:none">
		<form method="post" action="{link}index.php?form=Login{/link}">
			<input type="text" name="username" value="" />
			<input type="password" name="password" value="" />
			<input type="submit" value="submit" />
		</form>
	</div>

	{* functions (login, registration) *}
	<ul>
		<li><a href="{link}index.php?form=Login{/link}">Login</a></li>
		<li><a href="{link}index.php?form=Register{/link}">Register</a></li>
	</ul>
	
	
{/if}