<?php
class Activity extends AppModel {
	var $name = 'Activity';
	//The Associations below have been created with all possible keys, those that are not needed can be removed
        
        var $belongsTo = array('User', 'Client');

	function insertActivity($userId, $clientId)
	{
		$data['user_id']= $userId;
		$data['client_id']= $clientId;
                $data['logged_in_time'] = date('Y-m-d H:i');
		$data['logout_time'] = date('Y-m-d H:i', strtotime('+1 minute'));
		
		/** function getRealIpAddr() find in app_model **/
		$data['logged_in_ip']=$this->getRealIpAddr();
		
		$this->save($data);
		return $this->getLastInsertId();
	}
        
        function getRealIpAddr() 
	{ 
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
		{ 
			$ip=$_SERVER['HTTP_CLIENT_IP']; 
		} 
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
		//to check ip is pass from proxy 
		{ 
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR']; 
		} 
		else 
		{ 
			$ip=$_SERVER['REMOTE_ADDR']; 
		} 
		return $ip; 
	}

	
	function updateActivity($activityId)
	{
		$status = $this->Field('logged_in_time', array('id' => $activityId));
		if(!empty($status))
		{
			$this->id = $activityId;
			$this->saveField('logout_time', date('Y-m-d H:i'));
		}
	}

	function updateActivityIsLogout($activityId)
	{
		$status = $this->Field('logged_in_time', array('id' => $activityId));
		if(!empty($status))
		{
			$this->id = $activityId;
			$this->saveField('is_logout', '1');
                        $this->saveField('logout_time', date('Y-m-d H:i'));
		}
	}

	
        	/**
	 * Overridden paginate method
	 */
	function paginate($conditions, $fields, $order, $limit, $page =1, $recursive = null, $extra = array()) {

		if (empty($order)) {
				//$order = array($extra['passit']['sort'] => $extra['passit']['direction']);
				$sort = $extra['passit']['sort'];
				$direction = $extra['passit']['direction'];
				$orderBy = 'ORDER BY '.$sort.' '.$direction;
		} else {
			if (is_array($order)) {
				$key = array_keys($order);
				$key = array_keys($order);
				$orderBy = 'ORDER BY '. $key[0] .' '. $order[$key[0]];
			} else {
				$orderBy=$order;
			}
		}

		if (!empty($conditions)) {
			$conditions = "where ". $conditions;
		} else {
			$conditions = '';
		}

		$offsetLimit = ($page-1)*$limit;
		if ($offsetLimit < 0) {
			$offsetLimit = 0;
		}

		if($conditions != "")
		{
			echo $sql="
			SELECT
			Activity.id,
			Activity.user_id,
			Activity.client_id,
			Activity.logged_in_ip,
			COUNT(Activity.client_id) as total_login, Client.hotelname,
			User.last_login
			FROM `activities` AS Activity
			LEFT JOIN `users` AS `User` ON (`Activity`.`user_id` = `User`.`id`)
			LEFT JOIN `clients` AS `Client` ON (`Activity`.`client_id` = `Client`.`id`)
			$conditions
			GROUP BY Activity.client_id
			". $orderBy ." limit ". $offsetLimit .','. $limit;
                        
                        
                        $sql="
			SELECT
			Activity.id,
			Activity.user_id,
			Activity.client_id,
			Activity.logged_in_ip,
			COUNT(Activity.client_id) as total_login
			FROM `activities` AS Activity
			
			GROUP BY Activity.client_id
                         limit ". $offsetLimit .','. $limit;
                        

			$results = $this->query($sql);
                        echo 'Results';
                        print_r($results);
                        
				if ($results) {
					return $results;
				} else {
					return false;
				}

		}
		else
		{
			return false;
		}

		
	}


	/**
	 * Overridden paginateCount method
	 */
	function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
		if (!empty($conditions)) {
			$criteria = ' And '.$conditions;
		} else {
			$criteria = '';
			return 0;
		}

		$sql = "SELECT DISTINCT `Activity`.user_id, User.last_login, Client.hotelname FROM `activities` as `Activity` LEFT JOIN `users` AS `User` ON (`Activity`.`user_id` = `User`.`id`) LEFT JOIN `clients` AS `Client` ON (`Activity`.`client_id` = `Client`.`id`) WHERE `Activity`.user_id != '0' ".$criteria;
/*		$sql = "SELECT * 
					FROM `clients` Client
					LEFT JOIN users User ON User.id = Client.user_id WHERE $conditions";
*/
		$results = $this->query($sql);
		return count($results);
	}

        
}//class end
