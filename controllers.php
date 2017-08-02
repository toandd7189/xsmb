<?php
include ('lib/simple_html_dom.php');
set_time_limit(0);

require_once('helper.php');
require_once('model.php');

$date = isset($_POST['date']) ? $_POST['date'] : date('d-m-Y', time());
$dayofweek = date('l', strtotime($date));
$year = date('Y', time());

$action = $_POST['action'];
$model = new xsModel();
$number = array();

switch ($action) {
	case 'getResults' :	
		$month = date('F', strtotime($date));
		$y = date('Y', strtotime($date));
		$results = $model->getResultsDay($date, $month, $y);
		if (empty($results))
			echo 'It hasn\'t got results of '. $date;
		else 
			echo xsHelper::render($results, $date);
		break;
	case 'getBestNumber' :
		$number = $model->getBestNumber($dayofweek, true, $year);
		break;
	case 'getBestNumberBefore2017' :
		$number = $model->getBestNumber($dayofweek, true);
		break;
	case 'getBestNumberBySpecial':
		$number = $model->getNumbersBySpecialYesterday($date);
		break;
	case 'getNumberOfDate' :
		$number = $model->getNumberOfDate($date);
		break;
	case 'getBestSpecial' :
		$number = $model->getBestSpecial($date);
		break;
	case 'getBestSpecial_fl' :
		$number = $model->getBestSpecialFL($date);
		break;
	case 'update':
		xsHelper::updateDb($year);
		break;
	default:
		$number = array();
		break;
}
if (!empty($number))
	echo json_encode($number);