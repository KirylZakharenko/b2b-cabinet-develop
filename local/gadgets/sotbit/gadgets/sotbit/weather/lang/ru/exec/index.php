<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/xml.php');

use \Bitrix\Main\Page\Asset;
use \Yandex\Locator\Api;
use \Yandex\Weather\Weather;

Asset::getInstance()->addCss('/local/gadgets/sotbit/weather/styles.css');
Asset::getInstance()->addJs('https://api-maps.yandex.ru/2.0/?load=package.full&lang='.LANGUAGE_ID.'_'.mb_strtoupper(LANGUAGE_ID));

if (empty($arGadgetParams['API_KEY_LOCATOR']) && empty($arGadgetParams['API_KEY_WEATHER'])) {
    ShowError('Не указаны API ключи', 'validation-invalid-label');
    return;
}

$ip = \Bitrix\Main\Service\GeoIp\Manager::getRealIp();

$apiLocation = new Api($arGadgetParams['API_KEY_LOCATOR']);
$apiLocation->setIp($ip);

try {
    $apiLocation->load();
} catch(Exception $e) {
    ShowError($e->getMessage(), 'validation-invalid-label');
    return;
}

$responseLocation = $apiLocation->getResponse();
$currentLatitude = $responseLocation->getLatitude();
$currentLongitude = $responseLocation->getLongitude();

$cache = new CPageCache();

$cache_id = $currentLatitude . '-' . $currentLongitude;
if($arGadgetParams["CACHE_TIME"]>0 && !$cache->StartDataCache($arGadgetParams["CACHE_TIME"], $cache_id, "gdweather"))
	return;

$weather = new Weather($arGadgetParams['API_KEY_WEATHER'], $currentLatitude, $currentLongitude);

try {
    $weather->load();
} catch(Exception $e) {
    ShowError($e->getMessage(), 'validation-invalid-label');
    return;
}

$resWeather = $weather->getResponse();
$wind_dir = [
    'nw' => 'северо-западный',
    'n' => 'северный',
    'ne' => 'северо-восточный',
    'e' => 'восточный',
    'se' => 'юго-восточный',
    's' => 'южный',
    'sw' => 'юго-западный',
    'w' => 'западный',
    'c' => 'штиль',
]
?>
<h3 class="widget_weather-title"></h3>

<div class="widget_content widget_links">
    <div class="widget_weather-content">
        <div class="widget_weather-temp"><span class="t2"><?=$resWeather['fact']['temp']?>°</span></div>
        <div class="widget_weather-icons">
            <img src="https://yastatic.net/weather/i/icons/funky/dark/<?=$resWeather['fact']['icon']?>.svg" class="gdwico w-100 h-100" alt="">
        </div>
        <div class="widget_weather-text">
            <span class="display_block">
                Ветер: <?=$wind_dir[$resWeather['fact']['wind_dir']]?>, <?=$resWeather['fact']['wind_speed'] ? $resWeather['fact']['wind_speed'] . ' м/сек' : ''?>
            </span>
            <span class="display_block">
                Давление: <?=$resWeather['fact']['pressure_mm']?> мм.рт.ст.
            </span>
            <span class="display_block">
                Влажность: <?=$resWeather['fact']['humidity']?>%
            </span>
            <span class="display_block">
                Восход: <?=$resWeather['forecast']['sunrise']?>
            </span>
            <span class="display_block">
                Заход: <?=$resWeather['forecast']['sunset']?>
            </span>
        </div>
    </div>
</div>

<?if($arGadgetParams["SHOW_URL"]=="Y"):?>
<br />
<div class="d-flex justify-content-between">
    <a target="_blank" href="<?=htmlspecialcharsbx($resWeather['info']['url'])?>" class="main-link b2b-main-link">Подробнее</a>
    <a target="_blank" href="https://yandex.ru/pogoda/"class="main-link-yandex">
        <img src="/local/gadgets/sotbit/weather/images/yandex_logo_black.svg" alt="logo yandex weather"/>
    </a>
</div>
<?endif?>
<script>
    ymaps.ready(function(){
        var geolocation = ymaps.geolocation;
        document.querySelector('.widget_weather-title').innerText = geolocation.city;
    });    
</script>

<?$cache->EndDataCache();?>