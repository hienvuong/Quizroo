<?php require_once('Connections/kuizzroo.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}


// find out values

// Quiz Information
$quiz_title = $_POST['quiz_title'];
$quiz_description = $_POST['quiz_description'];
$quiz_cat = $_POST['quiz_cat'];
$quiz_picture = ($_POST['quiz_picture'] != "") ? $_POST['quiz_picture'] : "none.gif";
$quiz_member_id = $_POST['quiz_member_id'];

// Quiz Results
for($i = 0; $i < $_POST['resultCount']; $i++){
	$result_title[] = $_POST['result_title_'.($i+1)];
	$result_description[] = $_POST['result_description_'.($i+1)];
	$result_picture[] = ($_POST['result_picture_'.($i+1)] != "") ? $_POST['result_picture_'.($i+1)] : "none.gif";
}
// Quiz Questions
$questionArray = explode("_", $_POST['optionCounts']);
$question = array();
for($i = 0; $i < $_POST['questionCount']; $i++){
	$question[][0] = $_POST['question_'.($i+1)];
	// Quiz Options
	for($j = 0; $j < $questionArray[$i]; $j++){
		$question[$i][1][$j][] = $_POST['q'.($i+1).'o'.($j+1)];
		$question[$i][1][$j][] = $_POST['q'.($i+1).'r'.($j+1)];
		$question[$i][1][$j][] = $_POST['q'.($i+1).'w'.($j+1)];
	}
}

$result_convertor = array();

/*
// debug
echo $quiz_title."<br>".$quiz_description."<br>".$quiz_cat."<br>".$quiz_picture;
echo "<br><br>";
print_r($result_title);
echo "<br>";
print_r($result_description);
echo "<br>";
print_r($result_picture);
echo "<br><br>";
print_r($question);
*/

mysql_select_db($database_kuizzroo, $kuizzroo);
// insert into the quiz table
$insertSQL = sprintf("INSERT INTO qb_quizzes(`quiz_name`, `quiz_description`, `fk_quiz_cat`, `quiz_picture`, `fk_member_id`) VALUES (%s, %s, %d, %s, %d)",
				   GetSQLValueString($quiz_title, "text"),
				   GetSQLValueString($quiz_description, "text"),
				   GetSQLValueString($quiz_cat, "int"),
				   GetSQLValueString($quiz_picture, "text"),
				   GetSQLValueString($quiz_member_id, "int"));
//echo "<br>".$insertSQL;
mysql_query($insertSQL, $kuizzroo) or die(mysql_error());

// find the quiz id
$querySQL = "SELECT LAST_INSERT_ID() AS insertID";
$resultID = mysql_query($querySQL, $kuizzroo) or die(mysql_error());
$row_resultID = mysql_fetch_assoc($resultID);
$currentQuizID = $row_resultID['insertID'];
mysql_free_result($resultID);

// Insert the results
$insertSQL = "INSERT INTO qb_results(`result_title`, `result_description`, `result_picture`, `fk_quiz_id`) VALUES ";
for($i = 0; $i < $_POST['resultCount']; $i++){
	$insertSQL .= sprintf(" (%s, %s, %s, %d),",
				   GetSQLValueString($result_title[$i], "text"),
				   GetSQLValueString($result_description[$i], "text"),
				   GetSQLValueString($result_picture[$i], "text"),
				   GetSQLValueString($currentQuizID, "int"));
}
//echo "<br>".$insertSQL;
mysql_query(substr($insertSQL, 0, strlen($insertSQL)-1), $kuizzroo) or die(mysql_error());
// find the result id
$querySQL = "SELECT LAST_INSERT_ID() AS insertID";
$resultID = mysql_query($querySQL, $kuizzroo) or die(mysql_error());
$row_resultID = mysql_fetch_assoc($resultID);
$lastResultID = $row_resultID['insertID'];
mysql_free_result($resultID);


// Insert the questions
for($i = 0; $i < $_POST['questionCount']; $i++){
	$insertSQL = sprintf("INSERT INTO qb_questions(`question`, `fk_quiz_id`) VALUES (%s, %d)",
				   GetSQLValueString($question[$i][0], "text"),
				   GetSQLValueString($currentQuizID, "int"));
	//echo "<br>".$insertSQL;
	mysql_query($insertSQL, $kuizzroo) or die(mysql_error());

	// find the question id
	$querySQL = "SELECT LAST_INSERT_ID() AS insertID";
	$resultID = mysql_query($querySQL, $kuizzroo) or die(mysql_error());
	$row_resultID = mysql_fetch_assoc($resultID);
	$currentQuestionID = $row_resultID['insertID'];
	mysql_free_result($resultID);

	// Insert the Quiz Options
	for($j = 0; $j < $questionArray[$i]; $j++){
		$resultValue = $lastResultID + $question[$i][1][$j][1] - 1;
		$insertSQL = sprintf("INSERT INTO qb_options(`option`, `fk_result`, `option_weightage`, `fk_question_id`) VALUES (%s, %d, %d, %d)",
					   GetSQLValueString($question[$i][1][$j][0], "text"),
					   GetSQLValueString($resultValue, "int"),
					   GetSQLValueString($question[$i][1][$j][2], "int"),
					   GetSQLValueString($currentQuestionID, "int"));
		//echo "<br>".$insertSQL;
		mysql_query($insertSQL, $kuizzroo) or die(mysql_error());
	}
}

$insertGoTo = "createQuizSuc.php?id=".$currentQuizID;
header(sprintf("Location: %s", $insertGoTo));
?>