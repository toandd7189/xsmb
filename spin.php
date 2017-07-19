<?php
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="js/spin.js" type="text/javascript"></script>
		<link href="css/reset.css" type="text/css" rel="stylesheet" />
		<link href="css/spin.css" type="text/css" rel="stylesheet" />
		<title>Quay Thử</title>
	</head>
	<body>
		<div id="t-wrap">
			<h1 class="g-title" data-g="g1" data-n="1">Chuẩn bị quay Giải Nhất</h1>
			<div id="content" class="container-fluid">
				<div id="tool">
					<div class="row">
						<div id="t-btn" class="col-md-2">
							<div id="t-spin" class="square" style="background: none;">
								<button id="btnq" onclick="spin();"></button>
							</div>
						</div>
						<?php for ($t=4; $t>=0; $t--) : ?>
							<div id="t-<?php echo $t ?>" class="col-md-2">
								<div class="square">
									<span class="spin"></span>
								</div>
							</div>
						<?php endfor; ?>
					</div>
				</div>
				<div id="results">
					<div class="row">
						<div class="col-md-6">
							<button id="saveBtn" class="btn btn-success hide">Save</button>
							<table width="500" style="float:right;">
								<tr id="g0">
									<td width="30%">Đặc biệt</td>
									<td id="g0-1" colspan="12"></td>
								</tr>
								<tr id="g1">
									<td>Giải Nhất</td>
									<td id="g1-1" colspan="12"></td>
								</tr>
								<tr id="g2">
									<td>Giải Nhì</td>
									<td id="g2-1" colspan="6"></td>
									<td id="g2-2" colspan="6"></td>
								</tr>
								<tr id="g3-0">
									<td rowspan="2">Giải Ba</td>
									<td id="g3-1" colspan="4"></td>
									<td id="g3-2" colspan="4"></td>
									<td id="g3-3" colspan="4"></td>
								</tr>
								<tr id="g3-1">
									<td style="display:none"></td>
									<td id="g3-4" colspan="4"></td>
									<td id="g3-5" colspan="4"></td>
									<td id="g3-6" colspan="4"></td>
								</tr>
								<tr id="g4">
									<td>Giải Tư</td>
									<td id="g4-1" colspan="3"></td>
									<td id="g4-2" colspan="3"></td>
									<td id="g4-3" colspan="3"></td>
									<td id="g4-4" colspan="3"></td>
								</tr>
								<tr id="g5-0">
									<td rowspan="2">Giải Năm</td>
									<td id="g5-1" colspan="4"></td>
									<td id="g5-2" colspan="4"></td>
									<td id="g5-3" colspan="4"></td>
								</tr>
								<tr id="g5-1">
									<td style="display:none"></td>
									<td id="g5-4" colspan="4"></td>
									<td id="g5-5" colspan="4"></td>
									<td id="g5-6" colspan="4"></td>
								</tr>
								<tr id="g6">
									<td>Giải Sáu</td>
									<td id="g6-1" colspan="4"></td>
									<td id="g6-2" colspan="4"></td>
									<td id="g6-3" colspan="4"></td>
								</tr>
								<tr id="g7">
									<td>Giải Bảy</td>
									<td id="g7-1" colspan="3"></td>
									<td id="g7-2" colspan="3"></td>
									<td id="g7-3" colspan="3"></td>
									<td id="g7-4" colspan="3"></td>
								</tr>
							</table>
						</div>
						<div class="col-md-4">
							<table width="150">
								<?php for($i = 0; $i <= 9; $i++) : ?>
									<tr id="d<?php echo $i ?>">
										<td width="20%"><?php echo $i ?></td>
										<td></td>
									</tr>
								<?php endfor; ?>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div> 
	</body>
	<footer></footer>
</html>

