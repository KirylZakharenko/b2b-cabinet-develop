<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?

foreach($arResult["ORDERS"] as $val)
{
	$arResult["ORDER_BY_STATUS"][$val["ORDER"]["STATUS_ID"]][] = $val;
}

$filterOption = new Bitrix\Main\UI\Filter\Options('ORDER_LIST');
$filterData = $filterOption->getFilter([]);
$arResult['FILTER_STATUS_NAME'] = (isset($filterData['STATUS'])) ? $arResult['INFO']['STATUS'][$filterData['STATUS']]['NAME']: '';

$buyers = [];
$pt = unserialize(Bitrix\Main\Config\Option::get("sotbit.b2bcabinet","BUYER_PERSONAL_TYPE","a:0:{}"));
if(!is_array($pt))
{
	$pt = [];
}

$innProps = unserialize(Bitrix\Main\Config\Option::get('sotbit.b2bcabinet', 'PROFILE_ORG_INN'));
if(!is_array($innProps))
{
	$innProps = [];
}


$orgProps = unserialize(Bitrix\Main\Config\Option::get('sotbit.b2bcabinet', 'PROFILE_ORG_NAME'));
if(!is_array($orgProps))
{
	$orgProps = [];
}

$idBuyers = [];
$rs = CSaleOrderUserProps::GetList(
	array("DATE_UPDATE" => "DESC"),
	array(
		"PERSON_TYPE_ID" => $pt,
		"USER_ID" => (int)$USER->GetID()
	)
);
while($buyer = $rs->Fetch())
{
	$idBuyers[]=$buyer['ID'];
}

if($idBuyers)
{
	$rs = \Bitrix\Sale\Internals\UserPropsValueTable::getList(
		array(
			'filter' => array(
				"USER_PROPS_ID" => $idBuyers,
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

foreach($arResult['ORDERS'] as $arOrder)
{
	$idOrders[] = $arOrder['ORDER']['ID'];
}


$rs = \Bitrix\Sale\Internals\OrderPropsValueTable::getList(['filter' => ['ORDER_ID' => $idOrders, 'ORDER_PROPS_ID' =>
	$innProps]]);
while($org = $rs->fetch())
{
	if($buyers)
	{
		foreach ($buyers as $id => $v)
		{
			if($v['INN'] == $org['VALUE'])
			{
				$name = $v['ORG'];
				$name .= ($v['INN'])?' ('.$v['INN'].')':'';
				$orgs[$org['ORDER_ID']] = $name;
			}
		}
	}
}





foreach($arResult['ORDERS'] as $arOrder)
{
	 $aActions = Array(
		array("ICONCLASS"=>"detail", "TEXT"=>GetMessage('SPOL_MORE_ABOUT_ORDER'), "ONCLICK"=>"location.assign('".$arOrder['ORDER']["URL_TO_DETAIL"]."')"),
//		array("ICONCLASS"=>"copy", "TEXT"=>GetMessage('SPOL_REPEAT_ORDER'), "ONCLICK"=>"jsUtils.Redirect(arguments, '".$arOrder['ORDER']["URL_TO_COPY"]."')", "DEFAULT"=>true),
//		array("SEPARATOR"=>true),
//		array("ICONCLASS"=>"cancel", "TEXT"=>GetMessage('SPOL_CANCEL_ORDER'), "ONCLICK"=>"if(confirm('".GetMessage('SPOL_CONFIRM_DEL_ORDER')."')) window.location='".$arOrder['ORDER']["URL_TO_CANCEL"]."';"),
	);

	foreach($allowActions as $licence)
		array_push($aActions, GetAction($licence, $arOrder));

	$payment = current($arOrder['PAYMENT']);
	$shipment = current($arOrder['SHIPMENT']);

	$aCols = array(
		"ID" => $arOrder['ORDER']["ID"],
		"DATE_INSERT" => $arOrder['ORDER']['DATE_INSERT']->toString(),
		'ACCOUNT_NUMBER' => $arOrder['ORDER']['ACCOUNT_NUMBER'],
		"DATE_UPDATE" => $arOrder['ORDER']['DATE_UPDATE']->toString(),
		'STATUS' => $arResult['INFO']['STATUS'][$arOrder['ORDER']['STATUS_ID']]['NAME'],
		'PAYED' => $arOrder["ORDER"]["PAYED"],
		'PAY_SYSTEM_ID' => $arOrder["ORDER"]["PAY_SYSTEM_ID"],
		'BUYER' => $orgs[$arOrder['ORDER']['ID']]
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
			'STATUS' => $arResult['INFO']['STATUS'][$arOrder['ORDER']['STATUS_ID']]['NAME'],
			'PAYED' => GetMessage('SPOL_'.($arOrder["ORDER"]["PAYED"] == "Y" ? 'YES' : 'NO')),
			'PAY_SYSTEM_ID' => $arOrder["ORDER"]["PAY_SYSTEM_ID"],
			'DELIVERY_ID' => $arOrder["ORDER"]["DELIVERY_ID"],
			'BUYER' => $orgs[$arOrder['ORDER']['ID']]
		) ),
		'actions' => $aActions,
		'COLUMNS' => $aCols,
		'editable' => true,
	);
}

function GetAction($key, $arOrder)
{
	$arAction = array(
		'repeat' => array("ICONCLASS"=>"copy", "TEXT"=>GetMessage('SPOL_REPEAT_ORDER'), "ONCLICK"=>"location.assign('".$arOrder['ORDER']["URL_TO_COPY"]."')"),
		'cancel' => array("ICONCLASS"=>"cancel", "TEXT"=>GetMessage('SPOL_CANCEL_ORDER'), "ONCLICK"=>"if(confirm('".GetMessage('SPOL_CONFIRM_DEL_ORDER')."')) window.location='".$arOrder['ORDER']["URL_TO_CANCEL"]."';"),
	);

	return $arAction[$key];
}