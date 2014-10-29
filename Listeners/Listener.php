<?php

namespace Jb\YahooWeatherApiBundle\Listeners;

use Jb\YahooWeatherApiBundle\Services\YahooWeatherApi;

class Listener{

	public function onKernelResponse(\Symfony\Component\HttpKernel\Event\FilterResponseEvent $event){
		$event->getResponse()->headers->set('weather_hit_cache',YahooWeatherApi::$lastFromcache);	
	}

}