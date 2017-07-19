<?php
class xsModel 
{
	public $data;
	
	function __construct() {
		$this->data = $this->getALlResults();
	}
	
	public function getResultsYear($year) {
		$path = 'db/'.$year.'.json';
		if (!file_exists($path))
			return;
		
		$content = file_get_contents($path);
		
		return json_decode($content);
	}
	
	public function getALlResults() {
		$results = array();
		$files = scandir('db/');
		if (empty($files))
			return;
		foreach ($files as $i => $file) {
			$ext = substr($file, -4);
			if ($ext !== 'json')
				continue;
			$year = str_replace('.'.$ext,'',$file);
			$results[$year] = $this->getResultsYear($year);	
		}
		
		return $results;
	}
	
	public function getResultsMonth($month) {
		$results = array();
		
		foreach ($this->data as $year) {
			if (empty($year)) continue;
			foreach ($year as $m => $values) {
				if (ucfirst($month) == $m)
					$results[] = $values;
			}
		}
	
		return $results;
	}
	
	public function getResultsDay($day, $month, $year) {
		if (empty($this->data[$year]->{$month}->{$day}))
			return;
		
		return $this->data[$year]->{$month}->{$day};
	}
	
	public function getResultsDayOfWeek($dayofweek, $year = NULL) {
		$results = [];
		if ($year)
			$r_year = $this->getResultsYear($year);
		else 
			$r_year = $this->data;
		if (empty($r_year)) return;
		if (is_array($r_year)) {
			foreach ($r_year as $r_y) {
				foreach ($r_y as $month) {
					foreach ($month as $day) {
						if (empty($day->loto)) continue;
						if (ucfirst($dayofweek) === $day->day) {
							$arr = array();
							$arr['xsmb'] = $day->xsmb;
							$arr['loto'] = $day->loto;
							$results[] = $arr;
						}
					}
				}
			}
		} else {
			foreach ($r_year as $month) {
				foreach ($month as $day) {
					if (empty($day->loto)) continue;
					if (ucfirst($dayofweek) === $day->day) {
						$arr = array();
						$arr['xsmb'] = $day->xsmb;
						$arr['loto'] = $day->loto;
						$results[] = $arr;
					}
				}
			}
		}
		
		return $results;
	}
	
	/*
	* Method get the number which is the most appearred in the year.
	* param		string		the day of week
	* param		string		year
	*
	* @return	array 		list of the numbers is the most appearred.
	*/
	public function getBestNumber($dayofweek, $cf = true, $year = NULL) {
		$bestNumbers = array();
		
		$results = $this->getResultsDayOfWeek($dayofweek, $year);
		$loto = [];
		if (empty($results)) return;
		foreach ($results as $result) {
			$loto[] = $result['loto'];
		}
		
		$checkNumbers = array();
		$i = 0;
		do {
			$l = '';
			if ($i < 10)
				$l = '0'.$i;
			else 
				$l .= $i;
			$checkNumbers[$l] = 0;
			foreach ($loto as $d) {
				foreach($d as $number) {
					if ($l === $number)
						$checkNumbers[$l]++;
				}
			}
			$i++;
		} while($i <= 99);

		$bestNumbers = array_keys($checkNumbers, max($checkNumbers));
		
		if ($cf)
			return $bestNumbers;
		else
			return $checkNumbers;
	}
	
	public function getSpecialYesterday($date) {
		
	}
	
	public function getResultsBySpecialYesterday($special_yesterday) {
		$results = [];
		$i = 0;
		foreach ($this->data as $y) {
			foreach ($y as $m) {
				foreach ($m as $date => $r) {
					foreach ($r->xsmb as $g => $kq) {
						if ($g === 'Đặc biệt' && substr($kq[0], -2) == $special_yesterday) {
							$ds = date('d-m-Y', strtotime($date) + 3600*24);
							$ys = date('Y', strtotime($date) + 3600*24);
							$ms = date('F', strtotime($date) + 3600*24);
							
							$results[] = $this->getResultsDay($ds, $ms, $ys);
						}
					}
				}
			}
		}
		
		return $results;
	}
	
	public function getNumbersBySpecialYesterday($date) {
		$numbers = [];
		$yesterday = date('d-m-Y', strtotime($date) - 3600*24);
		$y = date('Y', strtotime($yesterday));
		$m = date('F', strtotime($yesterday));
		
		// Get the number of special title in yesterday.
		$rs_yesterday = $this->getResultsDay($yesterday, $m, $y);
		$special_ys = substr($rs_yesterday->xsmb->{"Đặc biệt"}[0], -2);
		
		// Get all results of the next day which has special number in yesterday.
		$rs_by_special_ys = $this->getResultsBySpecialYesterday($special_ys);
		if (!empty($rs_by_special_ys)) {
			$i = 0; 
			do {
				$l = '';
				if ($i < 10)
					$l = '0'.$i;
				else 
					$l .= $i;
				$numbers[$l] = 0;
				foreach ($rs_by_special_ys as $obj) {
					if (!$obj)
						continue;
					foreach ($obj->loto as $lo) {
						if ($l === $lo)
							$numbers[$l]++;
					}
				}
				$i++;
				
			} while($i <= 99);
		}
		$bestNumbers = array_keys($numbers, max($numbers));
		
		return $bestNumbers;
	}
}

