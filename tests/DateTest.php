<?php
require_once __DIR__.'/../init.php';

class TestDates extends PHPUnit_Framework_TestCase {

	function testDateComponents(){
		$date = '05/06/1988';
		$date = date_components($date);
		$this->assertEquals([
			'year' => 1988, 'month' => 06, 'day' => 05,
		], $date);
	}

	function testYearCorrectReturn(){
		$year = 88;
		$year = date_year_correct($year);
		$this->assertEquals(1988, $year);
	}

	function testYearCorrectRef(){
		$year = 88;
		date_year_correct_($year);
		$this->assertEquals(1988, $year);
	}

	function testAgeLastMonth(){
		$dob = '05/06/1988';
		$age = age_from_dob($dob, '2015-01-01');
		$this->assertEquals(26, $age);
	}

	public function validDobs(){
		return array(
			['01/5/1980', '1980-05-01'],
			['01/01/1920', '1920-01-01'],
			['1/9/20', '2020-09-01'],
			['01/12/88', '1988-12-01'],
		);
	}

	/**
	 * @dataProvider validDobs
	 */
	function testDateFromValidDob($dob, $expected){
		$date = date_from_dob($dob);
		$this->assertEquals($expected, $date);
	}

	public function invalidDobs(){
		return array(
			['01/jan/1980', '1980-jan-01'],
			['01/0123/1920', '1920-0123-01'],
			['x/9/1955', '1955-9-x'],
			['01/12/xx', 'xx-12-01'],
			['xx/xx', '-xx-xx'],
			['1sd', '1sd'],
		);
	}

	/**
	 * @dataProvider invalidDobs
	 */
	function testDateFromInvalidDob($dob, $expected){
		$date = date_from_dob($dob);
		$this->assertNotEquals($expected, $date);
	}

	function testSaturdaysInFeb2014(){
		$date = '01/02/2014';
		$saturdays = days_left(6, $date);
		$this->assertEquals(4, $saturdays);
	}

	function testSaturdaysMidFeb2014(){
		$date = '12/02/2014';
		$sats = days_left(6, $date);
		$this->assertEquals(2, $sats);
	}

	function testSaturdaysEndFeb2014(){
		$date = '28/02/2014';
		$sats = days_left(6, $date);
		$this->assertEquals(0, $sats);
	}

	// seconds_to_time
	function testSeconds(){
		$seconds = 20;
		$convert = seconds_to_time($seconds);
		$this->assertEquals('20', $convert);
	}

	function testSecondsMinute(){
		$seconds = 60;
		$convert = seconds_to_time($seconds);
		$this->assertEquals('1:00', $convert);
	}

	function testSecondsMinutes(){
		$seconds = 80;
		$convert = seconds_to_time($seconds);
		$this->assertEquals('1:20', $convert);
	}

	function testSecondsMinutesHours(){
		$seconds = (60 * 60) + 80;
		$convert = seconds_to_time($seconds);
		$this->assertEquals('1:1:20', $convert);
	}

	function testSecondsHour(){
		$seconds = (60 * 60);
		$convert = seconds_to_time($seconds);
		$this->assertEquals('1:00:00', $convert);
	}

	function testSecondsDay(){
		$seconds = (60 * 60 * 24);
		$convert = seconds_to_time($seconds);
		$this->assertEquals('1 days, 0:00:00', $convert);
	}

	function testSecondsMinutesHoursDays(){
		$seconds = (60 * 60 * 24) + 80;
		$convert = seconds_to_time($seconds);
		$this->assertEquals('1 days, 0:1:20', $convert);
	}

	function testSecondsYear(){
		$seconds = (60 * 60 * 24 * 365);
		$convert = seconds_to_time($seconds);
		$this->assertEquals('1 years, 0:00:00', $convert);
	}

	function testSecondsMinutesHoursDaysYears(){
		$seconds = (60 * 60 * 24 * 365) + 80;
		$convert = seconds_to_time($seconds);
		$this->assertEquals('1 years, 0:1:20', $convert);
	}

	function testSecondsMinutesHoursDaysYearsBoth(){
		$seconds = (60 * 60 * 24 * 365) + (60 * 60 * 24) + 80;
		$convert = seconds_to_time($seconds);
		$this->assertEquals('1 years, 1 days, 0:1:20', $convert);
	}

	// seconds_convert
	function testSecondsToDays(){
		$seconds = (60 * 60 * 24 * 35);
		$convert = seconds_convert($seconds, 'day');
		$this->assertEquals(35, $convert);
	}

	function testSecondsToWeeks(){
		$seconds = (60 * 60 * 24 * 35);
		$convert = seconds_convert($seconds, 'week');
		$this->assertEquals(5, $convert);
	}

	function testSecondsToMonths(){
		$seconds = (60 * 60 * 24 * 35);
		$convert = seconds_convert($seconds, 'month');
		$this->assertEquals(1, $convert);
	}

	function testSecondsToQuarters(){
		$seconds = (60 * 60 * 24 * 285);
		$convert = seconds_convert($seconds, 'quarter');
		$this->assertEquals(3, $convert);
	}

	function testSecondsToYears(){
		$seconds = (60 * 60 * 24 * 385);
		$convert = seconds_convert($seconds, 'year');
		$this->assertEquals(1, $convert);
	}

	function testSecondsNotYears(){
		$seconds = (60 * 60 * 24 * 364);
		$convert = seconds_convert($seconds, 'year');
		$this->assertEquals(0, $convert);
	}

	function testStringToTimeAm(){
		$time = '9:24am';
		make_time($time, false, 'H:i:s');
		$this->assertEquals('09:24:00', $time);
	}

	function testStringToTimePm(){
		$time = '9:24pm';
		make_time($time, false, 'H:i:s');
		$this->assertEquals('21:24:00', $time);
	}

	function testStringToTime24h(){
		$time = '14:24';
		make_time($time, false, 'H:i:s');
		$this->assertEquals('14:24:00', $time);
	}

	function testStringToTime24hPm(){
		$time = '14:24pm';
		make_time($time);
		$this->assertEquals('14:24pm', $time);
	}

	// sql_dat
	function testSqlDatUTC(){
		$date = '2015-02-13';
		$dat = sql_dat($date);
		$this->assertEquals('2015-02-13', $dat);
	}

	function testSqlDatUK(){
		$date = '13/02/2015';
		$dat = sql_dat($date);
		$this->assertEquals('2015-02-13', $dat);
	}

	function testSqlDatUKShort(){
		$date = '1/2/15';
		$dat = sql_dat($date);
		$this->assertEquals('2015-02-01', $dat);
	}

	function testSqlDatUKDot(){
		$date = '13.02.2015';
		$dat = sql_dat($date);
		$this->assertEquals('2015-02-13', $dat);
	}

	function testSqlDatUKDash(){
		$date = '13-02-2015';
		$dat = sql_dat($date);
		$this->assertEquals('2015-02-13', $dat);
	}

	function testSqlDatUSA(){
		$date = '02/13/2015';
		$dat = sql_dat($date, $error, true);
		$this->assertEquals('2015-02-13', $dat);
	}

	function testSqlDatUSAShort(){
		$date = '2/1/15';
		$dat = sql_dat($date, $error, true);
		$this->assertEquals('2015-02-01', $dat);
	}

	function testSqlDatYmd(){
		$date = '2015/02/13';
		$dat = sql_dat($date);
		$this->assertEquals('2015-02-13', $dat);
	}

	function testSqlDatYmdShort(){
		$date = '2015/2/13'; // cant do short year in this format - would just be silly!
		$dat = sql_dat($date);
		$this->assertEquals('2015-02-13', $dat);
	}

	// date_nearest_day

	function testNextDay(){
		$date = '2016-05-10'; // a tuesday
		$next_thursday = date_next_day($date, 'thu');
		$this->assertEquals('2016-05-12', $next_thursday);
	}

	function testPrevDay(){
		$date = '2016-05-10'; // a tuesday
		$prev_thursday = date_prev_day($date, 'thu');
		$this->assertEquals('2016-05-05', $prev_thursday);
	}

	function testPrevDayIsToday(){
		$date = '2016-05-10'; // a tuesday
		$prev_tuesday = date_prev_day($date, 'tue');
		$this->assertEquals('2016-05-10', $prev_tuesday);
	}

	// date_working_forward

	function testDateWorkingForward(){
		$date = '2016-07-01'; // a friday
		$one_working_day = date_working_forward($date, 1);
		$this->assertEquals('2016-07-04', $one_working_day);
	}

	function testDateWorkingForwardStartSat(){
		$date = '2016-07-02'; // a saturday
		$one_working_day = date_working_forward($date, 1);
		$this->assertEquals('2016-07-05', $one_working_day); // should be a tuesday, 1 working day from monday
	}

	function testDateWorkingBackward(){
		$date = '2016-07-04'; // a monday
		$one_working_day = date_working_backward($date, 1);
		$this->assertEquals('2016-07-01', $one_working_day);
	}

	function testDateWorkingBackwardSun(){
		$date = '2016-07-03'; // a sunday
		$one_working_day = date_working_backward($date, 1);
		$this->assertEquals('2016-07-01', $one_working_day);
	}

	// string_time

	function testStringTimeFarFutureError(){
		$date = '2116-07-01';
		$error = '';
		$result = string_time($date, $error);
		$this->assertFalse($result);
		$this->assertContains('too far', $error);
		$this->assertContains('future', $error);
	}

	function testStringTimeNearFutureFine(){
		$date = '2061-07-01';
		$stamp = mktime(0,0,0,7,1,2061);
		$error = '';
		$result = string_time($date, $error);
		$this->assertEquals($stamp, $result);
		$this->assertEquals('', $error);
	}

	function testStringTimeFirstJan1970Fine(){
		$date = '1970-01-01';
		$stamp = mktime(0,0,0,1,1,1970);
		$error = '';
		$result = string_time($date, $error);
		$this->assertEquals($stamp, $result);
		$this->assertEquals('', $error);
	}

	function testStringTimeNearPastFine(){
		$date = '1916-07-01';
		$stamp = mktime(0,0,0,7,1,1916);
		$error = '';
		$result = string_time($date, $error);
		$this->assertEquals($stamp, $result);
		$this->assertEquals('', $error);
	}

	function testStringTimeFarPastError(){
		$date = '1861-07-01';
		$error = '';
		$result = string_time($date, $error);
		$this->assertFalse($result);
		$this->assertContains('too far', $error);
		$this->assertContains('past', $error);
	}

	function testStringTimeFarPastChangedLimitFine(){
		$date = '1861-07-01';
		$stamp = mktime(0,0,0,7,1,1861);
		$min_stamp = mktime(0,0,0,1,1,1850);
		$error = '';
		$result = string_time($date, $error, false, $min_stamp);
		$this->assertEquals($stamp, $result);
		$this->assertEquals('', $error);
	}

	function testStringTimeInvalidRandomError(){
		$date = 'hello';
		$error = '';
		$result = string_time($date, $error);
		$this->assertFalse($result);
		$this->assertContains('wasn\'t recognised', $error);
	}

	function testStringTimeInvalidBadFormatError(){
		$date = '07/31/2016';
		$error = '';
		// $date_usa false by default
		$result = string_time($date, $error);
		$this->assertFalse($result);
		$this->assertContains('wasn\'t recognised', $error);
	}

	function testStringTimeInvalidBadFormatUSAError(){
		$date = '31/07/2016';
		$error = '';
		$result = string_time($date, $error, true);
		$this->assertFalse($result);
		$this->assertContains('wasn\'t recognised', $error);
	}

	function testStringTimeFormats(){
		$stamp = mktime(0,0,0,7,31,2016);
		$error = '';

		$non_usa = [
			'yyyy-mm-dd' => '2016-07-31',
			'dd-mm-yyyy' => '31-07-2016',
			'dd.mm.yyyy' => '31.07.2016',
			'dd/mm/yyyy' => '31/07/2016',
			'dd/mm/yy' => '31/07/16',
		];
		foreach ($non_usa as $format => $date) {
			$result = string_time($date, $error);
			$this->assertEquals($stamp, $result, 'Bad output for '.$format);
			$this->assertEquals('', $error, 'Non-empty error for '.$format);
		}

		$usa = [
			'mm-dd-yyyy' => '07-31-2016',
			'mm.dd.yyyy' => '07.31.2016',
			'mm/dd/yyyy' => '07/31/2016',
			'mm/dd/yy' => '07/31/16',
		];
		foreach ($usa as $format => $date) {
			$result = string_time($date, $error, true);
			$this->assertEquals($stamp, $result, 'Bad output for '.$format);
			$this->assertEquals('', $error, 'Non-empty error for '.$format);
		}
	}
}
