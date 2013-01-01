{include file='documentHeader'}

<head>
	<title>{if $__wcf->getPageMenu()->getLandingPage()->menuItem != 'wcf.user.dashboard'}{lang}wcf.user.dashboard{/lang} - {/if}{PAGE_TITLE|language}</title>
	{include file='headInclude'}
</head>

<body id="tpl{$templateName|ucfirst}">

{if $__boxSidebar|isset && $__boxSidebar}
	{capture assign='sidebar'}
		{@$__boxSidebar}
	{/capture}
{/if}

{include file='header' sidebarOrientation='right'}

{if $__wcf->getPageMenu()->getLandingPage()->menuItem == 'wcf.user.dashboard'}
	<header class="boxHeadline">
		<hgroup>
			<h1>{PAGE_TITLE|language}</h1>
			{hascontent}<h2>{content}{PAGE_DESCRIPTION|language}{/content}</h2>{/hascontent}
		</hgroup>
	</header>
{else}
	<header class="boxHeadline">
		<hgroup>
			<h1>{lang}wcf.user.dashboard{/lang}</h1>
		</hgroup>
	</header>
{/if}

{include file='userNotice'}

<section id="dashboard">
	{if $__boxContent|isset}{@$__boxContent}{/if}
</section>

{include file='footer'}

</body>
</html>
