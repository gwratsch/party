<?php
include_once 'settings.php';
unset($_SESSION["email"]);
unset($_SESSION["password"]);
unset($_SESSION["userid"]);
$_SESSION["valid"]= false;
?>
<!DOCTYPE html>
<html lang="nl">
<?php
  header("location: user.php");
  echo t("U bent uitgelogd");
  exit;
?>
</html>
