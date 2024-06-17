<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;
use Bitrix\Main\Page\Asset;

?>
<!DOCTYPE html>
<html lang="<?=LANGUAGE_ID?>">
<head>
    <meta charset="<?=LANG_CHARSET?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?$APPLICATION->ShowTitle()?></title>

    <?
    CJSCore::Init('jquery');
    $APPLICATION->ShowHead();

    Asset::getInstance()->addString('<link rel="preconnect" href="https://fonts.googleapis.com">');
    Asset::getInstance()->addString('<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>');
    Asset::getInstance()->addCss('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');
    
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/bootstrap.min.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/bootstrap_limitless.min.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/components.min.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/layout.min.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/icons/phosphor/styles.min.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/constants.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/custom.css");
    
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/jquery/jquery.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/bootstrap/bootstrap.bundle.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/plugins/forms/selects/select2.min.js");
    Asset::getInstance()->addJs("https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/i18n/ru.min.js", true);
    
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/app.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/dashboard.js");

    ?>
</head>

<body>
<!-- Page content -->
<div class="page-content bg-secondary">
        
        <!-- Content area -->
        <div class="content d-flex justify-content-center align-items-center overflow-auto">
        <?
            $APPLICATION->AuthForm('');
        ?>