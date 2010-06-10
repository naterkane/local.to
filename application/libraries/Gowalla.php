<?php

/**
 * @author Klaas Cuvelier
 * @author cuvelierklaas@gmail.com
 * @author http://www.cuvedev.net
 */

	class GowallaAPI
	{
		const APIServer = 'http://api.gowalla.com/';
		const APIKey	= '';
		const Version	= '1.0';



		public function __construct()
		{
			// don't do shit, I just like constructors
		}


		/**
		 * Do the actual call to the server
		 * @return	mixed	JSON data
		 */
		protected function doAPICall($url)
		{
			$curl = curl_init($url);

			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_URL, self::APIServer . $url);
			curl_setopt($curl, CURLOPT_USERAGENT, 'GowallaAPI: ' . self::Version . '/Local.to ' . curl_version());
			curl_setopt($curl, CURLOPT_HTTPHEADER, array(
				'X-Gowalla-API-Key: ' . self::APIKey,
				'Content-Type: application/json',
				'Accept: application/json'
			));

			$data = curl_exec($curl);
			curl_close($curl);

			return json_decode($data, true);
		}


		/**
		 * Retrieve information about a specific spot
		 * @param	int	$spotID		ID of the spot you want to get the info from
		 * @return	mixed			Assoc Array Result
		 */
		public function getSpotInfo($spotID)
		{
			return $this->doAPICall('spots/' . (int)$spotID);
		}

		/**
		 * Retrieve a list of spots within a specified distance of a location
		 * @param	float $lat		Latitude
		 * @param	float $lng		Longitude
		 * @param	int $radius		Search radius (in meters)
		 * @return	mixed			Assoc Array Result
		 */
		public function getSpotList($lat, $lng, $radius)
		{
			return $this->doAPICall('spots?lat=' . (float)$lat . '&lng=' . (float)$lng . '&radius=' . (int)$radius);
		}

		/**
		 * Retrieve a list of check-ins at a particular spot. Show only the activity that to a given user
		 * @param	int $spotID		ID of the spot you want to get the activity from
		 * @return	mixed			Assoc Array Result
		 */
		public function getSpotEvents($spotID)
		{
			return $this->doAPICall('spots/' . (int)$spotID . '/events');
		}

		/**
		 * Retrieve a list of items available at a particular spot
		 * @param	int $spotID		ID of the spot you want to get the items from
		 * @return	mixed			Assoc Array Result
		 */
		public function getSpotItems($spotID)
		{
			return $this->doAPICall('spots/' . (int)$spotID . '/items');
		}

		/**
		 * Lists all spot categories
		 * @return	mixed			Assoc Array Result
		 */
		public function getCategories()
		{
			return $this->doAPICall('categories');
		}

		/**
		 * Retrieve information about a specific category
		 * @param	int $categoryID	ID of the category you want to get the info from
		 * @return	mixed			Assoc Array Result
		 */
		public function getCategory($categoryID)
		{
			return $this->doAPICall('categories/' . (int)$categoryID);
		}

		/**
		 * Retrieve information about a specific user
		 * @param	mixed $userID	ID of the user you want to get the info from
		 * @return	mixed			Assoc Array Result
		 */
		public function getUserInfo($userID)
		{
			return $this->doAPICall('users/' . $userID);
		}

		/**
		 * Retrieve a list of the stamps the user has collected
		 * @param mixed $userID		ID of the user you want to get the stamps from
		 * @param	int $limit		Number of results to show
		 * @return	mixed			Assoc Array Result
		 */
		public function getUserStamps($userID, $limit = 20)
		{
			return $this->doAPICall('users/' . $userID . '/stamps?limit=' . (int)$limit);
		}

		/**
		 * Retrieve a list of spots the user has visited most often
		 * @param	mixed $userID	ID of the spot you want to get the most visited spots from
		 * @return	mixed			Assoc Array Result
		 */
		public function getUserTopspots($userID)
		{
			return $this->doAPICall('users/' . $userID . '/top_spots');
		}

		/**
		 * Retrieve information about a specific item
		 * @param	int $itemID		ID of the item you want to get the info from
		 * @return	mixed			Assoc Array Result
		 */
		public function getItem($itemID)
		{
			return $this->doAPICall('items/' . (int)$itemID);
		}

		/**
		 * Retrieve a list of trips
		 * @return	mixed			Assoc Array Result
		 */
		public function getTrips()
		{
			return $this->doAPICall('trips');
		}

		/**
		 * Retrieve information about a specific trip
		 * @param	int $tripID		ID of the trip you want to get the info from
		 * @return	mixed			Assoc Array Result
		 */
		public function getTrip($tripID)
		{
			return $this->doAPICall('trips/' . (int)$tripID);
		}

	}

?>
