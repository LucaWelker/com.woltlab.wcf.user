<style type="text/css">
	ol#recentActivity {
		width: 100%;
	}

	ol#recentActivity li {
		border-bottom: 1px solid rgb(192, 192, 192);
		display: table;
		width: 100%;
	}

	ol#recentActivity li > div {
		background-image: -o-linear-gradient(transparent, rgb(224, 224, 224));
		display: table-row;
	}

	ol#recentActivity li > div > p {
		display: table-cell;
		padding: 3px;
	}

	ol#recentActivity li > div > p:first-child {
		width: 48px;
	}

	ol#recentActivity li > div > p > span {
		display: block;
	}

	ol#recentActivity li > div > p > span:last-child {
		color: rgb(153, 153, 153);
		font-size: 85%;
		margin-top: 3px;

		-webkit-transition: color .2s linear;
		-moz-transition: color .2s linear;
		-ms-transition: color .2s linear;
		-o-transition: color .2s linear;
		transition: color .2s linear;
	}

	ol#recentActivity li:hover > div > p > span:last-child {
		color: rgb(102, 102, 102);
	}
</style>

<ol id="recentActivity">
	{foreach from=$eventList item=event}
		{assign var=__dummy value=$event->userProfile->getAvatar()->setMaxSize(48, 48)}
		<li>
			<div>
				<p class="userAvatar">
					<a href="{link controller='User' object=$event->userProfile}{/link}">{@$event->userProfile->getAvatar()}</a>
				</p>
				<p>
					<span>{@$event->text}</span>
					<span>{@$event->time|time}</span>
				</p>
			</div>
		</li>
	{/foreach}
</ol>