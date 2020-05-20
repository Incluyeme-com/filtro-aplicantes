<?php
/**
 * Copyright (c) 2020.
 * Jesus Nuñez <Jesus.nunez2050@gmail.com>
 */
include 'filters/WP_Filters_Incluyeme.php';

class WP_Incluyeme extends WP_Filters_Incluyeme
{
	const VERSION = '1.0.0';
	
	function searchModifiedIncluyeme($withExtra = false)
	{
		$query = "SELECT
  %prefix%users.user_email,
  %prefix%users.display_name,
  %prefix%wpjb_resume.phone,
  %prefix%wpjb_resume.description,
  %prefix%wpjb_job.job_title,
  %prefix%posts.guid,
  %prefix%usermeta.meta_key,
  %prefix%wpjb_application.status AS applicant_status,
  %prefix%usermeta.meta_value as first_name,
  %prefix%wpjb_resume.candidate_state,
  %prefix%wpjb_resume.candidate_location,
  %prefix%users.ID AS users_id,
  %prefix%wpjb_application.id AS application_id,
  %prefix%wpjb_resume.id AS resume_id,
  lValue.value AS discap,
  lVal.meta_value AS last_name,
  meta.name AS type_discap,
  %prefix%wpjb_resume_detail.grantor AS contratante,
  %prefix%wpjb_resume_detail.detail_title AS puesto,
  %prefix%wpjb_resume_detail.type AS WType,
  edu.grantor AS academia,
  edu.detail_title AS titulo,
  edu.type AS eduType
FROM %prefix%wpjb_resume
  INNER JOIN %prefix%users
    ON %prefix%users.ID = %prefix%wpjb_resume.user_id
  INNER JOIN %prefix%wpjb_resume_search
    ON %prefix%wpjb_resume.id = %prefix%wpjb_resume_search.resume_id
  INNER JOIN %prefix%wpjb_application
    ON %prefix%wpjb_resume.user_id = %prefix%wpjb_application.user_id
  INNER JOIN %prefix%wpjb_job
    ON %prefix%wpjb_application.job_id = %prefix%wpjb_job.id
  LEFT OUTER JOIN %prefix%wpjb_meta_value
    ON %prefix%wpjb_resume.id = %prefix%wpjb_meta_value.object_id
  LEFT OUTER JOIN %prefix%wpjb_meta
    ON %prefix%wpjb_meta_value.meta_id = %prefix%wpjb_meta.id
        LEFT OUTER JOIN %prefix%wpjb_meta_value as idiomsV
    ON %prefix%wpjb_resume.id =idiomsV.object_id
        LEFT OUTER JOIN %prefix%wpjb_meta idioms
    ON idiomsV.meta_id = idioms.id
  LEFT OUTER JOIN %prefix%wpjb_tagged
    ON %prefix%wpjb_resume.id = %prefix%wpjb_tagged.id
  LEFT OUTER JOIN %prefix%posts
    ON %prefix%wpjb_resume.post_id = %prefix%posts.ID
  INNER  JOIN %prefix%usermeta
    ON %prefix%users.ID = %prefix%usermeta.user_id
  AND %prefix%usermeta.meta_key = 'first_name'
  LEFT OUTER JOIN %prefix%wpjb_company
    ON %prefix%wpjb_job.employer_id = %prefix%wpjb_company.id
  LEFT OUTER JOIN %prefix%wpjb_meta_value lValue
    ON %prefix%wpjb_resume.id = lValue.object_id
  LEFT OUTER JOIN %prefix%usermeta lVal
    ON %prefix%users.ID = lVal.user_id
  AND lVal.meta_key = 'last_name'
  INNER  JOIN %prefix%wpjb_meta meta
    ON lValue.meta_id = meta.id
  AND meta.name = 'tipo_discapacidad'
  LEFT OUTER JOIN %prefix%wpjb_resume_detail
    ON %prefix%wpjb_resume.id = %prefix%wpjb_resume_detail.resume_id
  AND 1 = %prefix%wpjb_resume_detail.type
  LEFT OUTER JOIN %prefix%wpjb_resume_detail edu
    ON %prefix%wpjb_resume.id = edu.resume_id
    AND 2 = edu.type
        LEFT OUTER JOIN %prefix%incluyeme_users_idioms
    ON %prefix%wpjb_resume.id = %prefix%incluyeme_users_idioms.resume_id
WHERE
 %prefix%wpjb_company.user_id = %userID% ";
		
		$group = '
  GROUP BY   %prefix%users.user_email';
		if ($this->getSearchPhrase() !== null) {
			$queries = $this->addQueries($query, true);
		} else {
			$queries = $this->addQueries($query);
		}
		$queries = $queries . $group;
		error_log(print_r($this->changePrefix($queries), true));
		$results = $this->executeQueries($this->changePrefix($queries));
		try {
			if (count($results) !== 0) {
				$response = $this->getCV($results);
				foreach ($response as $key => $search) {
					$rating = $this->getExtraRating($response[$key]->users_id);
					if ($rating !== false) {
						$response[$key]->rating = $rating[0]->rating;
					} else {
						if (self::getFavs()) {
							unset($response[$key]);
						} else {
							$response[$key]->rating = false;
						}
					}
				}
				$response = array_values($response);
				return array_unique($response, SORT_REGULAR);
			}
			return $response = [];
		} catch (Exception $e) {
			throw new Exception('Invalid data passing to this function: searchModifiedIncluyeme' . $e);
		}
	}
	
	private function getExtraRating($userId)
	{
		$query = 'SELECT
  %prefix%wpjb_meta_value.value as rating,
%prefix%users.ID as users_id
FROM  %prefix%wpjb_resume
  INNER JOIN %prefix%users
    ON %prefix%users.ID = %prefix%wpjb_resume.user_id
  LEFT JOIN %prefix%wpjb_resume_search
    ON %prefix%wpjb_resume.id = %prefix%wpjb_resume_search.resume_id
  LEFT JOIN %prefix%wpjb_application
    ON %prefix%wpjb_resume.user_id = %prefix%wpjb_application.user_id
  LEFT JOIN %prefix%wpjb_job
    ON %prefix%wpjb_application.job_id = %prefix%wpjb_job.id
  LEFT JOIN %prefix%wpjb_meta_value
    ON %prefix%wpjb_resume.id = %prefix%wpjb_meta_value.object_id
  LEFT JOIN %prefix%wpjb_meta
    ON %prefix%wpjb_meta_value.meta_id = %prefix%wpjb_meta.id
  LEFT JOIN %prefix%wpjb_tagged
    ON %prefix%wpjb_resume.id = %prefix%wpjb_tagged.id
  LEFT JOIN %prefix%posts
    ON %prefix%wpjb_resume.post_id = %prefix%posts.ID
  LEFT JOIN %prefix%usermeta
    ON %prefix%users.ID = %prefix%usermeta.user_id
  LEFT JOIN %prefix%wpjb_company
    ON %prefix%wpjb_job.employer_id = %prefix%wpjb_company.id
WHERE %prefix%wpjb_meta.name = "rating"
  AND %prefix%wpjb_company.user_id = %userID%
  AND %prefix%users.ID = %resume% ';
		
		$group = 'GROUP BY %prefix%users.user_email,
         %prefix%users.display_name,
         %prefix%wpjb_resume.phone,
         %prefix%wpjb_resume.description,
         %prefix%wpjb_application.applicant_name,
         %prefix%wpjb_job.job_title,
         %prefix%posts.guid,
         %prefix%wpjb_meta.name,
         %prefix%wpjb_meta_value.value,
         %prefix%usermeta.meta_key,
         %prefix%usermeta.meta_value,
         %prefix%wpjb_resume.candidate_state,
         %prefix%wpjb_resume.candidate_location,
          %prefix%users.ID  LIMIT 1';
		$query .= $group;
		$result = $this->executeQueries($this->changePrefix($query, '%resume%', $userId));
		try {
			if (count($result) === 0) {
				return false;
			}
			return $result;
		} catch (Exception $e) {
			throw new Exception('Invalid data passing to this function: getExtraData' . $e);
		}
	}
}