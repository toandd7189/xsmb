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
					if (in_array($l, $d))
						$checkNumbers[$l]++;
			}
			$i++;
		} while($i <= 99);
    
		asort($checkNumbers);
		$bestNumbers = array_keys($checkNumbers, min($checkNumbers));
		if ($cf)
			return $bestNumbers;
		else
			return $checkNumbers;
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
			$count = count($rs_by_special_ys);
			foreach ($rs_by_special_ys as $obj) {
				if (!$obj)
					$count--;
			}
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
					if (in_array($l, $obj->loto))
						$numbers[$l]++;
				}
				$i++;
				
			} while($i <= 99);
		}
		
		// Sort numbers.
		asort($numbers);
    
		$bestNumbers = array_keys($numbers, min($numbers));
		
		return $bestNumbers;
	}
	
	public function getBestSpecial($date) {
		$bestSpecial = [];
		$specials = [];
		$dayofweek = date('l', strtotime($date));
		$results = $this->getResultsDayOfWeek($dayofweek);
		
		if (empty($results)) return;
		
		foreach ($results as $result) {
			$specials[] = $result['xsmb']->{'Đặc biệt'}[0];
		}
		$i = 0;
		do {
			$s = '';
			if ($i < 10)
				$s .= '0'.$i;
			else
				$s .= $i;
			$bestSpecial[$s] = 0;
			foreach ($specials as $spe) {
				$lo = substr($spe, -2);
				if ($s == $lo)
					$bestSpecial[$s]++;
			}
			$i++;
		} while ($i <= 99);
		asort($bestSpecial);
		$best_sp = array_keys($bestSpecial, min($bestSpecial));
		
		return $best_sp;
	}
	
	public function getBestSpecialFL($date) {
		//$date = '28-07-2017';
		$specials = [];
		$dayofweek = date('l', strtotime($date));
		$results = $this->getResultsDayOfWeek($dayofweek);
		
		if (empty($results)) return;
		
		foreach ($results as $result) {
			if (!$result['xsmb']->{'Đặc biệt'})
				continue;
			$specials[] = substr($result['xsmb']->{'Đặc biệt'}[0], -2);
		}
		
		$firstLetters = array();
		$lastLetters = array();
		
		foreach ($specials as $sp) {
			$first = substr($sp, 0,1);
			$last = substr($sp, 1);
			
			// The First Letter.
			if (!isset($firstLetters[$first]))
				$firstLetters[$first] = 0;
			else 
				$firstLetters[$first]++;
			
			// The Last Letter.
			if (!isset($lastLetters[$last]))
				$lastLetters[$last] = 0;
			else 
				$lastLetters[$last]++;
		}
		asort($firstLetters);
		asort($lastLetters);
		var_dump($firstLetters);
		var_dump($lastLetters);
		die;
	}
	
}

