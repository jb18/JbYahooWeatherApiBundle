JbYahooWeatherApiBundle
=======================

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

## Methods
