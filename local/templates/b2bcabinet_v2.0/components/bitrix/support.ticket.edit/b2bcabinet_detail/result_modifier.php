<?
use Bitrix\Main\UserTable,
	Bitrix\Main\Page\Asset;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$arResult['RESPONSIBLE'] = '';
$arResult["TICKET"]["RESPONSIBLE_USER_ID"] = intval($arResult["TICKET"]["RESPONSIBLE_USER_ID"]);
$users = [];

Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/assets/js/plugins/editor/trumbowyg/trumbowyg.min.js');
Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/assets/js/plugins/editor/trumbowyg/lang/ru.min.js');
Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/assets/js/pages/editor_trumbowyg.js');

$arResult['SUPPORT_PAGE'] = false;

$arResult['DICTIONARY_MARK'] = [
	'ARRANGES' => [
		'ICON' => 'ph-smiley',
		'COLOR' => 'var(--success)'
	],
	'NOT_SURE' => [
		'ICON' => 'ph-smiley-meh',
		'COLOR' => 'var(--warning-500)'
	],
	'NOT_FULL' => [
		'ICON' => 'ph-smiley-meh',
		'COLOR' => 'var(--danger-300)'
	],
	'NOT_SATISFIED' => [
		'ICON' => 'ph-smiley-sad',
		'COLOR' => 'var(--danger-400)'
	],
	'NOT_SUPPORT_TEAM' => [
		'ICON' => 'ph-smiley-meh',
		'COLOR' => 'var(--danger-200)'
	],
	'NOT_PRODUCTS' => [
		'ICON' => 'ph-smiley-sad',
		'COLOR' => 'var(--primary-hover)'
	]
];

if(strpos($APPLICATION->GetCurDir(),'support') !== false)
{
	$arResult['SUPPORT_PAGE'] = true;
}
if($arResult['MESSAGES'])
{
	foreach ($arResult['MESSAGES'] as $i => $message)
	{
		$arResult['MESSAGES'][$i]['MESSAGE'] = $arResult['MESSAGES'][$i]['~MESSAGE'];

		$arResult['MESSAGES'][$i]['MESSAGE'] = str_replace([
			'<quote>',
			'</quote>',
			'<br>'
		], [
			'<div class="quote"><div class="quote-text">',
			'</div></div>',
			''
		],
			$arResult['MESSAGES'][$i]['MESSAGE']);
		$users[$message['OWNER_USER_ID']] = $message['OWNER_USER_ID'];
	}
}

if($arResult["TICKET"]["RESPONSIBLE_USER_ID"] > 0)
{
	$users[$arResult["TICKET"]["RESPONSIBLE_USER_ID"]] = $arResult["TICKET"]["RESPONSIBLE_USER_ID"];
}
if($users)
{
	$rs = UserTable::getList([
		'filter' => ['ID' => $users],
		'select'
		=> [
			'ID',
			'NAME',
			'LAST_NAME',
			'PERSONAL_PHOTO'
		]
	]);
	while ($user = $rs->fetch())
	{
		if($arResult["TICKET"]["RESPONSIBLE_USER_ID"] > 0 && $arResult["TICKET"]["RESPONSIBLE_USER_ID"] == $user['ID'])
		{
			$arResult['RESPONSIBLE'] = trim($user['NAME'] . ' ' . $user['LAST_NAME']);
		}
		if(!$user['PERSONAL_PHOTO'])
		{
			continue;
		}
		if($arResult['MESSAGES'])
		{
			foreach ($arResult['MESSAGES'] as $i => $message)
			{
				if($message['OWNER_USER_ID'] == $user['ID'])
				{
					$arResult['MESSAGES'][$i]['PERSONAL_PHOTO'] = CFile::ResizeImageGet($user['PERSONAL_PHOTO'],
						[
							'width' => 55,
							'height' => 55
						], BX_RESIZE_IMAGE_EXACT);
				}
				$users[$message['OWNER_USER_ID']] = $message['OWNER_USER_ID'];
			}
		}
	}
}

$arResult['DEFAULT_MARK'] = 0;
$obMark = CTicketDictionary::GetList('s_c_sort', 'asc', ['TYPE'=>'M']);
while($mark = $obMark->Fetch())
{
	if($mark['SID'] == 'NOT_SURE') {
		$arResult['DEFAULT_MARK'] = $mark['ID'];
	}

	$arResult['DICTIONARY']['MARK'][$mark['ID']] = $mark;

	$arResult['DICTIONARY']['MARK'][$mark['ID']]['ICON'] = $arResult['DICTIONARY_MARK'][$mark["SID"]]['ICON'];
	$arResult['DICTIONARY']['MARK'][$mark['ID']]['COLOR'] = $arResult['DICTIONARY_MARK'][$mark["SID"]]['COLOR'];
}

$fCategory = 0;
$category = \CTicketDictionary::GetList($by, $sort, ['SID'=>'order_discussion'],$is_filtered)->Fetch();
if($category['ID'] > 0)
{
	$fCategory = $category['ID'];
}

$arResult['ORDER_CATEGORY'] = $fCategory;

if($arResult['DICTIONARY']['CATEGORY'][$fCategory])
{
	unset($arResult['DICTIONARY']['CATEGORY'][$fCategory]);
}



if(strpos($arResult["REAL_FILE_PATH"],'order') !== false) {
    $arResult["REAL_FILE_PATH"] = $APPLICATION->GetCurPage();
}

if (strpos($arResult["REAL_FILE_PATH"],'complaints') !== false) {
    $arResult["REAL_FILE_PATH"] = $APPLICATION->GetCurPage();
}
?>