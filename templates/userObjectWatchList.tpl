{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.watchedObjects{/lang}</title>
	{include file='headInclude'}
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='header'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.user.watchedObjects{/lang} <span class="badge">{#$items}</span></h1>
	</hgroup>
</header>

{include file='userNotice'}

<div class="contentNavigation">
	{pages print=true assign=pagesLinks controller='UserObjectWatchList' link="pageNo=%d"}
</div>

{hascontent}
	<div class="container marginTop shadow">
		<ul class="containerList">
			{content}
				{foreach from=$objects->getObjects() item=watchedObject}
					<li>
						{include file=$watchedObject->getTemplateName()}
					</li>
				{/foreach}
			{/content}
		</ul>
	</div>
{hascontentelse}
	<!-- TODO: What should we display here? -->
	<p class="info">There are no watched objects for you yet</p>
{/hascontent}

<div class="contentNavigation">
	{@$pagesLinks}
</div>

{include file='footer'}

</body>
</html>
