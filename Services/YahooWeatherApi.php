<?php

	/*
	 * This file is part of JbYahooWeatherApiBundle
	 * @see https://developer.yahoo.com/weather/
	 */

	namespace Jb\YahooWeatherApiBundle\Services;

	use Buzz\Browser;

	class YahooWeatherApi{
		
		protected $urlApi = "http://weather.yahooapis.com/forecastrss";
		protected $woeid;
		protected $unit;
		protected $xml;

		/**
		* @param string $woeid Woeid inject by Symfony2
		* @param string $unit Unit inject by Symfony2 (f or c for Farheneint or Celsius)
     	*/
		public function __construct($woeid,$unit){
			$this->woeid = $woeid;
			$this->unit = $unit;
		}

		/**
		* Run query to Yahoo Weather Api with parameter given in Symfony2 config
		* 
     	* @param string|null $woeid Woeid of the city to force query on specific city
     	*
     	* @return YahooWeatherApi Current object
     	*/
		public function query($woeid=null){

			$woeidToUse = (is_null($woeid)) ? $this->woeid : $woeid;

			$browser = new Browser();
			$response = $browser->get($this->urlApi.'?w='.$woeidToUse.'&u='.$this->unit);
			$content = $response->getContent();
			$xml = new \SimpleXMLElement($content);

			$this->xml = $xml;

			return $this;
		}


		/**
		* Get raw xml from Query done to Yahoo weather Api
		*
     	* @return SimpleXMLElement Yahoo Weather Api Response
     	*/
		public function getXml(){
			return $this->xml;
		}

		/**
		* Get Current condition datas : Attributes of node yweather:condition of xml
		* @see https://developer.yahoo.com/weather/#item
     	* 
     	* @return array All attributes of yweather:condition node
     	*/
		public function getCurrentCondition(){
			$current = $this->xml->channel->item->xpath('yweather:condition');

			$toReturn = array();

			foreach ($current[0]->attributes() as $key => $value) {
				$toReturn[$key] = (string)$value;
			}

			return $toReturn;
		}

		/**
		* Get 5 days forecasts datas : Attributes of all nodes yweather:forecast of xml
		* @see https://developer.yahoo.com/weather/#item
     	* 
     	* @return array All attributes of yweather:forecast nodes
     	*/
		public function getForecast(){
			$forecasts = $this->xml->channel->item->xpath('yweather:forecast');

			$toReturn = array();

			foreach ($forecasts as $forecast) {

				$tmpForecast = array();

				foreach ($forecast->attributes() as $key => $value) {
					$tmpForecast[$key] = (string)$value;
				}

				$toReturn[] = $tmpForecast;
			}

			return $toReturn;
		}

		/**
		* Get location datas : Attributes of node yweather:location of xml
		* @see https://developer.yahoo.com/weather/#channel
		* 
     	* @return array All attributes of yweather:location node
     	*/
		public function getLocation(){
			$location = $this->xml->channel->xpath('yweather:location');

			$toReturn = array();

			foreach ($location[0]->attributes() as $key => $value) {
				$toReturn[$key] = (string)$value;
			}

			return $toReturn;
		}

		/**
		* Return temperature data
		* 
     	* @return string temperature
     	*/
		public function temp(){
			$current = $this->xml->channel->item->xpath('yweather:condition');
			$temp = (string)$current[0]->attributes()->temp;

			return $temp;	
		}

		/**
		* Return city data
		* 
     	* @return string city
     	*/
		public function city(){
			$current = $this->xml->channel->xpath('yweather:location');
			$city = (string)$current[0]->attributes()->city;

			return $city;
		}

	}