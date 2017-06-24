# Ip2 PHP Library
Fast IP to Country for PHP

> <a href="http://www.ip2iq.com/free-open-ip-to-country-db/">Open IP2-Country DB</a> by <a href="http://www.ip2iq.com">IP2IQ</a> inside. Available free from www.ip2iq.com

#### Usage

```php
<?php
    require_once("Ip2.php");
    
    $ip2 = new \ip2iq\Ip2();
    $country_code = $ip2->country('8.8.8.8');
    //$countryCode === 'US'
?>
```

##### Composer

Add to the require object in your composer.json file:
```
"require": {
    "ip2iq/ip2" : "1.0.0"
}
```

```php
<?php
    require_once(dirname(__FILE__)."/vendor/autoload.php");
    
    $ip2 = new \ip2iq\Ip2();
    $country_code = $ip2->country('8.8.8.8');
    //$countryCode === 'US'
?>
```
