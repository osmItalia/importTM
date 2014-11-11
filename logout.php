<html>
<body>
<?php
session_start();
session_unset();
session_destroy();
?>

<h3>Logout effettuato</h3>
<p>
Per rientrare: <a href="login.php">Effettua l'accesso</a></p>
</body>
</html>

