<?php
function modifyLink() {
?>
<script type="text/javascript">
for (var a in document.links) {
	document.links[a].href = '<?="http://$_SERVER[SERVER_NAME]" . substr($_SERVER["PHP_SELF"], 0, strrpos($_SERVER["PHP_SELF"], "/")) . "/";?>?page=' + encodeURIComponent(document.links[a].href);
}
</script>
<?php	
}

function urlForm($url = "") {
?>
	<form style="position: fixed; top: 0; left: 0; width: 100%; background: #000; z-index: 99999999; padding: .2em; text-align: center">
		<input name=page size=100 value="<?=htmlspecialchars($url)?>">
		<input type=submit value="Open">
		<input type=button onclick="this.parentNode.style.display = 'none'" value="Close">
	</form>
<?php
}

$page = isset($_GET["page"]) ? $_GET["page"] : "";

if (empty($page)) {
	urlForm();
	die();
}

if (!preg_match("/^http/", $page)) {
	$page = "http://" . $page;
}

$content = @file_get_contents($page);

if (!empty($content)) {
	$doctypeRegExp = "~<\!doctype html.*>~siU";
	if (preg_match($doctypeRegExp, $content)) {
		echo preg_replace($doctypeRegExp, "$0 <base href='$page'>", $content);
	}
	else {
		echo "<base href='{$page}'>" . $content;
	}
	modifyLink();
	urlForm($page);
}
else {
?>
	<p>Nepodařilo se načíst obsah. Buď není povolené na serveru vzdálené načítání, nebo se <i>něco</i> pokazilo.</p>
<?php
}