<?php

namespace Jb\ApiYahooWeatherBundle\Services;

use Jb\ApiYahooWeather\Lib\ApiYahooWeather;

class ApiYahooWeatherService {
    
    protected $apiYahooWeatherObject = null;
    protected $memcached = null;
    protected $ttl;
    protected $lastInCache;
    
    protected $woeid;
    protected $unit;

    public function __construct($woeid, $unit, $memcached, $ttl) {
        
        $this->memcached = $memcached;
        $this->ttl = $ttl;
        
        $this->woeid = $woeid;
        $this->unit = $unit;
               
        $this->apiYahooWeatherObject = new ApiYahooWeather();
        if($woeid !== null){
            $this->callApi($woeid,$unit);
        }
    }

    public function callApi($woeid,$unit=null) {
        $data = false;
        if($this->memcached !== null){
            $data = $this->memcached->fetch($woeid);
        }
        if($data === false){
            $this->lastInCache = "no";
            $this->apiYahooWeatherObject->callApi($woeid,($unit===false)?$this->unit:$unit);
            $this->memcached->save($woeid,$this->apiYahooWeatherObject->get_lastResponse(),$this->ttl);
        }else{
            $this->lastInCache = "yes";
            $this->apiYahooWeatherObject->set_lastResponse($data);
        }
    }
    
    public function get_lastIncache(){
        return $this->lastInCache;
    }

    public function get_lastResponse($toJson = false) {
        return $this->apiYahooWeatherObject->get_lastResponse($toJson);
    }

    public function get_temperature($with_unit = false) {
        return $this->apiYahooWeatherObject->get_temperature($with_unit);
    }
    
    public function get_location(){
        return $this->apiYahooWeatherObject->get_location();
    }
    
    public function get_forecast(){
        return $this->apiYahooWeatherObject->get_forecast();
    }

}
