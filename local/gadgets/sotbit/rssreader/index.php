<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CUtil::InitJSCore(array());

$rnd = rand();
$APPLICATION->SetAdditionalCSS('/local/gadgets/sotbit/rssreader/styles.css');
$_SESSION["GD_RSS_PARAMS"][$rnd] = $arGadgetParams;
?>
<div id="rss_container_<?=$rnd?>" class="test">
</div>
<script type="text/javascript">

	lastWaitRSS = [];

	function __RSSadjustWait()
	{
		if (!this.bxmsg) return;

		var arContainerPos = BX.pos(this),
			div_top = arContainerPos.top;

		if (div_top < BX.GetDocElement().scrollTop)
			div_top = BX.GetDocElement().scrollTop + 5;

		this.bxmsg.style.top = (div_top + 5) + 'px';

		if (this == BX.GetDocElement())
		{
			this.bxmsg.style.right = '5px';
		}
		else
		{
			this.bxmsg.style.left = (arContainerPos.right - this.bxmsg.offsetWidth - 5) + 'px';
		}
	}

	__RSSshowWait = function(node)
	{
        if (!node) {
            return;
        }

		node = BX(node);

        if (!node) {
            return;
        }
		var container_id = node.id;

		var obMsg = node.bxmsg = node.appendChild(BX.create('DIV', {
			props: {
				id: 'wait_' + container_id,
				className: 'gdrsswaitwindow'
			}
		}));

		lastWaitRSS[lastWaitRSS.length] = obMsg;
		return obMsg;
	}


	__RSScloseWait = function(node, obMsg)
	{
		obMsg = obMsg || node && (node.bxmsg || BX('wait_' + node.id)) || lastWaitRSS.pop();
		if (obMsg && obMsg.parentNode)
		{
			for (var i=0,len=lastWaitRSS.length;i<len;i++)
			{
				if (obMsg == lastWaitRSS[i])
				{
					lastWaitRSS = BX.util.deleteFromArray(lastWaitRSS, i);
					break;
				}
			}

			obMsg.parentNode.removeChild(obMsg);
			if (node) node.bxmsg = null;
			BX.cleanNode(obMsg, true);
		}
	}

	BX.ready(function(){
		url = '/local/gadgets/sotbit/rssreader/getdata.php';
		params = 'rnd=<?=$rnd?>&lang=<?=LANGUAGE_ID?>&sessid='+BX.bitrix_sessid();

		BX.ajax.post(url, params, function(result)
		{
			__RSScloseWait('rss_container_<?=$rnd?>');
			let rss_container = ('rss_container_<?=$rnd?>');
			if (rss_container) {
                rss_container.innerHTML = result;
            }
		});

		__RSSshowWait('rss_container_<?=$rnd?>');
	});
</script>