{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.dashboard{/lang}</title>
	{include file='headInclude'}
</head>

<body id="tpl{$templateName|ucfirst}">

{if $__boxSidebar|isset && $__boxSidebar}
	{capture assign='sidebar'}{@$__boxSidebar}{/capture}
{/if}

{include file='header' sidebarOrientation='right'}

<header class="boxHeadline">
	<hgroup >
		<h1>{lang}wcf.user.dashboard{/lang}</h1>
	</hgroup>
</header>

{include file='userNotice'}

<section id="dashboard">
	{if $__boxContent|isset}{@$__boxContent}{/if}
</section>

{include file='footer'}

</body>
</html>
