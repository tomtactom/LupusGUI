<?php
	$show_only_user = false;
	$title = 'Logout';
	$description = 'Du wurdest erfolgreich ausgeloggt';
	$keywords = 'logout, abmelden';
    include($_SERVER['DOCUMENT_ROOT'].'/include/backend/head.inc.php');
    include($_SERVER['DOCUMENT_ROOT'].'/include/backend/header.inc.php');
?>
<article>
    <section>
		<h1>Logout</h1>
		<?php
			require($options['pluginpath'].'logout.inc.php');
			get('error');
			include($options['pluginhtmlpath'].'logout.html.inc.php');
		?>
    </section>
</article>
<?php 
	include($_SERVER['DOCUMENT_ROOT']."/include/backend/footer.inc.php");
?>
