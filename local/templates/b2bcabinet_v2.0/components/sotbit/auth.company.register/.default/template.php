<?

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

Loc::loadMessages(__FILE__);

if ($arResult["CONFIRM_REGISTRATION"]) {
    ?>
    <p class="success-register-confirm"><? echo Loc::getMessage("CONFIRM_REGISTRATION") ?></p>
    <?php
    return;
}
if ($USER->IsAuthorized()) {
    ?>
    <p>
        <?= Loc::getMessage("MAIN_REGISTER_AUTH"); ?>
    </p>
    <?
    return;
}
?>

<div class="row company-register__success-form">
    <div class="col-md-12">
        <div class="card card-body text-center">
            <div class="mx-auto mb-3 pb-1">
                <svg width="73" height="72" viewBox="0 0 73 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_3305_29824)">
                        <path d="M36.5 72C56.3823 72 72.5 55.8823 72.5 36C72.5 16.1177 56.3823 0 36.5 0C16.6177 0 0.5 16.1177 0.5 36C0.5 55.8823 16.6177 72 36.5 72Z" fill="#32B76C"/>
                        <path d="M31.0437 54.6734L14.45 38.0797C14.225 37.8547 14.225 37.5172 14.45 37.2922L19.2313 32.5109C19.4563 32.2859 19.7938 32.2859 20.0188 32.5109L31.4375 43.9297L52.925 22.4422C53.15 22.2172 53.4875 22.2172 53.7125 22.4422L58.4937 27.2234C58.7188 27.4484 58.7188 27.7859 58.4937 28.0109L31.8313 54.6734C31.6063 54.8984 31.2687 54.8984 31.0437 54.6734Z" fill="white"/>
                    </g>
                    <defs>
                        <clipPath id="clip0_3305_29824">
                            <rect width="72" height="72" fill="white" transform="translate(0.5)"/>
                        </clipPath>
                    </defs>
                </svg>
            </div>

            <div class="text-center w-sm-75 mx-auto">
                <a href="<?= $arParams["AUTH_URL"] ?>"
                   class="d-block btn btn-primary mt-2"><?= Loc::getMessage("REGISTER_SUCCESS_BTN") ?>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="js_person_type auth-form">
    <div class="card">
        <div class="card-header card-pt-2 d-flex justify-content-between">
            <div>
                <h5 class="card-title mb-0 fw-bold"><?=GetMessage("AUTH_REGISTER")?></h5>
                <span class="card-subtitle"><?=GetMessage("AUTH_REGISTER_DESCRIPTION")?></span>
            </div>
            <?
            if (\Bitrix\Main\Loader::includeModule('sotbit.multilang')) {
                $APPLICATION->IncludeComponent(
                    "sotbit:multilang.choose",
                    "dark",
                    array(
                        "COMPONENT_TEMPLATE" => ".default",
                        "ADD_GET_PARAM" => "N",
                        "GET_PARAM" => "lang"
                    ),
                    false
                );
            }
            ?>
        </div>
        <div class="card-body pt-0">
            <div class="chouse-company mb-4">
                <div class="bitrix-error">
                    <? if (!empty($arParams["~AUTH_RESULT"])):
                        $text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]); ?>

                        <div class="alert alert-dismissible fade show <?=($arParams["~AUTH_RESULT"]["TYPE"] == "OK"? "alert-success":"alert-danger")?>">
                            <?=nl2br(htmlspecialcharsbx($text))?>
                        </div>
                    <?endif?>

                    <? if (!empty($arResult['ERRORS'])) {
                        foreach ($arResult['ERRORS'] as $errorMessage) {
                            if (mb_detect_encoding($errorMessage, 'UTF-8, CP1251') == 'UTF-8') {
                                $errorMessage = mb_convert_encoding($errorMessage, 'UTF-8');
                            }
                            ShowError($errorMessage, 'validation-invalid-label');
                        }
                    }
                    ?>

                    <? if ($arResult["USE_EMAIL_CONFIRMATION"] === "Y" && is_array($arParams["AUTH_RESULT"]) && $arParams["AUTH_RESULT"]["TYPE"] === "OK"): ?>
                        <div class="alert alert-success alert-dismissible fade show"><? echo GetMessage("AUTH_EMAIL_SENT") ?></div>
                    <? elseif ($arResult["USE_EMAIL_CONFIRMATION"] === "Y"): ?>
                        <div class="alert alert-warning alert-dismissible fade show"><? echo GetMessage("AUTH_EMAIL_WILL_BE_SENT") ?></div>
                    <? endif ?>
                </div>


                <? if ($arResult["PERSONAL_GROUPS_LIST"]): ?>
                    <label class="d-block mb-3 fw-bold"><?= $arResult["PERSONAL_GROUPS_LIST_TITLE"] ?: Loc::getMessage('PERSONAL_GROUPS_LIST_TITLE') ?></label>

                    <form id="person_group_check" method="post">
                        <? foreach ($arResult["PERSONAL_GROUPS_LIST"] as $groupId => $arGroup): ?>
                            <div class="form-check form-check-inline">
                                    <input type="radio"
                                            id="PERSONAL_GROUP_<?=$groupId?>"
                                            class="form-check-input"
                                            name="CHECKED_PERSON_GROUP"
                                            value="<?= $groupId ?>"
                                        <?
                                        if ($arGroup["CHECKED"] == "Y") {
                                            echo 'checked';
                                        }
                                        ?>
                                            data-fouc
                                            onchange="BX('person_group_check').submit();"
                                    >
                                    <label class="form-check-label" for="PERSONAL_GROUP_<?=$groupId?>"><?= $arGroup['VALUE']; ?>
                            </div>
                        <? endforeach; ?>
                    </form>
                <? endif; ?>

                <div class="form-group form-group-float">
                    <label class="d-block mb-3 fw-bold"><?= Loc::getMessage("AUTH_CHOOSE_USER_TYPE") ?></label>
                    <? foreach ($arResult['PERSON_TYPES'] as $key => $group): ?>
                        <div class="form-check form-check-inline">
                            <input type="radio"
                                    class="js_checkbox_person_type form-check-input REGISTER_WHOLESALER_TYPE"
                                    name="PERSON_TYPE"
                                    id="PERSON_TYPE_<?= $group['ID']; ?>"
                                    value="<?= $group['ID']; ?>"
                                <?
                                if (isset($arResult["VALUES"]['WHOLESALER_FIELDS'][$group['ID']])) {
                                    echo 'checked';
                                } elseif ($key == '0' && is_null($arResult["VALUES"]['WHOLESALER_FIELDS'][$group['ID']])) {
                                    echo 'checked';
                                }
                                ?>
                            >
                            <label class="form-check-label" for="PERSON_TYPE_<?= $group['ID']; ?>"><?= $group['NAME']; ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php foreach ($arResult['PERSON_TYPES'] as $key => $group): ?>
                <div class="js_person_type_block js_person_type_<?= $group['ID'] ?>"<? if ($key != 0): ?> style="display: none;"<? endif; ?>
                    <? if (isset($arResult["VALUES"]['WHOLESALER_FIELDS'][$group['ID']])) {
                        echo 'checked';
                    } elseif ($key == '0' && is_null($arResult["VALUES"]['WHOLESALER_FIELDS'][$group['ID']])) {
                        echo 'checked';
                    }
                    ?>
                >
                    <form id="company-register" method="post" class="flex-fill" onsubmit="sendForm(this); return false;"
                          enctype="multipart/form-data">
                        <input type="hidden" name="REGISTER_WHOLESALER[TYPE]" value="<?= $group['ID'] ?>">
                        <input type="hidden" id="CONFIRM_JOIN" name="CONFIRM_JOIN" value="">
                        <div class="mb-4">
                            <? if (Option::get("sotbit.auth", "LOGIN_EQ_EMAIL", "N", SITE_ID) !== 'Y'): ?>
                                    <div class="col-md-12">
                                        <label class="form-label">Login: <span class="req">*</span></label>
                                        <div class="form-control-feedback form-control-feedback-end mb-2">
                                            <input required
                                                    type="text"
                                                    name="REGISTER_WHOLESALER_USER[<?= $group['ID'] ?>][LOGIN]"
                                                    class="form-control"
                                                    placeholder="<?= Loc::getMessage('REGISTER_ENTER_LOGIN') ?>"
                                                <?= !empty($arResult["VALUES"]['WHOLESALER_FIELDS'][$group['ID']]['LOGIN']) ? "value=" . $arResult["VALUES"]['WHOLESALER_FIELDS'][$group['ID']]['LOGIN'] . "" : "" ?>
                                            >
                                            <div class="form-control-feedback-icon">
                                                <i class="ph-user"></i>
                                            </div>
                                        </div>
                                    </div>
                            <? endif; ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">E-mail: <span class="req">*</span></label>
                                    <div class="form-control-feedback form-control-feedback-end mb-2">
                                        <input required
                                                type="email"
                                                name="REGISTER_WHOLESALER_USER[<?= $group['ID'] ?>][EMAIL]"
                                                class="form-control"
                                                placeholder="<?= Loc::getMessage('REGISTER_ENTER_EMAIL') ?>"
                                            <?= !empty($arResult["VALUES"]['WHOLESALER_FIELDS'][$group['ID']]['EMAIL']) ? "value=" . $arResult["VALUES"]['WHOLESALER_FIELDS'][$group['ID']]['EMAIL'] . "" : "" ?>
                                        >
                                        <div class="form-control-feedback-icon">
                                            <i class="ph-at"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label"><?= Loc::getMessage("REGISTER_FIELD_NAME") ?>: <span class="req">*</span> </label>
                                    <div class="form-control-feedback form-control-feedback-end mb-2">
                                        <input required
                                                type="text"
                                                name="REGISTER_WHOLESALER_USER[<?= $group['ID'] ?>][NAME]"
                                                class="form-control"
                                                placeholder="<?= Loc::getMessage('REGISTER_ENTER_FIRST_NAME') ?>"
                                            <?= !empty($arResult["VALUES"]['WHOLESALER_FIELDS'][$group['ID']]['NAME']) ? "value=" . $arResult["VALUES"]['WHOLESALER_FIELDS'][$group['ID']]['NAME'] . "" : "" ?>
                                        >
                                        <div class="form-control-feedback-icon">
                                            <i class="ph-user-circle"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><?= Loc::getMessage("REGISTER_FIELD_LAST_NAME") ?>: <span class="req">*</span>
                                    </label>
                                    <div class="form-control-feedback form-control-feedback-end mb-2">
                                        <input required
                                                type="text"
                                                name="REGISTER_WHOLESALER_USER[<?= $group['ID'] ?>][LAST_NAME]"
                                                class="form-control"
                                                placeholder="<?= Loc::getMessage('REGISTER_ENTER_SECOND_NAME') ?>"
                                            <?= !empty($arResult["VALUES"]['WHOLESALER_FIELDS'][$group['ID']]['LAST_NAME']) ? "value=" . $arResult["VALUES"]['WHOLESALER_FIELDS'][$group['ID']]['LAST_NAME'] . "" : "" ?>
                                        >
                                        <div class="form-control-feedback-icon">
                                            <i class="ph-user-circle"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <? if (Option::get("main", "new_user_phone_auth", "N", SITE_ID) === 'Y'): ?>
                                <div class="col-md-12">
                                    <label class="form-label"><?= Loc::getMessage("REGISTER_FIELD_PHONE_TO_REGISTER") ?>
                                        : <?= Option::get("main", "new_user_phone_required", "N", SITE_ID) === 'Y' ? '<span class="req">*</span>' : "" ?></label>
                                    <div class="form-control-feedback form-control-feedback-end mb-2">
                                        <input <?= Option::get("main", "new_user_phone_required", "N", SITE_ID) === 'Y' ? 'required' : '' ?>
                                                type="text"
                                                name="REGISTER_WHOLESALER_USER[<?= $group['ID'] ?>][PHONE_NUMBER]"
                                                class="form-control"
                                                placeholder="<?= Loc::getMessage('REGISTER_FIELD_PHONE_TO_REGISTER') ?>"
                                        >
                                    </div>
                                </div>
                            <? endif; ?>
                            <?
                            foreach ($arResult["OPT_FIELDS"][$group['ID']] as $FIELD) {
                                if (in_array($FIELD,
                                    ['PASSWORD', 'CONFIRM_PASSWORD', 'EMAIL', 'LOGIN', 'NAME', 'LAST_NAME'])) {
                                    continue;
                                } elseif ($arResult['OPT_FIELDS_FULL'][$FIELD]["EDIT_IN_LIST"] == "N") {
                                    continue;
                                } else {
                                    ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="form-label"><?
                                                if (!empty($arResult['OPT_FIELDS_FULL'][$FIELD]["EDIT_FORM_LABEL"])) {
                                                    echo $arResult['OPT_FIELDS_FULL'][$FIELD]["EDIT_FORM_LABEL"];
                                                } else {
                                                    echo Loc::getMessage("REGISTER_FIELD_" . $FIELD);
                                                } ?>:
                                                <?= in_array($FIELD,
                                                    is_array( $arResult['OPT_FIELDS_REQUIRED'][$group['ID']]) ?  $arResult['OPT_FIELDS_REQUIRED'][$group['ID']] : []) ? '<span class="req">*</span>' : '' ?></label>
                                            <div class="form-group form-group-feedback form-group-feedback-right">
                                                <? if ($FIELD === 'PERSONAL_PHOTO' || $FIELD === 'WORK_LOGO'): ?>
                                                    <input type="file" class="form-control"
                                                            name="REGISTER_WHOLESALER_FILES_<?= $FIELD ?>"/>
                                                    <input type="hidden"
                                                            name="REGISTER_WHOLESALER_USER[<?= $group['ID'] ?>][<?= $FIELD ?>]"/>
                                                <? elseif ($FIELD === 'PERSONAL_BIRTHDAY'): ?>
                                                    <input type="date"
                                                            class="form-control"
                                                            name="REGISTER_WHOLESALER_USER[<?= $group['ID'] ?>][<?= $FIELD ?>]"
                                                        <?
                                                        $fieldValue = '';
                                                        if (!empty($arResult["VALUES"]['FIELDS'][$FIELD])) {
                                                            $fieldValue = $arResult["VALUES"]['FIELDS'][$FIELD];
                                                        } elseif ($arResult["VALUES"]['WHOLESALER_FIELDS'][$group['ID']][$FIELD]) {
                                                            $fieldValue = $arResult["VALUES"]['WHOLESALER_FIELDS'][$group['ID']][$FIELD];
                                                        }

                                                        if (!empty($fieldValue)) {
                                                            echo 'value="' . $fieldValue . '"';
                                                        }
                                                        ?>
                                                            autocomplete="off"
                                                    >
                                                <? else: ?>
                                                    <input <?= in_array($FIELD,
                                                        is_array( $arResult['OPT_FIELDS_REQUIRED'][$group['ID']]) ?  $arResult['OPT_FIELDS_REQUIRED'][$group['ID']] : []) ? 'required' : '' ?>
                                                            type="text" class="form-control"
                                                            name="REGISTER_WHOLESALER_USER[<?= $group['ID'] ?>][<?= $FIELD ?>]"
                                                            maxlength="50"
                                                        <?
                                                        $fieldValue = '';
                                                        if (!empty($arResult["VALUES"]['FIELDS'][$FIELD])) {
                                                            $fieldValue = $arResult["VALUES"]['FIELDS'][$FIELD];
                                                        } elseif ($arResult["VALUES"]['WHOLESALER_FIELDS'][$group['ID']][$FIELD]) {
                                                            $fieldValue = $arResult["VALUES"]['WHOLESALER_FIELDS'][$group['ID']][$FIELD];
                                                        }

                                                        if (!empty($fieldValue)) {
                                                            echo 'value="' . $fieldValue . '"';
                                                        }
                                                        ?>
                                                            autocomplete="off"
                                                            placeholder="<?= Loc::getMessage('REGISTER_FIELD_PLACEHOLDER', ['#FIELD#' => Loc::getMessage('REGISTER_FIELD_' . $FIELD)]) ?>"
                                                    >
                                                <? endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?
                                }
                            }
                            ?>
                        </div>

                        <? if (isset($arResult['OPT_ORDER_FIELDS'][$group['ID']]) && !empty($arResult['OPT_ORDER_FIELDS'][$group['ID']])): ?>
                            <div class="mb-4">
                                <label class="d-block mb-3 fw-bold"><?= Loc::getMessage("AUTH_BLOCK_WHOLESALER_ORDER_TITLE") ?></label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <? foreach ($arResult['OPT_ORDER_FIELDS'][$group['ID']] as $order): ?>
                                            <? if ($order['NAME']): ?>
                                                <label 
                                                    for="sppd-property-<?=$order['ID']?>"
                                                    class="form-label"><?= $order['NAME'] . ":" ?>
                                                    <?= $order["REQUIRED"] == "Y" ? " <span class='req'>*</span>" : "" ?></label>
                                                <? if ($order["TYPE"] == "ENUM" && $order["VARIANTS"]): ?>
                                                    <div class="mb-2">
                                                        <select
                                                                class="form-control select"
                                                                data-minimum-results-for-search="Infinity"
                                                                name="REGISTER_WHOLESALER_OPT[<?= $group['ID']; ?>][<?= $order['CODE'] ?>]<?= $order['MULTIPLE'] == "Y" ? "[]" : "" ?>"
                                                                id="WHOLESALER_<?= $order['CODE'] ?>"
                                                                data-minimum-results-for-search="Infinity"
                                                            <?= $order['MULTIPLE'] == "Y" ? "multiple" : "" ?>
                                                            <?= $order['REQUIRED'] == 'Y' ? 'required' : '' ?>
                                                        >
                                                            <? if (!$order["DEFAULT_VALUE"]): ?>
                                                                <option disabled
                                                                        selected><?= Loc::getMessage("REGISTER_FIELD_TYPE_ENUM") ?></option>
                                                            <? endif; ?>
                                                            <? foreach ($order["VARIANTS"] as $variant): ?>
                                                                <option
                                                                        value="<?= $variant["ID"] ?>"
                                                                    <?
                                                                    if (($order["MULTIPLE"] == "Y" && in_array($variant["ID"],
                                                                                $order["DEFAULT_VALUE"])) || ($variant["ID"] == $order["DEFAULT_VALUE"])) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>
                                                                ><?= $variant["NAME"] ?></option>
                                                            <? endforeach; ?>
                                                        </select>
                                                    </div>
                                                <? elseif ($order["TYPE"] == "LOCATION"): ?>
                                                    <?
                                                    $locationTemplate = "";
                                                    $locationClassName = 'location-block-wrapper';
                                                    $locationClassName .= ' location-block-wrapper-delimeter';

                                                    CSaleLocation::proxySaleAjaxLocationsComponent(
                                                        array(
                                                            "AJAX_CALL" => "N",
                                                            'CITY_OUT_LOCATION' => 'Y',
                                                            'COUNTRY_INPUT_NAME' => "REGISTER_WHOLESALER_OPT[" . $group['ID'] . "][" . $order['CODE'] . '_COUNTRY' . "]",
                                                            'CITY_INPUT_NAME' => "REGISTER_WHOLESALER_OPT[" . $group['ID'] . "][" . $order['CODE'] . "]",
                                                            'LOCATION_VALUE' => "",
                                                        ),
                                                        array(),
                                                        $locationTemplate,
                                                        true,
                                                        'location-block-wrapper mb-2'
                                                    );
                                                    ?>
                                                <? else: ?>
                                                    <? if ($order["MULTIPLE"] == 'Y'): ?>
                                                        <div class="mb-2 multiple-props">
                                                            <?
                                                            $valueMultiProp = '';
                                                            if(!empty($arResult["VALUES"]['WHOLESALER_ORDER_FIELDS'][$group['ID']][$order['CODE']])){
                                                                $valueMultiProp = $arResult["VALUES"]['WHOLESALER_ORDER_FIELDS'][$group['ID']][$order['CODE']];
                                                                $valueMultiProp = is_array($valueMultiProp) ? $valueMultiProp : [$valueMultiProp];
                                                            }

                                                            if (is_array($valueMultiProp)): 
                                                                foreach($valueMultiProp as $key => $item): ?>
                                                                <div class="form-control-multiple-wrap">
                                                                    <input type="text"
                                                                            class="form-control"
                                                                            placeholder="<?= $order['NAME'] ?><?= $order['DESCRIPTION'] ? " " . $order['DESCRIPTION'] : '' ?>"
                                                                            name="REGISTER_WHOLESALER_OPT[<?= $group['ID']; ?>][<?= $order['CODE'] ?>][]"
                                                                        <?= $order['REQUIRED'] == 'Y' ? 'required' : '' ?>
                                                                            maxlength="<?=
                                                                            !empty($order['SETTINGS']['MAXLENGTH']) ? $order['SETTINGS']['MAXLENGTH'] :
                                                                                (!empty($order['SETTINGS']['SIZE']) ? $order['SETTINGS']['SIZE'] : 50)
                                                                            ?>"
                                                                            minlength="<?= !empty($order['SETTINGS']['MINLENGTH']) ? $order['SETTINGS']['MINLENGTH'] : 0 ?>"
                                                                        <?= !empty($item) ? 'value="' . $item . '"' : '' ?>
                                                                        <?= $order['SETTINGS']['PATTERN'] ? "pattern='" . $order['SETTINGS']['PATTERN'] . "'" : "" ?>
                                                                            id="WHOLESALER_<?= $order['CODE'] ?>"
                                                                        <?= $order['DESCRIPTION'] ? "title='" . $order['DESCRIPTION'] . "'" : "" ?>
                                                                    >
                                                                    <?if ($key !== 0): ?>
                                                                        <div class="form-control-multiple position-absolute end-0 top-50 translate-middle-y me-1" 
                                                                             onclick="hideBlock(this)">
                                                                            <button 
                                                                                class="form-control-multiple-ic btn btn-sm btn-icon btn-link text-muted" 
                                                                                type="button">
                                                                                    <i class="ph-x fs-base"></i>
                                                                            </button>
                                                                        </div>
                                                                    <?endif; ?>
                                                                </div>
                                                                <? endforeach; ?>
                                                            <? else: ?>
                                                                <div class="form-control-multiple-wrap">
                                                                    <input <?=$order["TYPE"] != "NUMBER" ? ($order["CODE"] == "EMAIL" ? 'type="email"' : 'type="text"') : 'type="number"'?>
                                                                            class="form-control"
                                                                            placeholder="<?= $order['NAME'] ?><?= $order['DESCRIPTION'] ? " " . $order['DESCRIPTION'] : '' ?>"
                                                                            name="REGISTER_WHOLESALER_OPT[<?= $group['ID']; ?>][<?= $order['CODE'] ?>][]"
                                                                        <?= $order['REQUIRED'] == 'Y' ? 'required' : '' ?>
                                                                            maxlength="<?=
                                                                            !empty($order['SETTINGS']['MAXLENGTH']) ? $order['SETTINGS']['MAXLENGTH'] :
                                                                                (!empty($order['SETTINGS']['SIZE']) ? $order['SETTINGS']['SIZE'] : 50)
                                                                            ?>"
                                                                            minlength="<?= !empty($order['SETTINGS']['MINLENGTH']) ? $order['SETTINGS']['MINLENGTH'] : 0 ?>"
                                                                        <?= !empty($val) ? 'value="' . $val . '"' : '' ?>
                                                                        <?= $order['SETTINGS']['PATTERN'] ? "pattern='" . $order['SETTINGS']['PATTERN'] . "'" : "" ?>
                                                                            id="WHOLESALER_<?= $order['CODE'] ?>"
                                                                        <?= $order['DESCRIPTION'] ? "title='" . $order['DESCRIPTION'] . "'" : "" ?>
                                                                    >
                                                                </div>
                                                            <?endif; ?>
                                                            <button
                                                                    type="button"
                                                                    class="btn"
                                                                    data-add-type=<?=$order["TYPE"] != "NUMBER" ? ($order["CODE"] == "EMAIL" ? '"email"' : '"text"') : '"number"'?>
                                                                    data-add-placeholder="<?= $order['NAME'] ?><?= $order['DESCRIPTION'] ? " " . $order['DESCRIPTION'] : '' ?>"
                                                                    data-add-name="REGISTER_WHOLESALER_OPT[<?= $group['ID']; ?>][<?= $order['CODE'] ?>][]"
                                                                    data-add-maxlength="<?=
                                                                    !empty($order['SETTINGS']['MAXLENGTH']) ? $order['SETTINGS']['MAXLENGTH'] :
                                                                        (!empty($order['SETTINGS']['SIZE']) ? $order['SETTINGS']['SIZE'] : 50)
                                                                    ?>"
                                                                    data-add-minlength="<?= !empty($order['SETTINGS']['MINLENGTH']) ? $order['SETTINGS']['MINLENGTH'] : 0 ?>"
                                                            >
                                                                <?= Loc::getMessage('REGISTER_BTN_MULTIPLE') ?>
                                                            </button>
                                                        </div>
                                                    <? else: ?>
                                                        <div class="mb-2">
                                                            <? switch($order["TYPE"]) {
                                                                case 'FILE': 
                                                                    echo CFile::InputFile("REGISTER_WHOLESALER_FILES_" . $order['CODE'], 20,
                                                                                            null, false,
                                                                                            0, "IMAGE",
                                                                                            "class='form-control border' " . ($order['MULTIPLE'] === 'Y' ? 'multiple' : '')
                                                                                        ); 
                                                                    break;
                                                                case 'CHECKBOX':
                                                                case 'Y/N':
                                                                    ?>
                                                                    <input
                                                                            class="form-check-input"
                                                                            id="sppd-property-<?= $order['ID'] ?>"
                                                                            type="checkbox"
                                                                            name="REGISTER_WHOLESALER_OPT[<?= $group['ID']; ?>][<?= $order['CODE'] ?>]"
                                                                            value="Y"
                                                                        <?= ($order["REQUIRED"] == "Y" ? "required" : "") ?>
                                                                        <?= ($order["DEFAULT_VALUE"] == "Y") ? "checked" : "" ?>
                                                                    />
                                                                    <?
                                                                    break;
                                                                default:
                                                                    ?>
                                                                    <input
                                                                        <?=$order["TYPE"] != "NUMBER" ? ($order["CODE"] == "EMAIL" ? 'type="email"' : 'type="text"') : 'type="number"'?>
                                                                            class="form-control"
                                                                            placeholder="<?= $order['NAME'] ?><?= $order['DESCRIPTION'] ? " " . $order['DESCRIPTION'] : '' ?>"
                                                                            name="REGISTER_WHOLESALER_OPT[<?= $group['ID']; ?>][<?= $order['CODE'] ?>]"
                                                                        <?= $order['REQUIRED'] == 'Y' ? 'required' : '' ?>
                                                                            maxlength="<?=
                                                                            !empty($order['SETTINGS']['MAXLENGTH']) ? $order['SETTINGS']['MAXLENGTH'] :
                                                                                (!empty($order['SETTINGS']['SIZE']) ? $order['SETTINGS']['SIZE'] : 50)
                                                                            ?>"
                                                                            minlength="<?= !empty($order['SETTINGS']['MINLENGTH']) ? $order['SETTINGS']['MINLENGTH'] : 0 ?>"
                                                                        <?= !empty($arResult["VALUES"]['WHOLESALER_ORDER_FIELDS'][$group['ID']][$order['CODE']]) ? 'value="' . $arResult["VALUES"]['WHOLESALER_ORDER_FIELDS'][$group['ID']][$order['CODE']] . '"' : '' ?>
                                                                        <?= $order['SETTINGS']['PATTERN'] ? "pattern='" . $order['SETTINGS']['PATTERN'] . "'" : "" ?>
                                                                            id="WHOLESALER_<?= $order['CODE'] ?>"
                                                                        <?= $order['DESCRIPTION'] ? "title='" . $order['DESCRIPTION'] . "'" : "" ?>
                                                                    >
                                                                    <?
                                                            }
                                                            ?>
                                                        </div>
                                                    <? endif; ?>
                                                <? endif; ?>
                                            <? endif; ?>
                                        <? endforeach; ?>
                                        <? if (!empty($order['CODE']['FILE']) && $order['CODE']['FILE'] == 'Y'): ?>
                                            <?
                                            $APPLICATION->IncludeComponent(
                                                "bitrix:main.file.input",
                                                "auth_drag_n_drop",
                                                [
                                                    "INPUT_NAME" => "FILES",
                                                    "MULTIPLE" => "Y",
                                                    "MODULE_ID" => "main",
                                                    "MAX_FILE_SIZE" => "",
                                                    "ALLOW_UPLOAD" => "F",
                                                    "ALLOW_UPLOAD_EXT" => "",
                                                    "TAB_ID" => $group['ID']
                                                ],
                                                false
                                            );
                                            ?>
                                        <? endif; ?>
                                    </div>
                                </div>
                            </div>
                        <? endif; ?>
                        <? if (isset($arResult['COMPANY_USER_FIELDS'][$group['ID']]) && !empty($arResult['COMPANY_USER_FIELDS'][$group['ID']])): ?>
                            <div class="mb-4">
                                <label class="d-block mb-3 fw-bold"><?= Option::get('sotbit.auth', 'COMPANY_USER_FIELDS_TITLE_' . $group['ID'], '', SITE_ID) ?: Loc::getMessage("AUTH_BLOCK_COMPANY_USER_PROPS_TITLE") ?></label>

                                <? foreach ($arResult['COMPANY_USER_FIELDS'][$group['ID']] as $arCompanyUserField): ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="form-label"><?= $arCompanyUserField['EDIT_FORM_LABEL']; ?>
                                                : <?= $arCompanyUserField["MANDATORY"] == "Y" ? " <span class='req'>*</span>" : "" ?></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <? $APPLICATION->IncludeComponent(
                                            "bitrix:system.field.edit",
                                            $arCompanyUserField["USER_TYPE"]["USER_TYPE_ID"],
                                            array(
                                                "bVarsFromForm" => false,
                                                "arUserField" => $arCompanyUserField
                                            ),
                                            null, array("HIDE_ICONS" => "Y"));
                                        ?>
                                    </div>
                                <? endforeach; ?>
                            </div>
                        <? endif; ?>
                        <div class="mb-4">
                        <label class="d-block mb-3 fw-bold"><?= Loc::getMessage('AUTH_SAVE_OF_DATA') ?></label>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <? foreach ($arResult["SHOW_FIELDS"] as $FIELD) {
                                        if ($FIELD == 'PASSWORD' || $FIELD == 'CONFIRM_PASSWORD') {
                                            ?>
                                            <label class="form-label"><?= Loc::getMessage("REGISTER_FIELD_" . $FIELD) ?>:
                                                <span class="req">*</span></label>
                                            <div class="form-control-feedback form-control-feedback-end mb-2">
                                                <input required type="password" class="form-control badge-indicator-absolute"
                                                        placeholder="<?= Loc::getMessage("REGISTER_PLACEHOLDER_" . $FIELD) ?>"
                                                        name="REGISTER[<?= $FIELD ?>]" maxlength="50"
                                                        value=""
                                                        autocomplete="off">
                                                <div class="form-control-feedback-icon">
                                                    <i class="ph-lock-key"></i>
                                                </div>
                                            </div>
                                            <div class="invalid-feedback"><?= Loc::getMessage("REGISTER_NOTE_" . $FIELD) ?></div>
                                            <? if ($FIELD == 'PASSWORD') {?>
                                                <span class="d-block form-text text-muted mb-2"><?= $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"] ?></span>
                                            <?
                                            }
                                        }
                                    }
                                    ?>
                                    <? if ($arResult["USE_CAPTCHA"] == "Y"): ?>
                                        <input type="hidden" name="captcha_sid" id="captcha_sid"
                                                value="<?= $arResult["CAPTCHA_CODE"] ?>"/>

                                        <label class="form-label">
                                            <?= Loc::getMessage("REGISTER_CAPTCHA_PROMT") ?>: <span>*</span>
                                        </label>
                                        <div class="password_recovery-captcha_wrap d-flex align-items-center mb-2">
                                            <div class="bx-captcha">
                                                <img src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["CAPTCHA_CODE"] ?>"
                                                        width="180" height="40" alt="CAPTCHA">
                                            </div>
                                            <div class="form-group feedback_block__captcha_reload" role="button"
                                                    onclick="reloadCaptcha(this, '<?= SITE_DIR ?>');return false;">
                                                    <i class="ph-arrows-counter-clockwise icon_refresh"></i>
                                            </div>
                                        </div>


                                        <div class="password_recovery-captcha">
                                            <div class="form-group form-group-feedback form-group-feedback-right password_recovery-captcha_input">
                                                <input type="text" class="form-control" name="captcha_word"
                                                        maxlength="50" autocomplete="off"
                                                        placeholder="<?= Loc::getMessage("CAPTCHA_REGF_PROMT") ?>">
                                            </div>
                                        </div>
                                        <div class="bitrix-error"></div>
                                    <? endif ?>


                                    <div class="d-flex align-items-center mt-3">
                                        <input name="UF_CONFIDENTIAL"
                                                type="hidden"
                                                value="Y"/>

                                        <? $APPLICATION->IncludeComponent(
                                            "bitrix:main.userconsent.request",
                                            "b2bcabinet",
                                            array(
                                                "ID" => COption::getOptionString("main", "new_user_agreement",
                                                    "") ?: \COption::GetOptionString("sotbit.b2bcabinet",
                                                    "AGREEMENT_ID"),
                                                "IS_CHECKED" => "Y",
                                                "AUTO_SAVE" => "Y",
                                                "ORIGINATOR_ID" => $arResult["AGREEMENT_ORIGINATOR_ID"],
                                                "ORIGIN_ID" => $arResult["AGREEMENT_ORIGIN_ID"],
                                                "INPUT_NAME" => $arResult["AGREEMENT_INPUT_NAME"],
                                                "COMPOSITE_FRAME_MODE" => "A",
                                                "COMPOSITE_FRAME_TYPE" => "AUTO",
                                                "IS_LOADED" => "N",
                                                "REPLACE" => array(
                                                    "button_caption" => GetMessage("AUTH_REGISTER"),
                                                    "fields" => array(
                                                        rtrim(GetMessage("AUTH_NAME"), ":"),
                                                        rtrim(GetMessage("AUTH_LAST_NAME"), ":"),
                                                        rtrim(GetMessage("AUTH_LOGIN_MIN"), ":"),
                                                        rtrim(GetMessage("AUTH_PASSWORD_REQ"), ":"),
                                                        rtrim(GetMessage("AUTH_EMAIL"), ":"),
                                                    )
                                                ),
                                            )
                                        ); ?>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <input type="hidden" name="sotbit_auth_register"
                               value="<?= Loc::getMessage('AUTH_REGISTER_WORD') ?>"/>
                        <div class="btnBlock">
                            <button type="submit" class="btn btn-primary">
                                <?= Loc::getMessage('AUTH_REGISTER') ?>
                            </button>
                            <a href="?register=no" class="btn btn-link">
                                <?= Loc::getMessage('AUTH_AUTH') ?>
                            </a>
                        </div>

                    </form>
                </div>
            <? endforeach; ?>
        </div>
    </div>
</div>
<script>
    window.arParams = "<?=$this->__component->getSignedParameters();?>";
    var confirmModerationMsg = '<?=Loc::getMessage("REGISTER_CONFIRM_MODERATION_MESSAGE")?>',
        companyRegisterAgreementInput = '<?=$arResult["AGREEMENT_INPUT_NAME"]?>';
    var siteDir = '/';
</script>

