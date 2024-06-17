<?php

$arClasses = array (
  'Yandex\\Locator\\Api' => '/local/gadgets/sotbit/weather/classes/YandexLocator/Api.php',
  'Yandex\\Locator\\Exception' => '/local/gadgets/sotbit/weather/classes/YandexLocator/Exception.php',
  'Yandex\\Locator\\Exception\\CurlError' => '/local/gadgets/sotbit/weather/classes/YandexLocator/Exception/CurlError.php',
  'Yandex\\Locator\\Exception\\ServerError' => '/local/gadgets/sotbit/weather/classes/YandexLocator/Exception/ServerError.php',
  'Yandex\\Locator\\Response' => '/local/gadgets/sotbit/weather/classes/YandexLocator/Response.php',
  'Yandex\\Locator\\Transport' => '/local/gadgets/sotbit/weather/classes/YandexLocator/Transport.php',
  'Yandex\\Weather\\Weather' => '/local/gadgets/sotbit/weather/classes/YandexWeather/Weather.php'
);

CModule::AddAutoloadClasses(null, $arClasses);
	