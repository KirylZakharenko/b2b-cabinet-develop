<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

global $APPLICATION;
$currentLang = $arResult['LANG'][$arResult['CURRENT_LANG']];
?>


<a href="javascript:void(0)" class="navbar-nav-link btn-transparent text-white align-items-center p-2" data-bs-toggle="dropdown"
      title="<?= Loc::getMessage('SOTBIT_MULTILANG_CHOOSE_TITLE') ?>">
    <div class="d-flex align-items-center">
           <? if ($currentLang['ICON']): ?>
               <div class="icon">
                    <img src="<?= $currentLang['ICON'] ?>" alt="lang-icon-<?= $arResult['CURRENT_LANG'] ?>">
                </div>
           <? endif; ?>
      <div class="bx-user-info-name d-lg-inline-block ms-2"><?= $currentLang['TEXT'] ?></div>
    </div>
    <i class="ph ph-caret-down d-none d-sm-block p-1 ms-1"></i>
</a>
<div class="dropdown-menu dropdown-menu-end">
    <? foreach ($arResult['LANG'] as $id => $lang): ?>
        <?
        if ($id === $arResult['CURRENT_LANG']) {
            continue;
        }
        ?>
        <a href="javascript:void(0)" class="dropdown-item d-flex align-items-center" data-type="lang" data-id="<?= $id ?>">
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        SMultilangChoose.init({
            component: 'multilang-choose',
            curPage: '<?=$APPLICATION->GetCurPageParam()?>',
            signedParameters: '<?= $this->getComponent()->getSignedParameters() ?>',
        });
    });
</script>