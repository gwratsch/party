<?php
include_once 'config.php';
$page= 'user.php';
$database = config();
if($database['dbname'] == ''){
    $page = 'install.php';
}
?>
<!DOCTYPE html>
<html lang="nl">
<?php
  header("location: $page");
  exit;
?>
</html>
