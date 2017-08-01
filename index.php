<?php 
	date_default_timezone_set('Asia/Jakarta');

	require_once('model.php');
	require_once('helper.php');
	
	$model = new xsModel();
	
	$today 						= time();
	$yesterday 					= $today - 24*3600;
	$day						= date('l', $yesterday);
	$month						= date('F', $yesterday);
	$year						= date('Y', $yesterday);
	
	$resultOfYesterday			= $model->getResultsDay(date('d-m-Y', $yesterday), $month, $year);
	$xsmb 						= !empty($resultOfYesterday->xsmb)? $resultOfYesterday->xsmb : array();
	$loto						= [];
	
	if (!empty($resultOfYesterday->xsmb)) {
		for ($i=0;$i<=9;$i++) {
			$loto[$i] = [];
			foreach ($resultOfYesterday->loto as $lo) {
				if (substr($lo, 0,1) == $i) {
					$loto[$i][] = $lo;
				}
			}
		}
	}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head>
		<link rel="shortcut icon" type="image/png" href="images/favicon.jpg"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js" type="text/javascript"></script>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
		<script src="js/script.js" type="text/javascript"></script>
		<link href="css/reset.css" type="text/css" rel="stylesheet" />
		<link href="css/style.css" type="text/css" rel="stylesheet" />
		
		<title>Thánh Lô</title>
	</head>
	<body>
		<div id="wrapper">
			<div id="content">
				<div class="update">
					<button id="updatedb">Update</button>
					<div class="loading">
						<img src="images/loading.gif" />
					</div>
					<span class="update_message"></span>
				</div>
				<div class="title">
					<span class="yesterday"><b>Yesterday</b> : <i style="color:blue;"><?php echo date('l', $yesterday) ?>, <?php echo date('d-m-Y',$yesterday) ?></i></span>
				</div>
				<div class="container">
					<div class="row">
						<div class="col-xs-6">
							<div class="rs_yesterday">
								<?php if (!empty($xsmb)) : ?>
									<table width="60%">
										<?php foreach ($xsmb as $g => $rows) : ?>
											<?php if (count($rows) != 6) : ?>
												<tr>
													<td><?php echo $g; ?></td>
													<?php foreach ($rows as $r) : ?>
														<td colspan="<?php echo 12 / intval(count($rows)) ?>"><?php if ($g == 'Đặc biệt') echo '<b style="color: red;">'?><?php echo $r ?><?php if ($g == 'Đặc biệt') echo '</b>'; ?></td>
													<?php endforeach; ?>
												</tr>
											<?php else : ?>
												<tr>
													<td rowspan="2"><?php echo $g; ?></td>
													<?php for($k=0; $k<=2; $k++) : ?>
														<td colspan="<?php echo 12 / intval(count($rows)/2) ?>"><?php echo $rows[$k] ?></td>
													<?php endfor; ?>
												</tr>
												<tr>
													<td style="display:none;"> </td>
													<?php for($h=3; $h<=5; $h++) : ?>
														<td colspan="<?php echo 12 / intval(count($rows)/2) ?>"><?php echo $rows[$h] ?></td>
													<?php endfor; ?>
												</tr>
											<?php endif; ?>
										<?php endforeach; ?>
									</table>
									<table width="25%">
										<?php foreach ($loto as $j => $ns) : ?>
											<tr>
												<td><?php echo $j ?></td>
												<td style="text-align: left;">
													<?php echo implode(' , ', $ns); ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</table>
								<?php else : ?>
									<h4>No results, try update.</h4>
								<?php endif; ?>
							</div>
							<div class="act">
								<label>Nhập ngày<label>
								<input class="datepicker" name="date" data-date-format="mm/dd/yyyy">
								<button id="getresult">Xem kết quả</button>
								<div class="loading">
									<img src="images/loading.gif" />
								</div>
							</div>
							<div id="date_results" class="rs_yesterday"></div>
						</div>
						<div class="actions col-xs-6">
							<h5 class="note">Note: You should update data by click on the update button before find the best numbers!</h5>
							<div class="act">
								<h4>Best number today by the data from 2003 to now.</h4>
								<button id="get_best_number" class="btn btn-success">Find the best numbers</button>
								<div class="loading">
									<img src="images/loading.gif" />
								</div>
								<div id="best_number_17" class="best_number"></div>
							</div>
							<div class="act">
								<h4>Best number today by the data from 2003 to 2016.</h4>
								<button id="get_best_number_bf_2017" class="btn btn-success">Find the best numbers</button>
								<div class="loading">
									<img src="images/loading.gif" />
								</div>
								<div id="best_number_bf_17" class="best_number"></div>
							</div>
							<div class="act">
								<h4>Best number today by the special number in yesterday.</h4>
								<button id="get_best_number_by_sp_ys" class="btn btn-success">Find the best numbers</button>
								<div class="loading">
									<img src="images/loading.gif" />
								</div>
								<div id="best_number_by_sp_ys" class="best_number"></div>
							</div>
							<div class="act">
								<h4>Best special today.</h4>
								<button id="get_best_special" class="btn btn-danger">Find the best special</button>
								<div class="loading">
									<img src="images/loading.gif" />
								</div>
								<div id="best_special" class="best_number"></div>
							</div>
							<div class="act">
								<h4>Best special today by the First and Last letter.</h4>
								<button id="get_best_special_fl" class="btn btn-danger">Find the best special</button>
								<div class="loading">
									<img src="images/loading.gif" />
								</div>
								<div id="best_special_fl" class="best_number"></div>
							</div>
							<div class="act">
								<h4>Try spin same the way of XSMB.</h4>
								<button id="get_try" class="btn btn-info">Quay thử</button>
								<div id="get_try_results"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
	<footer></footer>
</html>