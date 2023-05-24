<?php


namespace App\Http\traits;

use App\designation;

Trait MonthlyWorkedHours {

	public function totalWorkedHours($employee)
	{
		if($employee->employeeAttendance->isEmpty()){
			return 0;
		}else{
			$total = 0;
			foreach ($employee->employeeAttendance as $a)
			{
				sscanf($a->total_work, '%d:%d', $hour, $min);
				$total += $hour * 60 + $min;
			}

			if ($h = floor($total / 60))
			{
				$total %= 60;
			}
			$sum_total = sprintf('%02d:%02d', $h, $total);

			return $sum_total;
		}
	}
	public function totalPaidAmount($employee)
	{
		if($employee->employeeAttendance->isEmpty()){
			return 0;
		}else{
			$des= designation::find($employee->designation_id);

			if ($des->rate_type==1) {
				$total = 0;
				foreach ($employee->employeeAttendance as $a)
				{
					// dd($a);
					$rate=$des->rate_per_shift;
					$overtime_rate=$des->overtime_rate;

					$hours=$a->total_work;
					list($hour, $minute) = explode(':', $hours);
					$decimal = $hour + ($minute / 60);
					$overtime_hours=$a->overtime;
					list($hour, $minute) = explode(':', $overtime_hours);
					$overtime_decimal = $hour + ($minute / 60);
					
					try {
						$final=round(($decimal*$rate)+($overtime_decimal*$overtime_rate));
						
					} catch (\Throwable $th) {
						$final=0;
					}
					$total+=$final;
				}
				return $total;
			}else{
				$total = 0;
				foreach ($employee->employeeAttendance as $a)
				{
					$work=(int)$a->amount_paid;
					$total += $work;
				}
				return $total;
			}
		}
	}




    //************* Test */
    // public function totalOvertimeHours($employee)
	// {
	// 	if($employee->employeeAttendance->isEmpty()){
	// 		return 0;
	// 	}else{
	// 		$total = 0;
	// 		foreach ($employee->employeeAttendance as $a)
	// 		{
	// 			sscanf($a->overtime, '%d:%d', $hour, $min);
	// 			$total += $hour * 60 + $min;
	// 		}

	// 		if ($h = floor($total / 60))
	// 		{
	// 			$total %= 60;
	// 		}
	// 		$sum_total = sprintf('%02d:%02d', $h, $total);

	// 		return $sum_total;
	// 	}
	// }

    //************* Test End */











	// public function totalWorkedHours($employee)
	// {
	// 	$total = 0;
	// 	$current_year =  date('Y');
	// 	$current_month =  date('m');


	// 	$att = $employee->load( ['employeeAttendance' => function ($query) use ($current_year, $current_month)
	// 	{
	// 		$query->whereYear('attendance_date',$current_year)->whereMonth('attendance_date',$current_month);
	// 	},]);

	// 	return $att;

	// 	foreach ($att->employeeAttendance as $a)
	// 	{
	// 		sscanf($a->total_work, '%d:%d', $hour, $min);
	// 		$total += $hour * 60 + $min;

	// 	}

	// 	if ($h = floor($total / 60))
	// 	{
	// 		$total %= 60;
	// 	}
	// 	$sum_total = sprintf('%02d:%02d', $h, $total);

	// 	return $sum_total;
	// }
}
