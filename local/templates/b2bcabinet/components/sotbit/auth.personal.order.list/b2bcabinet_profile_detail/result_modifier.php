<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Sotbit\B2bCabinet;

foreach($arResult["ORDERS"] as $val)
{
	$arResult["ORDER_BY_STATUS"][$val["ORDER"]["STATUS_ID"]][] = $val;
}

$methodIstall = Option::get('sotbit.b2bcabinet', 'method_install', '', SITE_ID) == 'AS_TEMPLATE' ? SITE_DIR.'b2bcabinet/' : SITE_DIR;

$filterOption = new Bitrix\Main\UI\Filter\Options('ORDER_LIST');
$filterData = $filterOption->getFilter([]);
$arResult['FILTER_STATUS_NAME'] = (isset($filterData['STATUS'])) ? $arResult['INFO']['STATUS'][$filterData['STATUS']]['NAME']: '';

$buyers = [];
$pt = unserialize(Option::get("sotbit.b2bcabinet","BUYER_PERSONAL_TYPE","a:0:{}"));
if(!is_array($pt))
{
	$pt = [];
}

$innProps = unserialize(Option::get('sotbit.b2bcabinet', 'PROFILE_ORG_INN'));
if(!is_array($innProps))
{
	$innProps = [];
}


$orgProps = unserialize(Option::get('sotbit.b2bcabinet', 'PROFILE_ORG_NAME'));
if(!is_array($orgProps))
{
	$orgProps = [];
}

$dbstatus = CSaleStatus::GetList(
    [], [] , false, false, ["ID", "NAME"]
);
while($resultStatus = $dbstatus->Fetch()){
    $arResult["ORDER_STATUS"][$resultStatus["ID"]] = $resultStatus["NAME"];
}

$dbpaySystem = CSalePaySystem::GetList(
    [], [] , false, false, ["ID", "NAME"]
);
while($resultPaySystem = $dbpaySystem->Fetch()){
    $arResult["PAY_SYSTEM"][$resultPaySystem["ID"]] = $resultPaySystem["NAME"];
}

$dbDelivery = CSaleDelivery::GetList(
    [], [] , false, false, ["ID", "NAME"]
);
while($resultDelivery = $dbDelivery->Fetch()){
    $arResult["DELIVERY"][$resultDelivery["ID"]] = $resultDelivery["NAME"];
}

if($arParams["PROFILE_ID"])
{
	$rs = \Bitrix\Sale\Internals\UserPropsValueTable::getList(
		array(
			'filter' => array(
				"USER_PROPS_ID" => $arParams["PROFILE_ID"],
				'ORDER_PROPS_ID' => array_merge($innProps,$orgProps)
			),
			"select" => array("ORDER_PROPS_ID",'USER_PROPS_ID','VALUE')
		)
	);
	while($prop = $rs->Fetch())
	{
		if(in_array($prop['ORDER_PROPS_ID'],$innProps))
		{
			$buyers[$prop['USER_PROPS_ID']]['INN'] = $prop['VALUE'];
		}
		if(in_array($prop['ORDER_PROPS_ID'],$orgProps))
		{
			$buyers[$prop['USER_PROPS_ID']]['ORG'] = $prop['VALUE'];
		}
	}
}

$arResult['BUYERS'] = [];

if($buyers)
{
	foreach($buyers as $id=>$v)
	{
		$name = $v['ORG'];
		$name .= ($v['INN'])?' ('.$v['INN'].')':'';
		$arResult['BUYERS'][$id] = $name;
	}
}

$orgs = [];

$idOrders = [];

foreach($arResult['ORDERS'] as $key => $arOrder)
{
    if(isset($filterData['FIND']) && !empty($filterData['FIND']) && $filterData['FIND'] != $arOrder['ORDER']['ID']) {
        unset($arResult['ORDERS'][$key]);
        continue;
    }

	$idOrders[] = $arOrder['ORDER']['ID'];
}

if ($arParams["PROFILE_ID"]) {

	$rs = \Bitrix\Sale\Internals\OrderPropsValueTable::getList([
		'filter' => [
			'ORDER_ID' => $idOrders,
			'ORDER_PROPS_ID' => $innProps
		]
	]);
	while($org = $rs->fetch())
	{
		if($org["VALUE"] != $buyers[$arParams["PROFILE_ID"]]["INN"]){
			continue;
		}
		$showOrders[] = $org["ORDER_ID"];

		if($buyers)
		{
			foreach ($buyers as $id => $v)
			{
				if($v['INN'] == $org['VALUE'])
				{
					$name = $v['ORG'];
					$name .= ($v['INN'])?' ('.$v['INN'].')':'';
					$orgs[$org['ORDER_ID']] = '<a href="'. $methodIstall .'personal/companies/profile_detail.php?ID='. $id .'">'. $name .'</a>';
				}
			}
		}
	}

}

$defaultFilter =  array(
    'ACCOUNT_NUMBER',
    'DATE_INSERT_to',
    'DATE_INSERT_from',
    'STATUS_ID',
    'PAYED',
    'PAY_SYSTEM_ID',
    'DELIVERY_ID',
    'FIND'
);

$filter = [];
$filterOption = new Bitrix\Main\UI\Filter\Options( 'PROFILE_ORDER_LIST' );
$filterData = $filterOption->getFilter( [] );
foreach( $filterData as $key => $value )
{
    if( in_array($key, $defaultFilter) && !empty($value))
        $filter[$key] = $value;
}

foreach($arResult['ORDERS'] as $arOrder)
{
    if($filter){
        $continue = false;
        if($filter["DATE_INSERT_to"] && $filter["DATE_INSERT_from"]){
            if($arOrder["ORDER"]["DATE_INSERT"]->toString()>=$filter["DATE_INSERT_from"] && $arOrder["ORDER"]["DATE_INSERT"]->toString()<=$filter["DATE_INSERT_to"]){
                $continue = false;
            }
            else{
                $continue = true;
            }
        }

        foreach ($filter as $code => $value){
            if($code == "ACCOUNT_NUMBER" && $arOrder["ORDER"]["ACCOUNT_NUMBER"] != $value) {
                $continue = true;
                break;
            }
            if($code == "STATUS_ID" && $arOrder["ORDER"]["STATUS_ID"] != $value) {
                $continue = true;
                break;
            }
            if($code == "PAYED" && $arOrder["ORDER"]["PAYED"] != $value) {
                $continue = true;
                break;
            }
            if($code == "PAY_SYSTEM_ID" && $arOrder["ORDER"]["PAY_SYSTEM_ID"] != $value) {
                $continue = true;
                break;
            }
            if($code == "DELIVERY_ID" && $arOrder["ORDER"]["DELIVERY_ID"] != $value) {
                $continue = true;
                break;
            }
            if($code == "FIND" && $arOrder["ORDER"]["ACCOUNT_NUMBER"] != $value) {
                $continue = true;
                break;
            }
        }
    }

	if (empty($showOrders)) {
		$showOrders = [];
	}

    if(!in_array($arOrder["ORDER"]["ID"], $showOrders) || $continue){
        continue;
    }

	$b2bOrder = new B2bCabinet\Shop\Order($arOrder["ORDER"]);
	$aActions = Array(
		array("TEXT"=>GetMessage('SPOL_MORE_ABOUT_ORDER'), "ONCLICK"=>"location.assign('".$b2bOrder->getUrl($arParams['PATH_TO_DETAIL'])."')"),
	);

    if(is_array($allowActions))
        foreach($allowActions as $licence)
            array_push($aActions, GetAction($licence, $arOrder));

	$payment = current($arOrder['PAYMENT']);
	$shipment = current($arOrder['SHIPMENT']);

	$aCols = array(
		"ID" => $arOrder['ORDER']["ID"],
		"DATE_INSERT" => $arOrder['ORDER']['DATE_INSERT']->toString(),
		'ACCOUNT_NUMBER' => $arOrder['ORDER']['ACCOUNT_NUMBER'],
		"DATE_UPDATE" => $arOrder['ORDER']['DATE_UPDATE']->toString(),
		'STATUS' => $b2bOrder->getStatus(),
		'PAYED' => $arOrder["ORDER"]["PAYED"],
		'PAY_SYSTEM_ID' => $arOrder["ORDER"]["PAY_SYSTEM_ID"],
	);

	$items = array();
	$index = 1;
	foreach ($arOrder['BASKET_ITEMS'] as $item)
	{
		array_push($items, $index++.". $item[NAME] - ($item[QUANTITY] $item[MEASURE_TEXT])");
	}

	$arResult['ROWS'][] = array(
		'data' =>array_merge($arOrder['ORDER'], array(
			"SHIPMENT_METHOD" => $arResult["INFO"]["DELIVERY"][$arOrder["ORDER"]["DELIVERY_ID"]]["NAME"],
			"PAYMENT_METHOD" => $arResult["INFO"]["PAY_SYSTEM"][$arOrder["ORDER"]["PAY_SYSTEM_ID"]]["NAME"],
			'ITEMS' => implode('<br>', $items),
			'STATUS' => ($arOrder['ORDER']['CANCELED'] == 'Y' ? Loc::GetMessage('SPOL_PSEUDO_CANCELLED') : $arResult['INFO']['STATUS'][$arOrder['ORDER']['STATUS_ID']]['NAME']),
			'PAYED' => GetMessage('SPOL_'.($arOrder["ORDER"]["PAYED"] == "Y" ? 'YES' : 'NO')),
			'PAY_SYSTEM_ID' => $arOrder["ORDER"]["PAY_SYSTEM_ID"],
			'DELIVERY_ID' => $arOrder["ORDER"]["DELIVERY_ID"],
		) ),
		'actions' => $aActions,
		'COLUMNS' => $aCols,
		'editable' => true,
	);
}

function GetAction($key, $arOrder)
{
	$arAction = array(
		'repeat' => array("TEXT"=>GetMessage('SPOL_REPEAT_ORDER'), "ONCLICK"=>"location.assign('".$arOrder['ORDER']["URL_TO_COPY"]."')"),
		'cancel' => array("TEXT"=>GetMessage('SPOL_CANCEL_ORDER'), "ONCLICK"=>"if(confirm('".GetMessage('SPOL_CONFIRM_DEL_ORDER')."')) window.location='".$arOrder['ORDER']["URL_TO_CANCEL"]."';"),
	);

	return $arAction[$key];
}