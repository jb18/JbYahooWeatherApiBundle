JbYahooWeatherApiBundle
=======================

## Versions
* 0.6 <-- Current
* 0.5.2
* 0.5.1
* 0.5.0

## Using and Setting Up
### Add in AppKernel.php

```php
public function registerBundles() {
  $bundles = array(
    new Jb\YahooWeatherApiBundle\JbYahooWeatherApiBundle()
  );
}
```

### Use

```php
$this->get('jb.yahoo_weather_api');
```

### Setting in config.yml
Two paramerters are available
```yaml
jb_yahoo_weather_api:
    city_woeid: <woeid>
    unit: (optional by default f) - f for fahreneit or c for celsius 
```
The woeid is a code which correponds to your city : 
choose one of these tools -> https://www.google.fr/search?q=weid+find&oq=weid+find&aqs=chrome..69i57.2639j0j7&sourceid=chrome&es_sm=122&ie=UTF-8#q=woeid&safe=off

## Activate memcached

### config.yml
```yaml
lsw_memcache:
    clients:
        jb_yahoo_weather_api:
            hosts:
              - { dsn: localhost, port: 11211 }
            options:
                prefix_key: "jbywa_"
```

And just have a look on debugbar to see if you have hits or write to memcached

## Methods

```php
   /**
   * Run query to Yahoo Weather Api with parameter given in Symfony2 config
   *  
   * @param string|null $woeid Woeid of the city to force query on specific city
   *
   * @return YahooWeatherApi Current object
   */
   public function query($woeid=null);
   
   /**
   * Get raw xml from Query done to Yahoo weather Api
   *
   * @return SimpleXMLElement Yahoo Weather Api Response
   */
   public function getXml();
   
   /**
   * Get Current condition datas : Attributes of node yweather:condition of xml
   * @see https://developer.yahoo.com/weather/#item
   * 
   * @return array All attributes of yweather:condition node
   */
   public function getCurrentCondition();
		
   /**
   * Get 5 days forecasts datas : Attributes of all nodes yweather:forecast of xml
   * @see https://developer.yahoo.com/weather/#item
   * 
   * @return array All attributes of yweather:forecast nodes
   */
   public function getForecast();

   /**
   * Get location datas : Attributes of node yweather:location of xml
   * @see https://developer.yahoo.com/weather/#channel
   * 
   * @return array All attributes of yweather:location node
   */
   public function getLocation();

   /**
   * Return temperature data
   * 
   * @return string temperature
   */
   public function temp();
		
   /**
   * Return city data
   * 
   * @return string city
   */
   public function city();
	
```
