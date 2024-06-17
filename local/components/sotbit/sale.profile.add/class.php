<?php
use Bitrix\Main;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale;
use Sotbit\Auth\Internals\BuyerConfirmTable;
use Sotbit\Auth\User\WholeSaler;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class SotbitProfileAdd extends CBitrixComponent
{
	const E_SALE_MODULE_NOT_INSTALLED = 10000;

	public function executeComponent()
	{
		global $USER, $APPLICATION;

        if(!$USER->IsAuthorized()) {
            $APPLICATION->AuthForm(Loc::getMessage("SOA_SALE_ACCESS_DENIED"), false, false, 'N', false);
            return;
        }

		Loc::loadMessages(__FILE__);

		$this->setFrameMode(false);

		if(!$this->checkRequiredModules()) {
            ShowError(Loc::getMessage("SOA_NECESSARY_MODULE_NOT_INSTALL"));
            return;
        }

		if($this->arParams["SET_TITLE"] === 'Y') {
			$APPLICATION->SetTitle(Loc::getMessage("SOA_NEW_SALE_TITLE"));
		}

        $request = Main\Application::getInstance()->getContext()->getRequest();

		if(count($this->arParams['PERSONAL_TYPES']) <= 0) {
			$this->arResult['ERRORS'][] = Loc::getMessage("SOA_SALE_ORG_NO_TYPES");
		}

		if(is_array($this->arParams['PERSONAL_TYPES'])) {
		    $idPersonType = reset($this->arParams['PERSONAL_TYPES']);
        }

		if($request->isPost() && ($request->get("change_person_type")) && check_bitrix_sessid()) {
			if(in_array($request->get('PERSON_TYPE'), $this->arParams['PERSONAL_TYPES'])) {
				$idPersonType = $request->get('PERSON_TYPE');
			}
		}

        if($request->isPost() && $request->get("cancel") && check_bitrix_sessid()) {
            $href = ($this->arParams['PATH_TO_LIST']) ? $this->arParams['PATH_TO_LIST'] : $APPLICATION->GetCurPage();

            \localRedirect($href);
            return;
        }

		if($request->isPost() && ($request->get("save") || $request->get("apply")) && check_bitrix_sessid()) {
			$this->addProfileProperties($request);
			if(!empty($request->get("PERSON_TYPE")))
			    $idPersonType = $request->get("PERSON_TYPE");
		}

        $this->arResult["USE_PERSONAL_GROUPS"] = false;
        if (Loader::includeModule('sotbit.auth') && Option::get('sotbit.auth', 'WHOLESALERS_USE_GROUPING', 'N', SITE_ID) == "Y" ) {
            $wholesaller = new Sotbit\Auth\User\WholeSaler();
            $this->arResult["PERSONAL_GROUPS_VALUES"] = $wholesaller->getPersonGroupValues();
            if ($this->arResult["PERSONAL_GROUPS_VALUES"]) {
                $this->arResult["PERSONAL_GROUPS_LIST"] = $wholesaller->getPersonGroupList(
                    [
                        "ID" => array_values($this->arResult["PERSONAL_GROUPS_VALUES"])
                    ]
                );

                if ($this->arResult["PERSONAL_GROUPS_VALUES"]) {
                    $this->arResult["PERSONAL_GROUPS_LIST_TITLE"] = $wholesaller->getPersonGroupTitle();

                    foreach ($this->arResult["PERSONAL_GROUPS_VALUES"] as $personId => $groupId) {
                        $this->arResult["PERSONAL_GROUPS_LIST"][$groupId]["PERSON_TYPE"][] = $personId;
                    }

                    $this->arResult["USE_PERSONAL_GROUPS"] = true;
                }
            }
        }

		$this->fillResultArray($idPersonType, $request);
        if ($this->arResult["PERSONAL_GROUPS_VALUES"]) {
            $this->setActivePersonTab();
        }
		$this->includeComponentTemplate();
	}

    protected function setActivePersonTab()
    {
        if ($this->arResult["PERSON_TYPE"] && $this->arResult["PERSONAL_GROUPS_VALUES"][$this->arResult["PERSON_TYPE"]["ID"]]) {
            $this->arResult["PERSONAL_ACTIVE_GROUP"] = $this->arResult["PERSONAL_GROUPS_LIST"][$this->arResult["PERSONAL_GROUPS_VALUES"][$this->arResult["PERSON_TYPE"]["ID"]]]["ID"];

        } else {
            $this->arResult["PERSONAL_ACTIVE_GROUP"]  =  $this->arResult["PERSONAL_GROUPS_LIST"][array_key_first($this->arResult["PERSONAL_GROUPS_LIST"])]["ID"];
        }
    }

	/**
	 * Function checks if required modules installed. If not, throws an exception
	 */
	protected function checkRequiredModules()
	{
	    return !(!Loader::includeModule('sale') || !Loader::includeModule('sotbit.b2bcabinet'));
	}

	/**
	 *
	 * @param  Main\HttpRequest $request
	 */
	protected function addProfileProperties(\Bitrix\Main\HttpRequest $request)
	{
		$fieldValues = $this->prepareAddProperties($request);
		if(empty($this->arResult['ERRORS']))
		{
			$idProfile = $this->executeAddProperties($request, $fieldValues);
		}

		if(empty($this->arResult['ERRORS']) && $idProfile > 0)
		{
			if(strlen($request->get("save")) > 0)
			{
                \localRedirect($this->arParams["PATH_TO_LIST"]);
			}
		}
	}

	/**
	 *
	 * @param  Main\HttpRequest $request
	 * @return array
	 */
	protected function checkProps($request)
	{
		$orderPropertiesList = self::getOrderProps([
            'filter' => ["PERSON_TYPE_ID" => $request->get('PERSON_TYPE')]
        ]);

		while ($orderProperty = $orderPropertiesList->fetch()) {
            $currentValue = !is_array($request->get("ORDER_PROP_" . $orderProperty["ID"])) ? trim($request->get("ORDER_PROP_" . $orderProperty["ID"])) : $request->get("ORDER_PROP_" . $orderProperty["ID"]);

			if(!$this->checkProperty($orderProperty, $currentValue)) {
                $this->arResult['ERRORS'][]  = Loc::getMessage("SOA_SALE_NO_FIELD") . " \"" . $orderProperty["NAME"] . "\"";
			}
		}
	}

		/**
	 *
	 * @param  Main\HttpRequest $request
	 * @return array
	 */
	protected function prepareAddProperties($request)
	{
		$fieldValues = [];
		$orderPropertiesList = self::getOrderProps([
            'filter' => ["PERSON_TYPE_ID" => $request->get('PERSON_TYPE')]
        ]);

		while ($orderProperty = $orderPropertiesList->fetch()) {
			$currentValue = $request->get("ORDER_PROP_" . $orderProperty["ID"]);

			if($this->checkProperty($orderProperty, $currentValue)) {
				$fieldValues[$orderProperty["ID"]] = [
					"USER_PROPS_ID" => $this->idProfile,
					"ORDER_PROPS_ID" => $orderProperty["ID"],
					"NAME" => $orderProperty["NAME"],
					'MULTIPLE' => $orderProperty["MULTIPLE"]
				];

				if($orderProperty["TYPE"] === 'FILE')
				{
					$fileIdList = [];

					$currentValue = $request->getFile("ORDER_PROP_" . $orderProperty["ID"]);
					foreach ($currentValue['name'] as $key => $fileName) {
						if(strlen($fileName) > 0) {
							$fileArray = [
								'name' => $fileName,
								'type' => $currentValue['type'][$key],
								'tmp_name' => $currentValue['tmp_name'][$key],
								'error' => $currentValue['error'][$key],
								'size' => $currentValue['size'][$key],
							];

							$fileIdList[] = CFile::SaveFile($fileArray, "/sale/profile/");
						}
					}

					$fieldValues[$orderProperty["ID"]]['VALUE'] = $fileIdList;
				} elseif($orderProperty['TYPE'] == "MULTISELECT") {
					$fieldValues[$orderProperty["ID"]]['VALUE'] = implode(',', $currentValue);
				} else {
					$fieldValues[$orderProperty["ID"]]['VALUE'] = $currentValue;
				}
			} else {
                $this->arResult['ERRORS'][]  = Loc::getMessage("SOA_SALE_NO_FIELD") . " \"" . $orderProperty["NAME"] . "\"";
			}
		}

		return $fieldValues;
	}

	/**
	 * @param Main\HttpRequest $request
	 * @param array $fieldValues
	 * @return void
	 */
	protected function executeAddProperties($request, $fieldValues) {

	    $idProfile = 0;
		global $USER;
		$idUser = $USER->GetID();

		if(!$idUser) {
            $this->arResult['ERRORS'][] = Loc::getMessage("SOA_SALE_NO_USER");
		}

		if(empty($this->arResult['ERRORS'])) {
			if(false && Loader::includeModule('sotbit.auth') && Option::get('sotbit.auth', 'CONFIRM_BUYER', 'N', SITE_ID) == 'Y')
			{
				$fields = [
					'PERSON_TYPE' => $request->get('PERSON_TYPE'),
					'ORDER_FIELDS' => []
				];

				$orderPropertiesList = self::getOrderProps([
                   'filter' => ["PERSON_TYPE_ID" => $request->get('PERSON_TYPE')]
                ]);

				while ($orderProperty = $orderPropertiesList->fetch())
				{
					$fields['ORDER_FIELDS'][$orderProperty['CODE']] = $fieldValues[$orderProperty['ID']];
				}

				$innCode = Option::get('sotbit.auth', 'GROUP_ORDER_INN_FIELD_' . $request->get('PERSON_TYPE'), 'INN',
					SITE_ID);

				$user = Main\UserTable::getList([
					'filter' => [
						'ID' => $idUser
					],
					'limit' => 1,
					'select' => ['EMAIL']

				])->fetch();

				BuyerConfirmTable::add([
					'LID' => SITE_ID,
					'FIELDS' => $fields,
					'EMAIL' => $user['EMAIL'],
					'ID_USER' => $idUser,
					'INN' => $fields['ORDER_FIELDS'][$innCode]
				]);
			} else {
				$saleProps = new \CSaleOrderUserProps;

				$profileProperty = $orderPropertiesList = self::getOrderProps([
                    'filter' => [
                            "PERSON_TYPE_ID" => $request->get('PERSON_TYPE'),
                            'IS_PROFILE_NAME' => 'Y'
                    ],
                    'select' => ['ID']
                ])->fetch();

                $name = '';

				if(!empty($profileProperty)) {
				    $name = $request->get('ORDER_PROP_' . $profileProperty['ID']);
                }

				if(empty($name)) {
                    $this->arResult['ERRORS'][] = Loc::getMessage("SOA_SALE_PROFILE_NO_NAME");
				    return;
                }

				$idProfile = $saleProps->Add(
					[
						'NAME' => trim($name),
						'USER_ID' => $idUser,
						'PERSON_TYPE_ID' => $request->get('PERSON_TYPE')
					]
				);

				if(!$idProfile) {
                    $this->arResult['ERRORS'][] = Loc::getMessage("SOA_SALE_ERROR_ADD_PROF");
					return;
				}

				$saleOrderUserPropertiesValue = new CSaleOrderUserPropsValue;

				$orderPropertiesList = self::getOrderProps([
                    'filter' => ["PERSON_TYPE_ID" => $request->get('PERSON_TYPE')]
                ]);

				while ($orderProperty = $orderPropertiesList->fetch()) {
				    if(is_array($value = $fieldValues[$orderProperty['ID']]['VALUE'])){
				        $value = serialize($value);
                    }
					$saleOrderUserPropertiesValue->Add(
						[
							'USER_PROPS_ID' => $idProfile,
							'ORDER_PROPS_ID' => $orderProperty['ID'],
							'NAME' => $orderProperty['NAME'],
							'VALUE' => $value
						]
					);
				}

				return $idProfile;
			}
		}
	}

	/**
	 * Fill $arResult array for output in template
	 * @param int $idPersonType
	 * @param Main\HttpRequest $request
	 */
	protected function fillResultArray( $idPersonType, $request ) {
		$this->arResult["ORDER_PROPS"] = [];
		$this->arResult["ORDER_PROPS_VALUES"] = [];

		if($request->get('NAME')) {
			$this->arResult['NAME'] = $request->get('NAME');
		}

		$rsPersonTypes = \Bitrix\Sale\Internals\PersonTypeTable::getList(
			[
				'filter' => [
                    [
                        'LOGIC' => 'OR',
                        ['LID' => SITE_ID],
                        ['PERSON_TYPE_SITE.SITE_ID' => SITE_ID],
                    ],
					'ID' => $this->arParams['PERSONAL_TYPES']
				]
			]
		);

		while ($personType = $rsPersonTypes->fetch()) {
			$this->arResult['PERSON_TYPES'][$personType['ID']] = $personType['NAME'];
		}

		$personType = Sale\PersonType::load(SITE_ID, $idPersonType);

		$this->arResult["PERSON_TYPE"] = $personType[$idPersonType];
		$this->arResult["PERSON_TYPE"]["NAME"] = htmlspecialcharsbx($this->arResult["PERSON_TYPE"]["NAME"]);

		$locationValue = [];

		if($this->arParams['COMPATIBLE_LOCATION_MODE'] == 'Y') {
			$locationDb = CSaleLocation::GetList(
				[
					"SORT" => "ASC",
					"COUNTRY_NAME_LANG" => "ASC",
					"CITY_NAME_LANG" => "ASC"
				],
				[],
				LANGUAGE_ID
			);

			while ($location = $locationDb->Fetch()) {
				$locationValue[] = $location;
			}
		}

		$arrayTmp = [];

		$orderPropertiesListGroup = CSaleOrderPropsGroup::GetList(
			[
				"SORT" => "ASC",
				"NAME" => "ASC"
			],
			[],
			false,
			false,
			[
				"ID",
				"PERSON_TYPE_ID",
				"NAME",
				"SORT"
			]
		);

		while ($orderPropertyGroup = $orderPropertiesListGroup->GetNext()) {
			$arrayTmp[$orderPropertyGroup["ID"]] = $orderPropertyGroup;
			$orderPropertiesList = self::getOrderProps(
                [
                    'filter' => [
                    "PROPS_GROUP_ID" => $orderPropertyGroup["ID"],
                    ]
                ]
            );

            $this->arResult["ORDER_PROPS"][$orderPropertyGroup["PERSON_TYPE_ID"]][$orderPropertyGroup["ID"]] = $orderPropertyGroup;

			while ($orderProperty = $orderPropertiesList->fetch()) {
				if(in_array($orderProperty["TYPE"], [
					"SELECT",
					"MULTISELECT",
					"RADIO",
					"ENUM",
				])) {
					$dbVars = CSaleOrderPropsVariant::GetList(($by = "SORT"), ($order = "ASC"), ["ORDER_PROPS_ID" => $orderProperty["ID"]]);
					while ($vars = $dbVars->GetNext())
						$orderProperty["VALUES"][] = $vars;
				}
				elseif($orderProperty["TYPE"] == "LOCATION" && $this->arParams['COMPATIBLE_LOCATION_MODE'] == 'Y') {
					$orderProperty["VALUES"] = $locationValue;
				}

				if($request->get('ORDER_PROP_' . $orderProperty['ID'])) {
					$this->arResult["ORDER_PROPS_VALUES"]['ORDER_PROP_' . $orderProperty['ID']] = $request->get('ORDER_PROP_' . $orderProperty['ID']);
				}

                $this->arResult["ORDER_PROPS"][$orderPropertyGroup["PERSON_TYPE_ID"]][$orderPropertyGroup["ID"]]["PROPS"][] = $orderProperty;
			}
		}
	}

	protected function getOrderProps(array $params = array()) {
	    if(!is_array($params['filter']))
            $params['filter'] = [];

        if(!is_array($params['order']))
            $params['order'] = [];

        return Bitrix\Sale\Internals\OrderPropsTable::getList(
            [
                'filter' => array_merge([
//                    "USER_PROPS" => "Y",
                    "ACTIVE" => "Y",
                    "UTIL" => "N"
                ], $params['filter']),
                'select' => (isset($params['select'])) ? $params['select'] : [
                    "*"
                ],
                'order' => array_merge([
                    "SORT" => "ASC",
                    "NAME" => "ASC"
                ], $params['order'])
            ]
        );
    }

	/**
	 * Check value required params of property
	 * @param $property
	 * @param $currentValue
	 * @return bool
	 */
    protected function checkProperty($property, $currentValue)
    {
        if ($property["REQUIRED"] == "Y") {
            if ($property["TYPE"] == "LOCATION") {
                if ((int)($currentValue) <= 0)
                    return false;
            } elseif ($property["TYPE"] == "MULTISELECT") {
                if (!is_array($currentValue) || count(array_filter($currentValue)) <= 0)
                    return false;
            }
            if ($property["MULTIPLE"] == "Y" && is_array($currentValue)) {
                if (empty(array_diff($currentValue, [null, '', false])))
                    return false;
            }
            if ($property["IS_EMAIL"] == "Y") {
                if (strlen(trim($currentValue)) <= 0 || !check_email(trim($currentValue)))
                    return false;
            } elseif ($property["IS_PROFILE_NAME"] == "Y") {
                if (!is_array($currentValue) && strlen(trim($currentValue)) <= 0)
                    return false;
            } elseif ($property["IS_PAYER"] == "Y") {
                if (!is_array($currentValue) && strlen(trim($currentValue)) <= 0)
                    return false;
            } else {
                if (!is_array($currentValue) && strlen($currentValue) <= 0)
                    return false;
            }
        }

        return true;
    }
}