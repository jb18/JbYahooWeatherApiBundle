<?php
namespace Jb\ApiYahooWeatherBundle\Twig;

class ApiYahooWeatherTwigExtension extends \Twig_Extension {
    
    protected $ApiYahooWeatherService;
    
    public function __construct(\Jb\ApiYahooWeatherBundle\Services\ApiYahooWeatherService $ApiYahooWeatherService) {
        $this->ApiYahooWeatherService = $ApiYahooWeatherService;
    }
    
    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('jbAYW_get_temperature', array($this, 'get_temperature')),
            new \Twig_SimpleFunction('jbAYW_get_location', array($this, 'get_location'))
        );
    }
    
    public function get_temperature($with_units=false){
        return $this->ApiYahooWeatherService->get_temperature($with_units);
    }
    
    public function get_location(){
        return $this->ApiYahooWeatherService->get_location();
    }
    
    public function getName()
    {
        return 'jb_api_yahoo_weather_extension';
    }
}
