<?php require_once("./check.php");

$title = 'Evasion 3rd Anniversary';

function resetPDO($dbh) {
  include("../db.php");
  $dbh = null;
  try {
    $dbh = new PDO($dsn, $dbuser, $dbpass,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
  } catch (PDOException $e) {
    $error = array("error" =>  'Error connection'); //$e->getMessage()
    echo json_encode($error);
    die();
  }
  return $dbh;
}

$dbh = null;

if (isset($_POST['prize_id']) and isset($_POST['quantity'])) {
  $dbh = resetPDO($dbh);
  $sql = "UPDATE `prize` SET `quantity` = ".intval($_POST['quantity'])." WHERE `prize_id` = :id;";
  $stmt = $dbh->prepare($sql);
  $executed = $stmt->execute(array(':id' =>  intval($_POST['prize_id'])));
}

if (isset($_POST['comment_id'])) {
  $dbh = resetPDO($dbh);
  $sql = "UPDATE `comment` SET `validated` = 0 WHERE `comment_id` = :id;";
  $stmt = $dbh->prepare($sql);
  $executed = $stmt->execute(array(':id' =>  intval($_POST['comment_id'])));
}

$dbh = resetPDO($dbh);
$sql = 'SELECT COUNT(DISTINCT `user_id`) AS total FROM `user`;';
$stmt = $dbh->prepare($sql);
$executed = $stmt->execute();
$tab = $stmt->fetch(PDO::FETCH_ASSOC);
$total = $tab['total'];

$dbh = resetPDO($dbh);
$sql = 'SELECT COUNT(DISTINCT `user_id`) AS shared FROM `user` WHERE `share` != 0;';
$stmt = $dbh->prepare($sql);
$executed = $stmt->execute();
$tab = $stmt->fetch(PDO::FETCH_ASSOC);
$nbShared = $tab['shared'];

$dbh = resetPDO($dbh);
$sql = "SELECT COUNT(DISTINCT `user_id`) AS event1 FROM `user` WHERE `last_play` != '0000-00-00';";
$stmt = $dbh->prepare($sql);
$executed = $stmt->execute();
$tab = $stmt->fetch(PDO::FETCH_ASSOC);
$nbEvent1 = $tab['event1'];

$dbh = resetPDO($dbh);
$sql = "SELECT COUNT(DISTINCT `user_id`) AS event2 FROM `comment`;";
$stmt = $dbh->prepare($sql);
$executed = $stmt->execute();
$tab = $stmt->fetch(PDO::FETCH_ASSOC);
$nbEvent2 = $tab['event2'];

$dbh = resetPDO($dbh);
$sql = "SELECT COUNT(DISTINCT `comment_id`) AS commented FROM `comment`;";
$stmt = $dbh->prepare($sql);
$executed = $stmt->execute();
$tab = $stmt->fetch(PDO::FETCH_ASSOC);
$nbComments = $tab['commented'];

$dbh = resetPDO($dbh);
$sql = "SELECT COUNT(*) AS winner FROM `winner`;";
$stmt = $dbh->prepare($sql);
$executed = $stmt->execute();
$tab = $stmt->fetch(PDO::FETCH_ASSOC);
$nbWinners = $tab['winner'];

$dbh = resetPDO($dbh);
$sql = "SELECT COUNT(DISTINCT `user_id`) AS players, year(`created_at`) AS year, month(`created_at`) AS month, day(`created_at`) AS day FROM `user` GROUP BY year(`created_at`), month(`created_at`), day(`created_at`);";
$stmt = $dbh->prepare($sql);
$executed = $stmt->execute();
$tab = $stmt->fetchAll();
?>

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
    <div class="row">
      <div class="columns large-12">
        <br>
        <p class="text-right"><a href="./deco.php">Logout</a></p>
        <h1 class="page-header"><?php echo $title;?> - Database overview</h1>
        <br>
        <p style="color:red"><strong>All percentages (%) are based on the total number of players.</strong></p>
        <br>
        <h2>Metrics</h2>
        <table>
          <tr>
            <th>Total number of players</th>
            <td><?php echo $total; ?></td>
            <td><?php echo round(($total/$total) *100, 2); ?>%</td>
          </tr>
          <tr>
            <th>Number of players who have shared</th>
            <td><?php echo $nbShared; ?></td>
            <td><?php echo round(($nbShared/$total) *100, 2); ?>%</td>
          </tr>
          <tr>
            <th>Number of Event1 players</th>
            <td><?php echo $nbEvent1; ?></td>
            <td><?php echo round(($nbEvent1/$total) *100, 2); ?>%</td>
          </tr>
          <tr>
            <th>Number of winners for Event1</th>
            <td><?php echo $nbWinners; ?></td>
            <td><?php echo round(($nbWinners/$total) *100, 2); ?>%</td>
          </tr>
          <tr>
            <th>Number of Event2 players</th>
            <td><?php echo $nbEvent2; ?></td>
            <td><?php echo round(($nbEvent2/$total) *100, 2); ?>%</td>
          </tr>
          <tr>
            <th>Number of comments</th>
            <td><?php echo $nbComments; ?></td>
            <td></td>
          </tr>
        </table>
        <br>

        <script type="text/javascript">
          google.load('visualization', '1.1', {packages: ['line']});
          google.setOnLoadCallback(drawChart);

          function drawChart() {

            var data = new google.visualization.DataTable();
            data.addColumn('date', 'date');
            data.addColumn('number', 'players');

            data.addRows([
            <?php foreach($tab as $row) :?>
            <?php echo '[new Date('.$row['year'].','.$row['month'].','.$row['day'].'),'.$row['players'].'],'; ?>
            <?php endforeach; ?>
            ]);

            var options = {
              chart: {
                title: 'Number of players',
                subtitle: 'per day'
              },
            };

            var chart = new google.charts.Line(document.getElementById('linechart_material'));

            chart.draw(data, options);
          }
        </script>

        <div id="linechart_material"></div>

        <?php
          $dbh = resetPDO($dbh);
          $sql = "SELECT `prize`.*, COUNT(`winner`.`prize_id`) AS alreadyWon FROM `prize` LEFT JOIN `winner` ON `prize`.`prize_id` = `winner`.`prize_id` GROUP BY `prize`.`prize_id`;";
          $stmt = $dbh->prepare($sql);
          $executed = $stmt->execute();
          $tab = $stmt->fetchAll();
        ?>
        <br>
        <hr>
        <br>
        <h2>Prizes</h2>
        <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Quantity</th>
            <th style="color:red">Already won</th>
            <th>New quantity</th>
          </tr>
          <?php
            foreach($tab as $row) :
          ?>
          <tr>
            <td><?php echo $row['prize_id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['quantity']; ?></td>
            <td style="color:red"><?php echo $row['alreadyWon']; ?></td>
            <td>
              <form action="admin.php" method="POST" onsubmit="return confirm('Do you really want to update the quantity?');">
                <input name="quantity" type="number" required>
                <input type="hidden"  name="prize_id" value="<?php echo $row['prize_id']; ?>">
                <input type="submit" value="Update">
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </table>

        <?php
          $dbh = resetPDO($dbh);
          $sql = "SELECT * FROM `user` ORDER BY `user`.`created_at` DESC LIMIT 10;";
          $stmt = $dbh->prepare($sql);
          $executed = $stmt->execute();
          $tab = $stmt->fetchAll();
        ?>
        <br>
        <hr>
        <br>
        <h2>Last Users</h2>
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

        <br>
        <h2>Download users</h2>
        <form action="export.php" method="get" style="max-width:150px;">
          <input name="date" type="date">
          <input type="submit" value="Download">
        </form>

        <?php
          $dbh = resetPDO($dbh);
          $sql = "SELECT `user`.*, `winner`.`created_at` AS won_date, `prize`.`prize_id` AS prize_id, `prize`.`name` AS prize FROM `user`, `winner`, `prize` WHERE `user`.`user_id` = `winner`.`user_id` AND `winner`.`prize_id` = `prize`.`prize_id` ORDER BY  `winner`.`created_at` DESC LIMIT 10;";
          $stmt = $dbh->prepare($sql);
          $executed = $stmt->execute();
          $tab = $stmt->fetchAll();
        ?>
        <br>
        <hr>
        <br>
        <h2>Last Winners</h2>
        <table>
          <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Created Date</th>
            <th>Prize ID</th>
            <th>Prize Name</th>
            <th>Won Date</th>
          </tr>
          <?php
            foreach($tab as $row) :
          ?>
          <tr>
            <td><?php echo $row['user_id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['phone']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['created_at']; ?></td>
            <td><?php echo $row['prize_id']; ?></td>
            <td><?php echo $row['prize']; ?></td>
            <td><?php echo $row['won_date']; ?></td>
          </tr>
          <?php endforeach; ?>
        </table>

        <br>
        <h2>Download winners</h2>
        <form action="exportw.php" method="get" style="max-width:150px;">
          <input name="date" type="date">
          <input type="submit" value="Download">
        </form>
        <br>

        <?php
          $dbh = resetPDO($dbh);
          $sql = 'SELECT `user`.*, `comment`.`comment` AS comment, `comment`.`created_at` AS commented_date, `comment`.`comment_id` AS comment_id, `hotel`.`name_en` AS hotel FROM `user`, `comment`, `hotel` WHERE `user`.`user_id` = `comment`.`user_id` AND `comment`.`hotel_id` = `hotel`.`hotel_id` ORDER BY `comment`.`validated` DESC, `comment`.`created_at` DESC LIMIT 10;';
          $stmt = $dbh->prepare($sql);
          $executed = $stmt->execute();
          $tab = $stmt->fetchAll();
        ?>
        <br>
        <hr>
        <br>
        <h2>Last Comments</h2>
        <table>
          <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Created Date</th>
            <th>Comment ID</th>
            <th>Comment</th>
            <th>Commented Date</th>
            <th>Hotel</th>
            <th>Hide</th>
          </tr>
          <?php
            foreach($tab as $row) :
          ?>
          <tr>
            <td><?php echo $row['user_id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['phone']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['created_at']; ?></td>
            <td><?php echo $row['comment_id']; ?></td>
            <td><?php echo $row['comment']; ?></td>
            <td><?php echo $row['commented_date']; ?></td>
            <td><?php echo $row['hotel']; ?></td>
            <td>
              <form action="admin.php" method="POST" onsubmit="return confirm('Do you really want to hide this comment?');">
                <input type="hidden"  name="comment_id" value="<?php echo $row['comment_id']; ?>">
                <input type="submit" value="Hide">
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </table>

        <br>
        <h2>Download Comments</h2>
        <form action="exportc.php" method="get" style="max-width:150px;">
          <input name="date" type="date">
          <input type="submit" value="Download">
        </form>
        <br>
      </div>
    </div>
  </body>
</html>
<?php $dbh = null;?>