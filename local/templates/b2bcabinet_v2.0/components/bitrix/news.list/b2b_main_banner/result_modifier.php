<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

global $USER;

if (isset($arResult["ITEMS"]) && !empty($arResult["ITEMS"])) {
    $arBanners = [];
    foreach ($arResult["ITEMS"] as $index => $item) {
        if ($item["DETAIL_PICTURE"]["SRC"]) {
            $arBanners[$index]["SRC"] = $item["DETAIL_PICTURE"]["SRC"];
            $arBanners[$index]["HEIGHT"] = $item["DETAIL_PICTURE"]["HEIGHT"];
            $arBanners[$index]["WIDTH"] = $item["DETAIL_PICTURE"]["WIDTH"];
        }
        if (!empty($item["PROPERTIES"]["LINK"]["VALUE"])) {
            $arBanners[$index]["LINK"] = $item["PROPERTIES"]["LINK"]["VALUE"];
        }
    }

    $arResult["BANNERS"] = $arBanners;
}

