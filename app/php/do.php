<?php

//isXMLHTTPRequest() 	or die('Forbidden');
isset($_GET['r'])	or die('Forbidden');
//isValidToken()		or die('CSRF Attack detected');

session_start();

function resetPDO($dbh) {
  include("db.php");
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
$dbh = resetPDO($dbh);

$method = $_GET['r'];

switch ($method) {
	case 'selectUser':
		echo selectUser();
	  break;
	case 'selectLastShare':
	  echo selectLastShare();
	  break;
	case 'selectMyPrize':
	  echo selectMyPrize();
	  break;
	case 'selectCredits':
		echo selectCredits();
	  break;
	case 'selectHotels':
		echo selectHotels();
	  break;
	case 'selectComments':
		echo selectComments();
	  break;
	case 'addComment':
		echo addComment();
	  break;
	case 'addUser':
		echo addUser();
	  break;
	case 'updateShareEvent1':
		echo updateShareEvent1();
	  break;
	case 'updateShareEvent2':
		echo updateShareEvent2();
	  break;
	case 'play':
		echo play();
	  break;
	default:
		$error = array("error" =>  "Undefined function.");
    echo json_encode($error);
	  break;
}

$dbh = null;

function isXMLHTTPRequest() {
	if(!sizeError($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		return true;
	} else {
		return false;
	}
}

function isValidToken() {
	//TODO improve security with a real token
	$token = getallheaders();
	$token = $token['X-CSRF-Token'];
	if(!sizeError($token) && $token == 'SylvainIsTheBest') {
		return true;
	} else {
		return false;
	}
}

function isValidHash($hash) {
  if ($hash != "ETzS7Juimc05VdUXB95fXV2aLYWDrAeW6PIOYRYB") {
    return false;
  } else {
    return true;
  }
}

function getClientIp() {
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'NA';
    return $ipaddress;
}

function selectUser() {
	$data = file_get_contents("php://input");
	$objData = json_decode($data);

	if(!isset($objData->data->hash)) {
  	$error = array("error" =>  "No hash value.");
    return json_encode($error);
  }
	if(!isset($objData->data->user)) {
  	$error = array("error" =>  "No user value.");
    return json_encode($error);
  }
	if (!isValidHash($objData->data->hash)) {
    $error = array("error" =>  "Incorrect hash value.");
    return json_encode($error);
  }

  if(strlen($objData->data->user->email) == 0 or strlen($objData->data->user->email) > 50 ) {
    $error = array("error" =>  "sizeErrorEmail");
    return json_encode($error);
  }
  if(strlen($objData->data->user->password) == 0 or strlen($objData->data->user->password) > 50 ) {
    $error = array("error" =>  "sizeErrorPassword");
    return json_enscode($error);
  }

  $dbh = $GLOBALS['dbh'];

  $sql = "SELECT COUNT(`user_id`) AS count FROM `user` WHERE `email` LIKE :email AND `password` LIKE :password;";
  $stmt = $dbh->prepare($sql);
  $unsafeEmail = htmlspecialchars($objData->data->user->email);
  $unsafePassword = md5(htmlspecialchars($objData->data->user->password));
  $executed = $stmt->execute(array(':email' => $unsafeEmail, ':password' => $unsafePassword));
  if ($executed) {
    $obj = $stmt->fetch(PDO::FETCH_LAZY);
    if ($obj->count == 0) {
      $error = array("error" =>  "noUser");
      return json_encode($error);
    }
  }else {
    $error = array("error" =>  "SELECT count user query1 error.");
    return json_encode($error);
  }

  $dbh = resetPDO($dbh);

  $sql = "SELECT `user_id` as id, name FROM `user` WHERE `email` LIKE :email AND `password` LIKE :password;";
  $stmt = $dbh->prepare($sql);
  $executed = $stmt->execute(array(':email' => $unsafeEmail, ':password' => $unsafePassword));
  if ($executed) {
    $obj = $stmt->fetch(PDO::FETCH_LAZY);
    return json_encode(array("id" =>  $obj->id, "name" =>  $obj->name));
  }else {
    $error = array("error" =>  "SELECT user query1 error.");
    return json_encode($error);
  }
}

function selectLastShare() {
	$data = file_get_contents("php://input");
	$objData = json_decode($data);

	if(!isset($objData->data->hash)) {
  	$error = array("error" =>  "No hash value.");
    return json_encode($error);
  }
	if(!isset($objData->data->user)) {
  	$error = array("error" =>  "No user value.");
    return json_encode($error);
  }
	if (!isValidHash($objData->data->hash)) {
    $error = array("error" =>  "Incorrect hash value.");
    return json_encode($error);
  }

  $dbh = $GLOBALS['dbh'];

  $sql = "SELECT `last_share` = CURDATE() AS already FROM `user` WHERE `user`.`user_id` = :id;";
  $stmt = $dbh->prepare($sql);
  $unsafeUserId = intval($objData->data->user->id);
  $executed = $stmt->execute(array(':id' => $unsafeUserId));
  if ($executed) {
    if ($stmt->columnCount() > 0) {
      $obj = $stmt->fetch(PDO::FETCH_LAZY);
      return $obj->already;
    }else {
      $error = array("error" =>  "noUser");
      return json_encode($error);
    }
  }else {
    $error = array("error" =>  "SELECT last share query error.");
    return json_encode($error);
  }
}

function selectMyPrize() {
	$data = file_get_contents("php://input");
	$objData = json_decode($data);

	if(!isset($objData->data->hash)) {
  	$error = array("error" =>  "No hash value.");
    return json_encode($error);
  }
	if(!isset($objData->data->user)) {
  	$error = array("error" =>  "No user value.");
    return json_encode($error);
  }
	if (!isValidHash($objData->data->hash)) {
    $error = array("error" =>  "Incorrect hash value.");
    return json_encode($error);
  }

  $dbh = $GLOBALS['dbh'];

  $sql = "SELECT COUNT(`prize`.`prize_id`) AS count FROM `winner`, `prize` WHERE displayed = 0 AND `winner`.`user_id` = :id AND `winner`.`prize_id` = `prize`.`prize_id`;";
  $unsafeUserId = intval($objData->data->user->id);
  $stmt = $dbh->prepare($sql);
  $executed = $stmt->execute(array(':id' =>  $unsafeUserId));
  if ($executed) {
    $obj = $stmt->fetch(PDO::FETCH_LAZY);
    if ($obj->count == 0) {
      $error = array("error" =>  "noPrize");
      return json_encode($error);
    }
  }else {
    $error = array("error" =>  "SELECT count myprize query error.");
    return json_encode($error);
  }

  $dbh = resetPDO($dbh);

  $sql = "SELECT `prize`.`prize_id` AS id FROM `winner`, `prize` WHERE displayed = 0 AND `winner`.`user_id` = :id AND `winner`.`prize_id` = `prize`.`prize_id` ORDER BY `winner`.`created_at` LIMIT 1;";
  $stmt = $dbh->prepare($sql);
  $executed = $stmt->execute(array(':id' =>  $unsafeUserId));
  if ($executed) {
    $obj = $stmt->fetch(PDO::FETCH_LAZY);
    $dbh = resetPDO($dbh);
    $sql = "UPDATE `winner` SET `displayed` = 1 WHERE `user_id` = :id AND `prize_id` = :prizeId;";
    $stmt = $dbh->prepare($sql);
    $executed = $stmt->execute(array(':id' =>  $unsafeUserId, ':prizeId' =>  $obj->id));
    if ($executed) {
      return $obj->id;
    }else {
      $error = array("error" =>  "UPDATE displayed error.");
      return json_encode($error);
    }
  }else {
    $error = array("error" =>  "SELECT myprize error.");
    return json_encode($error);
  }
}

function selectHotels() {
	$data = file_get_contents("php://input");
	$objData = json_decode($data);

	if(!isset($objData->data->hash)) {
  	$error = array("error" =>  "No hash value.");
    return json_encode($error);
  }
	if (!isValidHash($objData->data->hash)) {
    $error = array("error" =>  "Incorrect hash value.");
    return json_encode($error);
  }

  $dbh = $GLOBALS['dbh'];

  $sql = "SELECT COUNT(*) AS count FROM `hotel`;";
  $stmt = $dbh->prepare($sql);
  $executed = $stmt->execute();
  if ($executed) {
    $obj = $stmt->fetch(PDO::FETCH_LAZY);
    if ($obj->count == 0) {
      $error = array("error" =>  "noHotel");
      return json_encode($error);
    }
  }else {
    $error = array("error" =>  "SELECT count hotels query error.");
    return json_encode($error);
  }

  $dbh = resetPDO($dbh);

  $sql = "SELECT * FROM `hotel` ORDER BY RAND();";
  $stmt = $dbh->prepare($sql);
  $executed = $stmt->execute();
  if ($executed) {
    $result = $stmt->fetchAll();
    return json_encode($result);
  }else {
    $error = array("error" =>  "SELECT hotels query error.");
    return json_encode($error);
  }
}

function selectComments() {
	$data = file_get_contents("php://input");
	$objData = json_decode($data);

	if(!isset($objData->data->hash)) {
  	$error = array("error" =>  "No hash value.");
    return json_encode($error);
  }
	if (!isValidHash($objData->data->hash)) {
    $error = array("error" =>  "Incorrect hash value.");
    return json_encode($error);
  }

  $dbh = $GLOBALS['dbh'];

  $sql = "SELECT COUNT(`comment`.comment_id) AS count FROM `comment`, `user`, `hotel` WHERE `comment`.user_id = `user`.user_id AND `comment`.hotel_id = `hotel`.hotel_id AND `comment`.validated = 1;";
  $stmt = $dbh->prepare($sql);
  $executed = $stmt->execute();
  if ($executed) {
    $obj = $stmt->fetch(PDO::FETCH_LAZY);
    if ($obj->count == 0) {
      $error = array("error" =>  "noComment");
      return json_encode($error);
    }
  }else {
    $error = array("error" =>  "SELECT count comments query error.");
    return json_encode($error);
  }

  $dbh = resetPDO($dbh);

  $sql = "SELECT `comment`.comment_id, `user`.name, `hotel`.name_kr, `comment`.comment, DATE_FORMAT(`comment`.created_at, '%m-%d-%Y') AS created_at FROM `comment`, `user`, `hotel` WHERE `comment`.user_id = `user`.user_id AND `comment`.hotel_id = `hotel`.hotel_id AND `comment`.validated = 1 ORDER BY `comment`.`created_at` DESC LIMIT 10;";
  $stmt = $dbh->prepare($sql);
  $executed = $stmt->execute();
  if ($executed) {
    $result = $stmt->fetchAll();
    return json_encode($result);
  }else {
    $error = array("error" =>  "SELECT comments query error.");
    return json_encode($error);
  }
}

function selectCredits() {
	$data = file_get_contents("php://input");
	$objData = json_decode($data);

	if(!isset($objData->data->hash)) {
  	$error = array("error" =>  "No hash value.");
    return json_encode($error);
  }
	if(!isset($objData->data->user)) {
  	$error = array("error" =>  "No user value.");
    return json_encode($error);
  }
	if (!isValidHash($objData->data->hash)) {
    $error = array("error" =>  "Incorrect hash value.");
    return json_encode($error);
  }

  $dbh = $GLOBALS['dbh'];

  $sql = "SELECT `credits`, CURDATE() - `last_play` AS days FROM `user` WHERE `user_id` = :id;";
  $stmt = $dbh->prepare($sql);
  $unsafeUserId = intval($objData->data->user->id);
  $executed = $stmt->execute(array(':id' =>  $unsafeUserId));
  if ($executed) {
    if ($stmt->columnCount() > 0) {
      $obj = $stmt->fetch(PDO::FETCH_LAZY);
      if (intval($obj->days) >= 1 || intval($obj->credits) > 3) {
        $dbh = resetPDO($dbh);
        $sql = "UPDATE `user` SET `credits` = 3 WHERE `user_id` = :id;";
        $stmt = $dbh->prepare($sql);
        $executed = $stmt->execute(array(':id' =>  $unsafeUserId));
        if ($executed) {
          return 3;
        }else {
          $error = array("error" =>  "UPDATE credits3 error.");
          return json_encode($error);
        }
      }else {
        return intval($obj->credits);
      }
    }else {
      $error = array("error" =>  "noUser");
      return json_encode($error);
    }
  }else {
    $error = array("error" =>  "SELECT last_day query error.");
    return json_encode($error);
  }
}

function addComment() {
	$data = file_get_contents("php://input");
	$objData = json_decode($data);

	if(!isset($objData->data->hash)) {
  	$error = array("error" =>  "No hash value.");
    return json_encode($error);
  }
	if(!isset($objData->data->comment)) {
  	$error = array("error" =>  "No comment value.");
    return json_encode($error);
  }
	if (!isValidHash($objData->data->hash)) {
    $error = array("error" =>  "Incorrect hash value.");
    return json_encode($error);
  }

  if(mb_strlen($objData->data->comment->comment,'UTF-8') == 0 or mb_strlen($objData->data->comment->comment,'UTF-8')>50){
    $error = array("error" =>  "sizeErrorComment");
    return json_encode($error);
  }

  $dbh = $GLOBALS['dbh'];

  $sql = "INSERT INTO `comment`(`user_id`, `hotel_id`, `comment`)  VALUES (:userId, :hotelId, :comment);";
  $unsafeUserId = intval($objData->data->comment->userId);
  $unsafeHotelId = intval($objData->data->comment->hotelId);
  $unsafeComment = str_replace('&quot;','"',htmlspecialchars($objData->data->comment->comment));
  $stmt = $dbh->prepare($sql);
  $executed = $stmt->execute(array(':userId' => $unsafeUserId, ':hotelId' => $unsafeHotelId, ':comment' => $unsafeComment));
  if ($executed) {
    $id = $dbh->lastInsertId();
    return $id;
  }else {
    $error = array("error" =>  "INSERT comment query error.");
    return json_encode($error);
  }
}

function addUser() {
	$data = file_get_contents("php://input");
	$objData = json_decode($data);

	if(!isset($objData->data->hash)) {
  	$error = array("error" =>  "No hash value.");
    return json_encode($error);
  }
	if(!isset($objData->data->user)) {
  	$error = array("error" =>  "No user value.");
    return json_encode($error);
  }
	if (!isValidHash($objData->data->hash)) {
    $error = array("error" =>  "Incorrect hash value.");
    return json_encode($error);
  }

  if(mb_strlen($objData->data->user->name,'UTF-8') == 0 or mb_strlen($objData->data->user->name,'UTF-8') > 50){
    $error = array("error" =>  "sizeErrorName");
    return json_encode($error);
  }
  if(mb_strlen($objData->data->user->password,'UTF-8') == 0 or mb_strlen($objData->data->user->password,'UTF-8') > 50 ){
    $error = array("error" =>  "sizeErrorPassword");
    return json_encode($error);
  }
  if(strlen($objData->data->user->email) == 0 or strlen($objData->data->user->email) > 50 ){
    $error = array("error" =>  "sizeErrorEmail");
    return json_encode($error);
  }

  $phone = preg_replace('/[^0-9]+/', '', $objData->data->user->phone);
  if(strlen($phone) < 10 or strlen($phone) > 13 ){
    $error = array("error" =>  "sizeErrorPhone");
    return json_encode($error);
  }

  $dbh = $GLOBALS['dbh'];
  $id = -1;
  $alreadyPhone = false;
  $alreadyEmail = false;

  $sql = "SELECT COUNT(`user_id`) AS count FROM `user` WHERE `phone` LIKE :phone;";
  $stmt = $dbh->prepare($sql);
  $executed = $stmt->execute(array(':phone' => $phone));
  if ($executed) {
    $obj = $stmt->fetch(PDO::FETCH_LAZY);
    if ($obj->count > 0) {
      $alreadyPhone = true;
      $error = array("error" =>  "alreadyPhone");
      return json_encode($error);
    }
  }else {
    $error = array("error" =>  "SELECT user query1 error.");
    return json_encode($error);
  }

  $dbh = resetPDO($dbh);

  $sql = "SELECT COUNT(`user_id`) AS count FROM `user` WHERE `email` LIKE :email;";
  $unsafeEmail = htmlspecialchars($objData->data->user->email);
  $stmt = $dbh->prepare($sql);
  $executed = $stmt->execute(array(':email' => $unsafeEmail));
  if ($executed) {
    $obj = $stmt->fetch(PDO::FETCH_LAZY);
    if ($obj->count > 0) {
      $alreadyEmail = true;
      $error = array("error" =>  "alreadyEmail");
      return json_encode($error);
    }
  }else {
    $error = array("error" =>  "SELECT user query2 error.");
    return json_encode($error);
  }

  if(!$alreadyMobile and !$alreadyEmail) {
    $dbh = resetPDO($dbh);
    $sql = "INSERT INTO `user`(`name`, `phone`, `email`, `password`, `ip`)  VALUES (:name, :phone, :email, :password, :ip);";
    $unsafeName = htmlspecialchars($objData->data->user->name);
    $unsafePassword = md5(htmlspecialchars($objData->data->user->password));
    $stmt = $dbh->prepare($sql);
    $executed = $stmt->execute(array(':name' => $unsafeName, ':phone' => $phone , ':email' => $unsafeEmail, ':password' => $unsafePassword, ':ip' => getClientIp()));
    if ($executed) {
      $id = $dbh->lastInsertId();
      return $id;
    }else {
      $error = array("error" =>  "INSERT user query error.");
      return json_encode($error);
    }
  }
}

function updateShareEvent1() {
	$data = file_get_contents("php://input");
	$objData = json_decode($data);

	if(!isset($objData->data->hash)) {
  	$error = array("error" =>  "No hash value.");
    return json_encode($error);
  }
	if(!isset($objData->data->user)) {
  	$error = array("error" =>  "No user value.");
    return json_encode($error);
  }
	if (!isValidHash($objData->data->hash)) {
    $error = array("error" =>  "Incorrect hash value.");
    return json_encode($error);
  }

  $dbh = $GLOBALS['dbh'];

  $bonus = 0;

  $sql = "SELECT CURDATE() - `last_share` AS days FROM `user` WHERE `user_id` = :id;";
  $stmt = $dbh->prepare($sql);
  $unsafeUserId = intval($objData->data->user->id);
  $executed = $stmt->execute(array(':id' =>  $unsafeUserId));
  if ($executed) {
    if ($stmt->columnCount() > 0) {
      $obj = $stmt->fetch(PDO::FETCH_LAZY);
      if (intval($obj->days) >= 1) {
        $bonus = 3;
      }
    }else {
      $error = array("error" =>  "noUser");
      return json_encode($error);
    }
  }else {
    $error = array("error" =>  "SELECT last_share query error.");
    return json_encode($error);
  }

  $dbh = resetPDO($dbh);

  $sql = "UPDATE `user` SET `share` = `share`+1, `last_share` = CURDATE(), `credits` = `credits`+".$bonus." WHERE `user_id` = :id;";
  $stmt = $dbh->prepare($sql);
  $executed = $stmt->execute(array(':id' =>  $unsafeUserId));
  if ($executed) {
    return $unsafeUserId;
  }else {
    $error = array("error" =>  "UPDATE share event1 error.");
    return json_encode($error);
  }
}

function updateShareEvent2() {
	$data = file_get_contents("php://input");
	$objData = json_decode($data);

	if(!isset($objData->data->hash)) {
  	$error = array("error" =>  "No hash value.");
    return json_encode($error);
  }
	if(!isset($objData->data->user)) {
  	$error = array("error" =>  "No user value.");
    return json_encode($error);
  }
	if (!isValidHash($objData->data->hash)) {
    $error = array("error" =>  "Incorrect hash value.");
    return json_encode($error);
  }

  $dbh = $GLOBALS['dbh'];

  $sql = "UPDATE `user` SET `share` = `share`+1 WHERE `user_id` = :id;";
  $stmt = $dbh->prepare($sql);
  $unsafeUserId = intval($objData->data->user->id);
  $executed = $stmt->execute(array(':id' =>  $unsafeUserId));
  if ($executed) {
    return $unsafeUserId;
  }else {
    $error = array("error" =>  "UPDATE share event2 error.");
    return json_encode($error);
  }
}

function play() {
	$data = file_get_contents("php://input");
	$objData = json_decode($data);

	if(!isset($objData->data->hash)) {
  	$error = array("error" =>  "No hash value.");
    return json_encode($error);
  }
	if(!isset($objData->data->user)) {
  	$error = array("error" =>  "No user value.");
    return json_encode($error);
  }
	if (!isValidHash($objData->data->hash)) {
    $error = array("error" =>  "Incorrect hash value.");
    return json_encode($error);
  }

  $dbh = $GLOBALS['dbh'];

  $sql = "SELECT `credits` FROM `user` WHERE `user_id` = :id;";
  $stmt = $dbh->prepare($sql);
  $unsafeUserId = intval($objData->data->user->id);
  $executed = $stmt->execute(array(':id' =>  $unsafeUserId));
  if ($executed) {
    if ($stmt->columnCount() > 0) {
      $obj = $stmt->fetch(PDO::FETCH_LAZY);
      if (intval($obj->credits) > 0) {
        $dbh = resetPDO($dbh);
        $sql = "UPDATE `user` SET `credits` = `credits`-1, `last_play` = CURDATE() WHERE `user_id` = :id;";
        $stmt = $dbh->prepare($sql);
        $executed = $stmt->execute(array(':id' =>  $unsafeUserId));
        if ($executed) {
          $dbh = resetPDO($dbh);
          $sql = "SELECT `prize_id`, `name`, `quantity` FROM `prize` ORDER BY `prize_id`;";
          $stmt = $dbh->prepare($sql);
          $executed = $stmt->execute();
          if ($executed) {
            if ($stmt->columnCount() > 0) {
              $prizes = $stmt->fetchAll();
              $prize = 999;
              $rand = rand(1, 500);
              switch ($rand) {
                case ($rand == 1 && intval($prizes[0]['quantity'])>0):
                  $prize = intval($prizes[0]['prize_id']);
                  break;
                case ($rand == 2 && intval($prizes[1]['quantity'])>0):
                  $prize = intval($prizes[1]['prize_id']);
                  break;
                case ($rand == 3 && intval($prizes[2]['quantity'])>0):
                  $prize = intval($prizes[2]['prize_id']);
                  break;
                case ($rand == 4 && intval($prizes[3]['quantity'])>0):
                  $prize = intval($prizes[3]['prize_id']);
                  break;
                case ($rand == 5 && intval($prizes[4]['quantity'])>0):
                  $prize = intval($prizes[4]['prize_id']);
                  break;
                case ($rand == 6 && intval($prizes[5]['quantity'])>0) :
                  $prize = intval($prizes[5]['prize_id']);
                  break;
              }
              if($prize == 999 && intval($obj->credits) == 1 && intval($prizes[6]['quantity'])>0) {
                $prize = 7;
                $dbh = resetPDO($dbh);
                $sql = "SELECT COUNT(`user_id`) as total FROM `winner` WHERE `user_id` = :id AND (`prize_id` = 7 OR (DAY(`created_at`) = DAY(CURDATE()) AND MONTH(`created_at`) = MONTH(CURDATE()) AND YEAR(`created_at`) = YEAR(CURDATE())));";
                $stmt = $dbh->prepare($sql);
                $unsafeUserId = intval($objData->data->user->id);
                $executed = $stmt->execute(array(':id' =>  $unsafeUserId));
                if ($executed) {
                  $obj = $stmt->fetch(PDO::FETCH_LAZY);
                  if (intval($obj->total) == 0) {
                    $dbh = resetPDO($dbh);
                    $sql = "UPDATE `prize` SET `quantity` = `quantity`-1 WHERE `prize_id` = :prize_id;";
                    $stmt = $dbh->prepare($sql);
                    $executed = $stmt->execute(array(':prize_id' => $prize));
                    if ($executed) {
                      $dbh = resetPDO($dbh);
                      $sql = "INSERT INTO `winner`(`user_id`, `prize_id`) VALUES (:id, :prize);";
                      $stmt = $dbh->prepare($sql);
                      $executed = $stmt->execute(array(':id' => $unsafeUserId, ':prize' => $prize));
                      if ($executed) {
                        return $prize;
                      }else {
                        $error = array("error" =>  "INSERT winner7 query error.");
                        return json_encode($error);
                      }
                    }else {
                      $error = array("error" =>  "UPDATE quantity7 error.");
                      return json_encode($error);
                    }
                  }else {
                    return 999;
                  }
                }else {
                  $error = array("error" =>  "SELECT user prize7 error.");
                  return json_encode($error);
                }
              }else if($prize != 999) {
                $dbh = resetPDO($dbh);
                $sql = "SELECT COUNT(`user_id`) as total FROM `winner` WHERE `user_id` = :id AND `prize_id` != 7;";
                $stmt = $dbh->prepare($sql);
                $unsafeUserId = intval($objData->data->user->id);
                $executed = $stmt->execute(array(':id' =>  $unsafeUserId));
                if ($executed) {
                  $obj = $stmt->fetch(PDO::FETCH_LAZY);
                  if (intval($obj->total) == 0) {
                    $dbh = resetPDO($dbh);
                    $sql = "UPDATE `prize` SET `quantity` = `quantity`-1 WHERE `prize_id` = :prize_id;";
                    $stmt = $dbh->prepare($sql);
                    $executed = $stmt->execute(array(':prize_id' => $prize));
                    if ($executed) {
                      $dbh = resetPDO($dbh);
                      $sql = "INSERT INTO `winner`(`user_id`, `prize_id`) VALUES (:id, :prize);";
                      $stmt = $dbh->prepare($sql);
                      $executed = $stmt->execute(array(':id' => $unsafeUserId, ':prize' => $prize));
                      if ($executed) {
                        return $prize;
                      }else {
                        $error = array("error" =>  "INSERT winner query error.");
                        return json_encode($error);
                      }
                    }else {
                      $error = array("error" =>  "UPDATE quantity error.");
                      return json_encode($error);
                    }
                  }else {
                    return 999;
                  }
                }else {
                  $error = array("error" =>  "SELECT user prize error.");
                  return json_encode($error);
                }
              }else {
                return $prize;
              }
            }else {
              $error = array("error" =>  "noPrizes");
              return json_encode($error);
            }
          }else {
            $error = array("error" =>  "SELECT prizes error.");
            return json_encode($error);
          }
        }else {
          $error = array("error" =>  "UPDATE credits error.");
          return json_encode($error);
        }
      }else {
        $error = array("error" =>  "noCredits");
        return json_encode($error);
      }
    }else {
      $error = array("error" =>  "noUser");
      return json_encode($error);
    }
  }else {
    $error = array("error" =>  "SELECT credits query error.");
    return json_encode($error);
  }
}
?>