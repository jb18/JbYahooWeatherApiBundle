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
        protected $status;

        protected $leasewebmemcached = null;

        /**
        * @param string $woeid Woeid inject by Symfony2
        * @param string $unit Unit inject by Symfony2 (f or c for Farheneint or Celsius)
        * @param Lsw\MemcacheBundle\Cache\AntiDogPileMemcache|null Instance of LeaseWebMemcachedBundle if exists
        */
        public function __construct($woeid,$unit,$leasewebmemcached){
            $this->woeid = $woeid;
            $this->unit = $unit;
            $this->leasewebmemcached = $leasewebmemcached;
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
            $keyCache = 'content_'.$woeidToUse;

            if($this->leasewebmemcached !== null){
                $content = $this->leasewebmemcached->get($keyCache);
            }

            if($this->leasewebmemcached === null || $content === null){
                $browser = new Browser();
                $response = $browser->get($this->urlApi.'?w='.$woeidToUse.'&u='.$this->unit);
                
                $this->status = $response->getStatusCode();
                                                  
                $content = $response->getContent();
                if($this->leasewebmemcached !== null){
                    $this->leasewebmemcached->set($keyCache,$content,600);
                }
            }
            
            if($this->status == 200){
                $xml = new \SimpleXMLElement($content);
            }else{
                $xml = null;
            }

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
            
            if(count($this->xml->channel->item->xpath('yweather:condition')) > 0){
                $current = $this->xml->channel->item->xpath('yweather:condition');
                $temp = (float)$current[0]->attributes()->temp;
            }else{
                $temp = null;
            }

            return $temp;    
        }

        /**
        * Return city data
        * 
        * @return string city
        */
        public function city(){
                        
            if(count($this->xml->channel->xpath('yweather:location')) > 0){
                $current = $this->xml->channel->xpath('yweather:location');
                $city = (string)$current[0]->attributes()->city;
            }else{
                $city = null;
            }

            return $city;
        }

    }

