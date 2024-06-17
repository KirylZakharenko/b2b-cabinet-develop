<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

global $APPLICATION;
$currentLang = $arResult['LANG'][$arResult['CURRENT_LANG']];
?>

<div id="multilang-choose">

    <span class="dropdown-toggle d-flex align-items-center multilang-choose__current-lang" data-bs-toggle="dropdown" aria-expanded="true">
         <span class="d-flex align-center">
           <? if ($currentLang['ICON']): ?>
               <span class="icon">
                    <img src="<?= $currentLang['ICON'] ?>" alt="lang-icon-<?= $arResult['CURRENT_LANG'] ?>">
                </span>
           <? endif; ?>
            <span class="ms-2"><?= $currentLang['TEXT'] ?></span>
        </span>
    </span>

    <div class="dropdown-menu dropdown-menu-end" data-popper-placement="bottom-start">
        <? foreach ($arResult['LANG'] as $id => $lang): ?>
            <?
            if ($id === $arResult['CURRENT_LANG']) {
                continue;
            }
            ?>
            <a href="javascript:void(0)" class="dropdown-item" data-type="lang" data-id="<?= $id ?>">
                <? if ($lang['ICON']): ?>
                    <span class="icon">
                            <img src="<?= $lang['ICON'] ?>" alt="lang-icon-<?= $id ?>">
                        </span>
                <? endif; ?>
                <span>
                    <?= $lang['TEXT'] ?>
                </span>
            </a>
        <? endforeach; ?>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        SMultilangChoose.init({
            component: 'multilang-choose',
            curPage: '<?=$APPLICATION->GetCurPageParam()?>',
            signedParameters: '<?= $this->getComponent()->getSignedParameters() ?>',
        });
    });
</script>