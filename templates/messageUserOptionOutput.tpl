<div id="{$option->optionName}">{@$value}</div>

<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#{$option->optionName}').parents('dl:eq(0)').addClass('wide');
});
//]]>
</script>