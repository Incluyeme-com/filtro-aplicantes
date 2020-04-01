<?php
/**
 * Copyright (c) 2020.
 * Jesus Nuñez <Jesus.nunez2050@gmail.com>
 */
require_once $_SERVER["DOCUMENT_ROOT"] . '/wp-load.php';

class WP_Filters_Incluyeme
{
	private static $user_id;
	private static $job;
	private static $disability;
	private static $status;
	private static $searchPhrase;
	private static $city;
	private static $course;
	private static $name;
	private static $lastName;
	private static $oral;
	private static $idioms;
	private static $education;
	private static $description;
	private static $residence;
	private static $letter;
	private static $email;
	
	function __construct()
	{
		self::$user_id = null;
		self::$job = null;
		self::$disability = null;
		self::$status = null;
		self::$searchPhrase = null;
		self::$city = null;
		self::$course = null;
		self::$name = null;
		self::$lastName = null;
		self::$oral = null;
		self::$idioms = null;
		self::$education = null;
		self::$description = null;
		self::$residence = null;
		self::$letter = null;
		self::$email = null;
	}
	
	/**
	 * @return mixed
	 */
	public static function getUserId()
	{
		return self::$user_id;
	}
	
	/**
	 * @param mixed $user_id
	 */
	public static function setUserId($user_id)
	{
		self::$user_id = $user_id;
	}
	
	/**
	 * @return mixed
	 */
	public static function getJob()
	{
		return self::$job;
	}
	
	/**
	 * @param mixed $job
	 */
	public static function setJob($job)
	{
		self::$job = $job;
	}
	
	/**
	 * @return mixed
	 */
	public static function getDisability()
	{
		return self::$disability;
	}
	
	/**
	 * @param mixed $disability
	 */
	public static function setDisability($disability)
	{
		self::$disability = $disability;
	}
	
	/**
	 * @return mixed
	 */
	public static function getStatus()
	{
		return self::$status;
	}
	
	/**
	 * @param mixed $status
	 */
	public static function setStatus($status)
	{
		self::$status = $status;
	}
	
	/**
	 * @return mixed
	 */
	public static function getSearchPhrase()
	{
		return self::$searchPhrase;
	}
	
	/**
	 * @param mixed $searchPhrase
	 */
	public static function setSearchPhrase($searchPhrase)
	{
		self::$searchPhrase = $searchPhrase;
	}
	
	/**
	 * @return mixed
	 */
	public static function getCity()
	{
		return self::$city;
	}
	
	/**
	 * @param mixed $city
	 */
	public static function setCity($city)
	{
		self::$city = $city;
	}
	
	/**
	 * @return mixed
	 */
	public static function getCourse()
	{
		return self::$course;
	}
	
	/**
	 * @param mixed $course
	 */
	public static function setCourse($course)
	{
		self::$course = $course;
	}
	
	/**
	 * @return mixed
	 */
	public static function getName()
	{
		return self::$name;
	}
	
	/**
	 * @param mixed $name
	 */
	public static function setName($name)
	{
		self::$name = $name;
	}
	
	/**
	 * @return mixed
	 */
	public static function getLastName()
	{
		return self::$lastName;
	}
	
	/**
	 * @param mixed $lastName
	 */
	public static function setLastName($lastName)
	{
		self::$lastName = $lastName;
	}
	
	/**
	 * @return mixed
	 */
	public static function getOral()
	{
		return self::$oral;
	}
	
	/**
	 * @param mixed $oral
	 */
	public static function setOral($oral)
	{
		self::$oral = $oral;
	}
	
	/**
	 * @return mixed
	 */
	public static function getIdioms()
	{
		return self::$idioms;
	}
	
	/**
	 * @param mixed $idioms
	 */
	public static function setIdioms($idioms)
	{
		self::$idioms = $idioms;
	}
	
	/**
	 * @return mixed
	 */
	public static function getEducation()
	{
		return self::$education;
	}
	
	/**
	 * @param mixed $education
	 */
	public static function setEducation($education)
	{
		self::$education = $education;
	}
	
	/**
	 * @return mixed
	 */
	public static function getDescription()
	{
		return self::$description;
	}
	
	/**
	 * @param mixed $description
	 */
	public static function setDescription($description)
	{
		self::$description = $description;
	}
	
	/**
	 * @return mixed
	 */
	public static function getResidence()
	{
		return self::$residence;
	}
	
	/**
	 * @param mixed $residence
	 */
	public static function setResidence($residence)
	{
		self::$residence = $residence;
	}
	
	/**
	 * @return mixed
	 */
	public static function getLetter()
	{
		return self::$letter;
	}
	
	/**
	 * @param mixed $letter
	 */
	public static function setLetter($letter)
	{
		self::$letter = $letter;
	}
	
	/**
	 * @return mixed
	 */
	public static function getEmail()
	{
		return self::$email;
	}
	
	/**
	 * @param mixed $email
	 */
	public static function setEmail($email)
	{
		self::$email = $email;
	}
	
	protected static function changePrefix($query, $changeData = false, $value = false)
	{
		global $wpdb;
		
		$change = [
			'%prefix%' => $wpdb->prefix,
			'%userID%' => self::getUserId()
		];
		if ($changeData && is_string($changeData)) {
			$change[$changeData] = $value;
		}
		return str_replace(array_keys($change), array_values($change), $query);
	}
	
	protected function executeQueries($sql)
	{
		global $wpdb;
		return $wpdb->get_results($sql);
	}
	
	protected function changeObjectReferenceIncluyeme($obj, $property, $rename, $eliminate = false)
	{
		if (!is_string($property) || !is_string($rename) || !is_array($obj)) {
			throw new Exception('Invalid data passing to this function');
		}
		$response = [];
		foreach ($obj as $change) {
			$change->$rename = $change->$property;
			unset($change->$property);
			if ($eliminate) {
				unset($change->$eliminate);
			}
			array_push($response, $change);
		}
		return $response;
	}
	
	public static function addQueries($sql, $phrase = false)
	{
		if (self::getJob() !== null) {
			$sql .= 'AND %prefix%wpjb_job.id = ' . self::getJob() . ' ';
		}
		if (self::getName() !== null) {
			$sql .= 'AND %prefix%usermeta.meta_value Like "%' . self::getName() . '%" ';
		}
		if (self::getStatus() !== null) {
			$sql .= 'AND %prefix%wpjb_application.status in ( %statuses% ) ';
			$sql = self::changePrefix($sql, '%statuses%', implode(',', self::getStatus()));
		}
		if (self::getResidence() !== null) {
			$sql .= 'AND %prefix%wpjb_resume.candidate_state Like "%' . self::getResidence() . '%" ';
		}
		if (self::getCity() !== null) {
			$sql .= 'AND %prefix%wpjb_resume.candidate_location Like "%' . self::getCity() . '%" ';
		}
		if (self::getName() !== null) {
			$sql .= 'AND %prefix%usermeta.meta_value Like "%' . self::getName() . '%" ';
		}
		if (self::getEmail() !== null) {
			$sql .= 'AND %prefix%users.user_email = "' . self::getEmail() . '" ';
		}
		if (self::getSearchPhrase() !== null && $phrase) {
			$sql .= 'AND ( %prefix%usermeta.meta_value Like  "%' . self::getSearchPhrase() . '%" ';
			$sql .= 'OR %prefix%wpjb_application.status Like "%' . self::getSearchPhrase() . '%" ';
			$sql .= 'OR %prefix%wpjb_resume.candidate_state Like "%' . self::getSearchPhrase() . '%" ';
			$sql .= 'OR %prefix%wpjb_resume.candidate_location Like "%' . self::getSearchPhrase() . '%" ';
			$sql .= 'OR %prefix%usermeta.meta_value  Like "%' . self::getSearchPhrase() . '%" ';
			$sql .= 'OR  edu.grantor  Like "%' . self::getSearchPhrase() . '%" ';
			$sql .= 'OR edu.detail_title  Like "%' . self::getSearchPhrase() . '%" ';
			$sql .= 'OR %prefix%users.user_email Like "%' . self::getSearchPhrase() . '%" )';
		}
		if (self::getLastName() !== null) {
			$sql .= 'AND lVal.meta_value Like "%' . self::getLastName() . '%" ';
		}
		if (self::getCourse() !== null) {
			$sql .= 'AND edu.detail_title Like "%' . self::getCourse() . '%" ';
		}
		if (self::getEducation() !== null) {
			$sql .= 'AND edu.grantor Like "%' . self::getEducation() . '%" ';
		}
		if (self::getIdioms() !== null) {
			$sql .= 'AND idioms.name = "' . self::getIdioms() . '" ';
			$sql .= 'AND idiomsV.value != "No hablo" ';
		}
		if (self::getDisability() !== null) {
			$sql .= 'AND lValue.value in ( %disability% ) ';
			$sql = self::changePrefix($sql, '%disability%', '"' . implode('","', self::getDisability()) . '"');
		}
		return $sql;
	}
	
	public static function addQueriesSecondSQL($sql, $phrase = false)
	{
		if (self::getLastName() !== null) {
			$sql .= 'AND %prefix%usermeta.meta_value Like "%' . self::getLastName() . '%" ';
		}
		if (self::getDisability() !== null) {
			$sql .= 'AND %prefix%wpjb_meta_value.value in ( %disability% ) ';
			$sql = self::changePrefix($sql, '%disability%', '"' . implode(',', self::getDisability()) . '"');
		}
		return $sql;
	}
	
	public static function unionObjectsIncluyeme($obj, $mix, $param, $paramMix)
	{
		
		try {
			if (!is_array($obj) || !is_array($mix) || empty($param) || empty($paramMix)) {
				throw new Exception('Invalid data passing to this function: unionObjectsIncluyeme');
			}
			$positions = [];
			for ($i = 0; $i < count($obj); $i++) {
				foreach ($mix as $itemsMix) {
					if ($obj[$i]->$param === $itemsMix->$paramMix) {
						unset($itemsMix->$paramMix);
						foreach ($itemsMix as $key => $value) {
							$obj[$i]->$key = $value;
						}
					}
				}
			}
			return $obj;
		} catch (Exception $e) {
			return $obj;
		}
	}
	
	public function deleteData($obj)
	{
		for ($i = 0; $i < count($obj); $i++) {
			$exists = array_key_exists('discap', get_object_vars($obj[$i]));
			if (!property_exists($obj[$i], 'discap') || property_exists($obj[$i], 'discap') === null || !$exists) {
				unset($obj[$i]);
			}
		}
		$obj = array_merge($obj);
		return $obj;
	}
	
	public function getCV($obj)
	{
		$path = wp_upload_dir();
		$basePath = $path['basedir'];
		$baseDir = $path['baseurl'];
		for ($i = 0; $i < count($obj); $i++) {
			$route = $basePath . '/wpjobboard/resume/' . $obj[$i]->resume_id;
			$dir = $baseDir . '/wpjobboard/resume/' . $obj[$i]->resume_id;
			if (file_exists($route)) {
				if (file_exists($route . '/cv/')) {
					$search = opendir($route . '/cv/');
					while ($file = readdir($search)) {
						$obj[$i]->CV = $dir . '/cv/' . $file;
					}
				} else {
					$obj[$i]->CV = false;
				}
				if (file_exists($route . '/image/')) {
					$search = opendir($route . '/image/');
					while ($file = readdir($search)) {
						$obj[$i]->img = $dir . '/image/' . $file;
					}
				} else {
					$obj[$i]->CUD = false;
				}
				if (file_exists($route . '/certificado-discapacidad/')) {
					$search = opendir($route . '/certificado-discapacidad/');
					while ($file = readdir($search)) {
						$obj[$i]->CUD = $dir . '/certificado-discapacidad/' . $file;
					}
				} else {
					$obj[$i]->img = false;
				}
			} else {
				$obj[$i]->img = false;
				$obj[$i]->CUD = false;
				$obj[$i]->CV = false;
			}
		}
		return $obj;
	}
	
	public static function unionObjectsRating($obj, $mix, $param, $paramMix)
	{
		
		try {
			if (!is_array($obj) || !is_array($mix) || empty($param) || empty($paramMix)) {
				throw new Exception('Invalid data passing to this function: unionObjectsIncluyeme');
			}
			for ($i = 0; $i < count($obj); $i++) {
				foreach ($mix as $itemsMix) {
					if ($obj[$i]->$param === $itemsMix->$paramMix) {
						unset($itemsMix->$paramMix);
						foreach ($itemsMix as $key => $value) {
							$obj[$i]->$key = $value;
						}
					}
				}
			}
			return $obj;
		} catch (Exception $e) {
			return $obj;
		}
	}
	
	public static function changeFavPub($exist = false, $id = false)
	{
		global $wpdb;
		if ($exist == 1 && $id !== false) {
			$query = 'DELETE
  FROM %prefix%wpjb_meta_value
WHERE object_id = ' . $id . '
  AND meta_id = (SELECT
      %prefix%wpjb_meta.id
    FROM %prefix%wpjb_meta
    WHERE %prefix%wpjb_meta.name = \'rating\')';
			$query = self::changePrefix($query);
			$wpdb->query($query);
		} else {
			$query = 'INSERT INTO wp_wpjb_meta_value (meta_id, object_id, value)
  VALUES ((SELECT wp_wpjb_meta.id FROM wp_wpjb_meta WHERE wp_wpjb_meta.name = \'rating\'), (' . $id . '), 5)';
			$query = self::changePrefix($query);
			$wpdb->query($query);
		}
	}
}

