<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

if(!Loader::includeModule("iblock"))
{
	ShowError(GetMessage("SIMPLECOMP_IBLOCK_MODULE_NONE"));
	return;
}

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

if(!isset($arParams["PRODUCTS_IBLOCK_ID"]))
	$arParams["PRODUCTS_IBLOCK_ID"] = 0;

if(!isset($arParams["NEWS_IBLOCK_ID"]))
	$arParams["NEWS_IBLOCK_ID"] = 0;

if($this->startResultCache()) {

	$objNews = CIBlockElement::GetList(
		array(),
		array(
			"IBLOCK_ID" => $arParams["NEWS_IBLOCK_ID"],
			"ACTIVE" => "Y"
		),
		false,
		false,
		array(
			"ID",
			"NAME",
			"ACTIVE_FROM"
		)
	);
	$arNews = array();
	$arNewsID = array();
	while($newsElements =  $objNews->Fetch()) {
		$arNewsID[] = $newsElements["ID"];
		$arNews[$newsElements["ID"]] = $newsElements;
	}
	
	$objSection = CIBlockSection::GetList(
		array(),
		array(
			"IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"],
			$arParams["PRODUCTS_IBLOCK_ID_PROPERTY"] => $arNewsID,
			"ACTIVE" => "Y"
		),
		false,
		array(
			"ID",
			"IBLOCK_ID",
			"NAME",
			$arParams["PRODUCTS_IBLOCK_ID_PROPERTY"]
		),
		false
	);
	$arSections = array();
	$arSectionsID = array();
	while($arSectionCatalog = $objSection->Fetch()) {
		$arSectionsID[] = $arSectionCatalog["ID"];
		$arSections[$arSectionCatalog["ID"]] = $arSectionCatalog;
	}

	$objProduct = CIBlockElement::GetList(
		array(),
		array(
			"IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"],
			"SECTION_ID" => $arSectionsID,
			"ACTIVE" => "Y"
		),
		false,
		false,
		array(
			"ID",
			"IBLOCK_ID",
			"IBLOCK_SECTION_ID",
			"NAME",
			"PROPERTY_ARTNUMBER",
			"PROPERTY_MATERIAL",
			"PROPERTY_PRICE"
		)
	);
	$arResult["COUNT_ELEMENTS"] = 0;
	while($arProduct = $objProduct->Fetch()) {
		$arResult["COUNT_ELEMENTS"]++;
		foreach($arSections[$arProduct["IBLOCK_SECTION_ID"]][$arParams["PRODUCTS_IBLOCK_ID_PROPERTY"]] as $arSection) {
			$arNews[$arSection]["PRODUCTS"][] = $arProduct;
		}
	}

	foreach($arSections as $arSection) {
		foreach($arSection[$arParams["PRODUCTS_IBLOCK_ID_PROPERTY"]] as $arElement) {
			$arNews[$arElement]["SECTIONS"][] = $arSection["NAME"];
		}
	}
	$arResult["NEWS"] = $arNews;
	
	$this->SetResultCacheKeys(array("COUNT_ELEMENTS"));
	$this->includeComponentTemplate();

} else {
	$this->abortResultCache();
}

$APPLICATION->SetTitle("В каталоге товаров представлено товаров: {$arResult["COUNT_ELEMENTS"]}");
?>