<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$APPLICATION->SetAdditionalCSS('/local/gadgets/sotbit/html_area/styles.css');

$bEdit = ($_REQUEST['gdhtml'] == $id) && ($_REQUEST['edit']=='true') && ($arParams["PERMISSION"] > "R");
if($_SERVER['REQUEST_METHOD'] == 'POST' && $_REQUEST['gdhtmlform'] == 'Y' && $_REQUEST['gdhtml'] == $id)
{
	$arGadget["USERDATA"] = Array("content"=>$_POST["html_content"]);
	$arGadget["FORCE_REDIRECT"] = true;
}
$arData = $arGadget["USERDATA"];
$content = $arData["content"];
?>

<div id="gdf<?=$id?>edit">
<?
	if($content)
	{
		$parser = new CTextParser();
		$parser->allow = array(
			"HTML"=>($arParams["MODE"] != "AI" ? "N" : "Y"),
			"ANCHOR"=>"Y",
			"BIU"=>"Y",
			"IMG"=>"Y",
			"QUOTE"=>"Y",
			"CODE"=>"Y",
			"FONT"=>"Y",
			"LIST"=>"Y",
			"SMILES"=>"N",
			"NL2BR"=>"N",
			"VIDEO"=>"N",
			"TABLE"=>"Y",
			"CUT_ANCHOR"=>"N",
			"ALIGN"=>"Y"
		);
		$parser->parser_nofollow = "Y";
		echo $parser->convertText($content);
	}
	else
	{
		if($arParams["PERMISSION"]>"R") {?>
            <div class="widget_content">
            <?
            echo \Bitrix\Main\Localization\Loc::getMessage("GD_HTML_AREA_NO_CONTENT");
            ?>
            </div>
            <?
        }
	}
?>

<?if($arParams["PERMISSION"]>"R"):?>
<div class="widget_links_btns" style="padding-top: 10px;"><a class="main-link b2b-main-link" href="javascript:void(0);" onclick="gdhtmledit()"><?echo \Bitrix\Main\Localization\Loc::getMessage("GD_HTML_AREA_CHANGE_LINK")?></a></div>
<?endif?>
</div>


<form action="?gdhtml=<?=$id?>" method="post" id="gdf<?=$id?>" class="d-none">
<?
CModule::IncludeModule("fileman");

$LHE = new CLightHTMLEditor;
$LHE->Show(array(
	'jsObjName' => 'oGadgetLHE',
	'inputName' => 'html_content',
	'content' => $content,
	'width' => '100%',
	'height' => '200px',
	'bResizable' => true,
	'bUseFileDialogs' => false,
	'bUseMedialib' => false,
	'toolbarConfig' => array(
		'Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat',
		'CreateLink', 'DeleteLink', 'Image',
		'ForeColor', 'InsertOrderedList', 'InsertUnorderedList',
		'Outdent', 'Indent', 'FontList', 'FontSizeList',
		'Source'
	),
	'bSaveOnBlur' => false,
	'BBCode' => ($arParams["MODE"] != "AI"),
	'bBBParseImageSize' => true,
	'ctrlEnterHandler' => 'gdhtmlsave',
));
?>
	<input type="hidden" name="gdhtmlform" value="Y">
	<?if ($arParams["MULTIPLE"] == "Y"):?>
	<input type="hidden" name="dt_page" value="<?=$arParams["DESKTOP_PAGE"]?>">
	<?endif;?>
	<?=bitrix_sessid_post()?>
	<a class="btn btn-sm btn-flat-primary" href="javascript:void(0);" onclick="return gdhtmlsave();"><?echo \Bitrix\Main\Localization\Loc::getMessage("GD_HTML_AREA_SAVE_LINK")?></a> <a class="btn btn-sm btn-flat-danger" href="<?=$GLOBALS["APPLICATION"]->GetCurPageParam(($arParams["MULTIPLE"]=="Y"?"dt_page=".$arParams["DESKTOP_PAGE"]:""), array("dt_page","gdhtml","edit"))?>"><?echo \Bitrix\Main\Localization\Loc::getMessage("GD_HTML_AREA_CANCEL_LINK")?></a>
</form>
<script type="text/javascript">
function gdhtmlsave()
{
	oGadgetLHE.SaveContent();
	document.getElementById("gdf<?=$id?>").submit();
	return false;
}

function gdhtmledit()
{
	document.getElementById("gdf<?=$id?>").classList.toggle('d-none');
	document.getElementById("gdf<?=$id?>edit").classList.toggle('d-none');
}
</script>