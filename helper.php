<?php
date_default_timezone_set('Asia/Jakarta');

class xsHelper 
{
	public static function updateDb($year) {
		$jump 		= 24*3600;
		$path 		= 'db/'.$year.'.json';

		$startDate 	= '01-01-'.$year;
		$i = 0;
		$count = -1;
		$noresult = -1;

		//Check file is already exists or not.
		if (file_exists($path)) {
			$db 		= json_decode(file_get_contents($path));
			end($db);
			$lastMonth 	= $db->{key($db)};
			end($lastMonth);
			$startDate 	= key($lastMonth);
		} else {
			$db 		= new stdClass();
		}

		// Only get results and insert after 19:00, everyday.
		$startDate .= ' 19:00';

		do {
			$date 	= strtotime($startDate) + $i*$jump;
			$url 	= 'http://ketqua.net/xo-so-truyen-thong.php?ngay='.date('d-m-Y', $date);
			$day	= date('l', $date);
			$month 	= date('F', $date);
			
			if (time() >= $date) {
				$html 	= file_get_html($url);
				$table 	= $html->getElementById('result_tab_mb');
			} else {
				$table = NULL;
			}
			
			if (isset($table) && !empty($table)) {
				if (!isset($db->{$month}))
					$db->{$month} = new stdClass();
				
				$db->{$month}->{date('d-m-Y', $date)} 				= new stdClass();
				$db->{$month}->{date('d-m-Y', $date)}->day 			= $day;
				$db->{$month}->{date('d-m-Y', $date)}->xsmb 		= new stdClass();
				$db->{$month}->{date('d-m-Y', $date)}->xsmb		 	= new stdClass();
				$db->{$month}->{date('d-m-Y', $date)}->loto 		= array();
				
				$trs = $table->children(1)->children();
				$xs_result = array();
				foreach ($trs as $index => $tr) {
					foreach($tr->children() as $j => $td) {
						if ($j == 0)
							continue;
						$id = substr($td->getAttribute('id'), -3, -2);
						switch ($id) {
							case 0:
								$name = 'Đặc biệt';
								break;
							case 1:
								$name = 'Giải Nhất';
								break;
							case 2:
								$name = 'Giải Nhì';
								break;
							case 3:
								$name = 'Giải Ba';
								break;
							case 4:
								$name = 'Giải Tư';
								break;
							case 5:
								$name = 'Giải Năm';
								break;
							case 6:
								$name = 'Giải Sáu';
								break;
							case 7:
								$name = 'Giải Bảy';
								break;
						}
						if (!isset($xs_result[$name]))
							$xs_result[$name] = array();
						if ($td->innertext !== '&nbsp;') {
							$xs_result[$name][] = $td->innertext;
							$db->{$month}->{date('d-m-Y', $date)}->loto[] = substr($td->innertext,-2);
						}
					}
				}
				
				$db->{$month}->{date('d-m-Y', $date)}->xsmb = $xs_result;
				$count++;
			} else {
				$noresult++;
			}	
			$i++;
		} while ($noresult <= 7);

		$content =  json_encode($db);

		//save new results if it is updated.
		if ($count != 0 && $count != -1) {
			$hanlde = fopen($path, 'w');
			if (file_put_contents($path, $content))
				echo 'update success';
			else 
				echo 'update fail';
			fclose($hanlde);
		} else {
			echo 'uptodate';
		}
	}
	
	public static function render($data) {
		echo 'test';
	}
}
