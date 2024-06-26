<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

?>
<div class="list d-flex align-items-start flex-wrap">
    <?if($arParams["USE_SEARCH"] == "Y" || $arParams["USE_FILTER"] == "Y" || $arParams["SHOW_TAG_CLOUD"] == "Y"):?>
    <div class="offcanvas-lg offcanvas-size-lg offcanvas-end bg-transparent border-0 shadow-0 order-md-1 order-0 ps-lg-3 ps-0" 
         id="news__filter">
        <div class="sidebar-content">

            <? if ($arParams["USE_FILTER"] == "Y"): ?>
                <? $APPLICATION->IncludeComponent(
                    "bitrix:catalog.filter",
                    "",
                    array(
                        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                        "FILTER_NAME" => $arParams["FILTER_NAME"],
                        "FIELD_CODE" => $arParams["FILTER_FIELD_CODE"],
                        "PROPERTY_CODE" => $arParams["FILTER_PROPERTY_CODE"],
                        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                        "CACHE_TIME" => $arParams["CACHE_TIME"],
                        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                        "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                    ),
                    $component
                ); ?>
            <? endif ?>
            <? if ($arParams["SHOW_TAG_CLOUD"] == "Y"): ?>
                <? $APPLICATION->IncludeComponent(
                    "bitrix:search.tags.cloud",
                    "",
                    array(
                        "CHECK_DATES" => "Y",
                        "arrWHERE" => array("iblock_" . $arParams["IBLOCK_TYPE"]),
                        "arrFILTER" => array("iblock_" . $arParams["IBLOCK_TYPE"]),
                        "arrFILTER_iblock_" . $arParams["IBLOCK_TYPE"] => array($arParams["IBLOCK_ID"]),
                        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                        "CACHE_TIME" => $arParams["CACHE_TIME"],
                        "URL_SEARCH" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["search"],
                        "PAGE_ELEMENTS" => $arParams["TAGS_CLOUD_ELEMENTS"],
                        "PERIOD_NEW_TAGS" => $arParams["PERIOD_NEW_TAGS"],
                        "FONT_MAX" => $arParams["FONT_MAX"],
                        "FONT_MIN" => $arParams["FONT_MIN"],
                        "COLOR_NEW" => $arParams["COLOR_NEW"],
                        "COLOR_OLD" => $arParams["COLOR_OLD"],
                        "WIDTH" => $arParams["TAGS_CLOUD_WIDTH"],
                    ),
                    $component
                ); ?>
            <?endif;?>
        </div>

    </div>
    <?endif;?>
    <? switch ($arParams['NEWS_LIST_TEMPLATE']):
        case "horizontal":
            $news_list_template = "b2b_horizontal_news_list";
            break;
        case "grid":
            $news_list_template = "b2b_grid_news_list";
            break;
        default:
            $news_list_template = ".default";
    endswitch; ?>

        <? if ($arParams["USE_SEARCH"] == "Y"): ?>
            <div class="w-md-50 w-75 mb-4">
            <? $APPLICATION->IncludeComponent(
                "bitrix:search.form",
                "flat",
                array(
                    "PAGE" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["search"]
                ),
                $component
            ); ?>
            </div>
        <? endif ?>
        <div class="flex-lg-grow-1 ms-3">
            <div data-bs-toggle="offcanvas" data-bs-target="#news__filter" class="btn-filter btn btn-sm btn-icon btn-primary ">
                <i class="ph-funnel fs-5"></i>
            </div>
        </div>
        <div class="list-wrapper">
            <? $APPLICATION->IncludeComponent(
                "bitrix:news.list",
                $news_list_template,
                array(
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "NEWS_COUNT" => $arParams["NEWS_COUNT"],
                    "SORT_BY1" => $arParams["SORT_BY1"],
                    "SORT_ORDER1" => $arParams["SORT_ORDER1"],
                    "SORT_BY2" => $arParams["SORT_BY2"],
                    "SORT_ORDER2" => $arParams["SORT_ORDER2"],
                    "FIELD_CODE" => $arParams["LIST_FIELD_CODE"],
                    "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
                    "DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["detail"],
                    "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
                    "IBLOCK_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["news"],
                    "DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
                    "SET_TITLE" => $arParams["SET_TITLE"],
                    "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
                    "MESSAGE_404" => $arParams["MESSAGE_404"],
                    "SET_STATUS_404" => $arParams["SET_STATUS_404"],
                    "SHOW_404" => $arParams["SHOW_404"],
                    "FILE_404" => $arParams["FILE_404"],
                    "INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                    "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
                    "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
                    "PAGER_TITLE" => $arParams["PAGER_TITLE"],
                    "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
                    "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
                    "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
                    "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
                    "PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
                    "PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
                    "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                    "DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
                    "DISPLAY_NAME" => "Y",
                    "DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
                    "DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
                    "PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],
                    "ACTIVE_DATE_FORMAT" => $arParams["LIST_ACTIVE_DATE_FORMAT"],
                    "USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
                    "GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
                    "FILTER_NAME" => $arParams["FILTER_NAME"],
                    "HIDE_LINK_WHEN_NO_DETAIL" => $arParams["HIDE_LINK_WHEN_NO_DETAIL"],
                    "CHECK_DATES" => $arParams["CHECK_DATES"],
                ),
                $component
            ); ?>
        </div>
</div>
