<?php require_once("./check.php"); ?>

<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $title;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.0/css/foundation.min.css" />
    <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.0/css/normalize.css" />
  </head>
  <body>

<?php
//header("Content-Type: application/vnd.ms-excel");
//header("Expires: 0");
//header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//header("content-disposition: attachment;filename=export-users.xls");

require_once("../db.php");

try {
  $dbh = new PDO($dsn, $dbuser, $dbpass,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
} catch (PDOException $e) {
  $error = array("error" =>  'Error connection'); //$e->getMessage()
  echo json_encode($error);
  die();
}

if(isset($_GET['date']) && $_GET['date']!='') {
  $date = $_GET['date'];
  $sql = 'SELECT * FROM `user` WHERE `user`.`created_at` > "' . addslashes($date) . ' 00:00:00" AND `user`.`created_at` < "' . addslashes($date) . ' 23:59:59";';
} else {
  $sql = 'SELECT * FROM `user`;';
}

$stmt = $dbh->prepare($sql);
$executed = $stmt->execute();
$tab = $stmt->fetchAll();
?>

<table>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Phone</th>
    <th>Email</th>
    <th>Share</th>
    <th>Credits</th>
    <th>Last Event1</th>
    <th>Last Share</th>
    <th>Created Date</th>
  </tr>
  <?php
    foreach($tab as $row) :
  ?>
  <tr>
    <td><?php echo $row['user_id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['phone']; ?></td>
    <td><?php echo $row['email']; ?></td>
    <td><?php echo $row['share']; ?></td>
    <td><?php echo $row['credits']; ?></td>
    <td><?php echo $row['last_play']; ?></td>
    <td><?php echo $row['last_share']; ?></td>
    <td><?php echo $row['created_at']; ?></td>
  </tr>
  <?php endforeach; ?>
</table>

<?php $dbh = null;?>

  </body>
</html>