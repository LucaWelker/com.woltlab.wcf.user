{include file='documentHeader'}
<head>
	<title>Login form</title>
	
	{include file='headInclude' sandbox=false}
</head>
<body>
{include file='header' sandbox=false}

<form method="post" action="index.php?form=Login">
	<input type="text" name="username" value="{$username}" />
	<input type="password" name="password" value="{$password}" />
	<input type="submit" value="submit" />
</form>

{include file='footer' sandbox=false}

</body>
</html>