<?php

namespace Jb\YahooWeatherApiBundle\Tests\Services;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class YahooWeatherApiTest extends WebTestCase
{
    public function testCallFromParameter(){
        $client = self::createClient();
        $service= $client->getContainer()->get('jb.yahoo_weather_api');
        
        $returnValues = $service->query();
        
        $this->all($returnValues);
        
    }
    
    public function testCallFromParameterGiven(){
        $client = self::createClient();
        $service= $client->getContainer()->get('jb.yahoo_weather_api');
        
        $returnValues = $service->query("123456");  
        
        $this->all($returnValues);       
    }
    
    protected function all($response){
        $xml = $response->getXml();
        
        $testXml = (count($xml->channel->xpath('yweather:atmosphere')) > 0) ? true : false;
        $this->asserttrue($testXml);

        $testCurrentCondition = (count($response->getCurrentCondition()) > 0) ? true : false;
        $this->asserttrue($testCurrentCondition);
        
        $testForecast = (count($response->getForecast()) > 0) ? true : false;
        $this->asserttrue($testForecast);
        
        $testLocation = (count($response->getLocation()) > 0) ? true : false;
        $this->asserttrue($testLocation);
                
        $this->asserttrue(gettype($response->city())=="string");
                
        $this->asserttrue(gettype($response->temp())=="double");
    }
    
}
