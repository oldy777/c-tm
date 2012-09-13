<!DOCTYPE html>
<html>
<head>
<title><?=htmlspecialchars($args['title'])?> :: <?=htmlspecialchars($args['subtitle'])?></title>
<base href="http://<?=$_SERVER['HTTP_HOST']?>/admin/" >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
<link href="/admin/style.css" type="text/css" rel="stylesheet" >
<link rel="shortcut icon" href="/admin/favicon.ico" type="image/x-icon" >
<link rel="shortcut icon" href="/admin/favicon.gif" type="image/gif" >
<script language="javascript" type="text/javascript" src="/jscript/common.js"></script>
<script language="javascript" type="text/javascript" src="/jscript/calendar.js"></script>
<script language="javascript" type="text/javascript" src="/admin/script.js"></script>
<link type="text/css" href="/js/css/jquery-ui-1.8.23.custom.css" rel="stylesheet" />
<script src="/js/jquery-1.8.0.min.js" type="text/javascript"></script>
<script src="/js/jquery-ui-1.8.23.custom.min.js" type="text/javascript"></script>
</head>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
<style>
xmp{line-height:10px; margin:0; padding:5; font-size:11px; font-weight:bold;}
</style>
<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
<!-- head -->
<tr height="90">
<td width="3%"><img src="/admin/images/none.gif" width="5" height="1" alt="" ></td>
<td width="94%">
	<table width="100%" height="90" cellpadding="0" cellspacing="0">
	<tr valign="middle">
	<td align="left"></td>
	<td>
	<div class="global"><a href="/admin/logout.php"><img src="/admin/images/icon_exit.gif" width="28" height="28" alt="Выйти из системы" ></a><br ><a href="/admin/logout.php">выход</a></div>
	</td>
	</tr>
	</table>
</td>
<td width="3%"><img src="/admin/images/none.gif" width="5" height="1" alt="" ></td>
</tr>
<!--/head -->
<!-- middle -->
<tr>
<td><img src="/admin/images/none.gif" width="5" height="1" alt="" ></td>
<td valign="top">

	<!-- main table -->
	<table width="100%" height="100%" cellpadding="0" cellspacing="1" style="border:1px solid #C7DFE3"><tr>
	<td width="250" bgcolor="#EDF8FF" background="/admin/images/bg01.gif" valign="top" id="sidebar" nowrap>

		<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
		<td width="1%"><img src="/admin/images/none.gif" width="1" height="15" alt="" ></td>
		<td width="99%"><img src="/admin/images/none.gif" width="1" height="1" alt="" ></td>
		</tr>

		<!-- modules -->
		<tr>
		<td valign="top"><img src="/admin/images/icon_modules.gif" width="28" height="28" style="margin:0px 10px 0px 15px" alt="Модули" ></td>
		<td valign="top" nowrap>

			<div class="command"><label for="btn_sectionmodules">Модули:</label><a id="btn_sectionmodules" href="javascript:onmodsection('sectionmodules')"><img src="/admin/images/rolldown.gif" width="11" height="11" hspace="4" border="0" ></a>
			<ul id="sectionmodules">
<?foreach($args['modules'] as $i)if($i['section']=='modules'){?>
			<li class="<?=($i['current']? 'selected' : '')?>"><a href="/admin/?mod=<?=htmlspecialchars($i['name'])?>" title="<?=htmlspecialchars($i['descr'])?>"><?=htmlspecialchars($i['title'])?></a></li>
<?}?>
			</ul>
			</div>

		</td>
		</tr>
		<tr><td colspan="2"><img src="/admin/images/none.gif" width="1" height="15" alt="" ></td></tr>

		<!-- tools -->
		<tr>
		<td valign="top"><img src="/admin/images/icon_tools.gif" width="28" height="28" style="margin:0px 10px 0px 15px" alt="Инструменты" ></td>
		<td valign="top" nowrap>

			<div class="command"><label for="btn_sectiontools">Инструменты:</label><a id="btn_sectiontools" href="javascript:onmodsection('sectiontools')"><img src="/admin/images/rolldown.gif" width="11" height="11" hspace="4" border="0" ></a>
			<ul id="sectiontools">
<?foreach($args['modules'] as $i)if($i['section']=='tools'){?>
			<li class="<?=($i['current']? 'selected' : '')?>"><a href="/admin/?mod=<?=htmlspecialchars($i['name'])?>" title="<?=htmlspecialchars($i['descr'])?>"><?=htmlspecialchars($i['title'])?></a></li>
<?}?>
			</ul>
			</div>

		</td>
		</tr>
		<tr><td colspan="2"><img src="/admin/images/none.gif" width="1" height="15" alt="" ></td></tr>

		<!-- struct -->
		<tr>
		<td valign="top"><img src="/admin/images/icon_struct.gif" width="28" height="28" style="margin:0px 10px 0px 15px" alt="Структура сайта" ></td>
		<td valign="top" nowrap>

			<div class="command"><label for="btn_sectionstruct">Структура&nbsp;сайта:</label><a id="btn_sectionstruct" href="javascript:onmodsection('sectionstruct')"><img src="/admin/images/rolldown.gif" width="11" height="11" hspace="4" border="0" ></a>
			<ul id="sectionstruct">
<?foreach($args['modules'] as $i)if($i['section']=='struct'){?>
			<li class="<?=($i['current']? 'selected' : '')?>"><a href="/admin/?mod=<?=htmlspecialchars($i['name'])?>" title="<?=htmlspecialchars($i['descr'])?>"><?=htmlspecialchars($i['title'])?></a></li>
<?}?>
			</ul>
			</div>

		</td>
		</tr>
		<tr><td colspan="2"><img src="/admin/images/none.gif" width="1" height="15" alt="" ></td></tr>

		<!-- access -->
		<tr>
		<td valign="top"><img src="/admin/images/icon_access.gif" width="28" height="28" style="margin:0px 10px 0px 15px" alt="Пользователи" ></td>
		<td valign="top" nowrap>

			<div class="command"><label for="btn_sectionaccess">Пользователи:</label><a id="btn_sectionaccess" href="javascript:onmodsection('sectionaccess')"><img src="/admin/images/rolldown.gif" width="11" height="11" hspace="4" border="0" ></a>
			<ul id="sectionaccess">
<?foreach($args['modules'] as $i)if($i['section']=='access'){?>
			<li class="<?=($i['current']? 'selected' : '')?>"><a href="/admin/?mod=<?=htmlspecialchars($i['name'])?>" title="<?=htmlspecialchars($i['descr'])?>"><?=htmlspecialchars($i['title'])?></a></li>
<?}?>
			</ul>
			</div>

		</td>
		</tr>
		<tr><td colspan="2"><img src="/admin/images/none.gif" width="1" height="15" alt="" ></td></tr>
		</table>

	</td>
	<td width="10" bgcolor="#B9D7DC" onclick="onsidebar();return false;" style="cursor:hand" title="скрыть/показать панель модулей" id="spliter"><a href="javascript:onsidebar()"><img src="/admin/images/splitter.gif" width="9" height="25" border="0" alt="" ></a></td>
	<td  valign="top" bgcolor="#F8FCFF" style="padding:20px 10px 10px 20px; background:url(/admin/images/head_bg2.gif) repeat-x top #F8FCFF" id="workspace">

	<!-- content -->
	<noscript><div class="error">В вашем броузере не включена поддержка скриптов</div></noscript>
	<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
	<td>
	<h1><?=htmlspecialchars($args['subtitle']==''? $args['title'] : $args['subtitle'])?></h1>
	<div class="path"><img src="/admin/images/bullet3.gif" width="9" height="7" alt="" >&nbsp;&nbsp;<a href="/admin/">CMS</a> / <a href="/admin/?mod=<?=htmlspecialchars($args['mod'])?>"><?=htmlspecialchars($args['title'])?></a><?=($args['subtitle']? ' / '.htmlspecialchars($args['subtitle']) : '')?></div>
	</td>
<?if($args['help']){?>
	<td><div class="global"><a href="<?=htmlspecialchars($args['help'])?>"><img src="/admin/images/icon_help.gif" width="28" height="28" alt="Вызвать справку" ></a><br ><a href="<?=htmlspecialchars($args['help'])?>">помощь</a></div></td>
<?}?>
	</tr>
	<tr><td colspan="2"><img src="/admin/images/none.gif" width="1" height="30" alt="" ></td></tr>
<?if(!empty($args['commands'])){?>
	<!-- toolbar -->
	<tr><td colspan="2" class="toolbar" nowrap><img src="/admin/images/toolbar_left.gif" width="8" height="23" style="margin-right:10px"  align="absmiddle" alt="" >
<?foreach($args['commands'] as $i){?>
	<?if($i['path']){?><a href="<?=htmlspecialchars($i['path'])?>"><?=$i['title']?></a><?}else{ echo $i['title']; }?>
	<img src="/admin/images/toolbar_div.gif" width="12" hspace="10" height="23" align="absmiddle" alt="" >
<?}?>

	</td></tr>
	<tr><td colspan="2"><img src="/admin/images/none.gif" width="1" height="10" alt=""></td></tr>
	<!--/toolbar -->
<?}?>
	</table>

<?if($args['mod']){?>
	<!-- module -->
<?if($args['ok']){?><div align="center" class="error">Данные сохранены.</div><?}?>
<?if($args['error']){?><div align="center" class="error">Запись не найдена!</div><?}?>
<table width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td>
            <?=$args['content']?>
        </td>
    </tr>
</table>
	<!--/module -->
<?}elseif($errors['notfound']){?>
	<!-- nofound -->
	<div class="error">Модуль не установлен!</div>
	<!--/nofound -->
<?}elseif($errors['perm']){?>
	<!-- perm -->
	<div class="error">Доступ запрещен!</div>
	<!--/perm -->
<?}else{?>
	<!-- splash -->
	<table width="100%" cellpadding="4" cellspacing="2" class="table">
	<tr><th colspan="2" style="font-size:1.25em; font-weight:bold; text-align:left;">Модули</th></tr>
<?foreach($args['modules'] as $i)if($i['section']=='modules'){?>
	<tr>
	<td><a href="/admin/?mod=<?=htmlspecialchars($i['name'])?>" style="margin-left:20px"><?=htmlspecialchars($i['title'])?></a></td>
	<td>&nbsp;<?=htmlspecialchars($i['descr'])?></td>
	</tr>
<?}?>
	<tr><th colspan="2" style="font-size:1.25em; font-weight:bold; text-align:left;">Инструменты</th></tr>
<?foreach($args['modules'] as $i)if($i['section']=='tools'){?>
	<tr>
	<td><a href="/admin/?mod=<?=htmlspecialchars($i['name'])?>" style="margin-left:20px"><?=htmlspecialchars($i['title'])?></a></td>
	<td>&nbsp;<?=htmlspecialchars($i['descr'])?></td>
	</tr>
<?}?>
	<tr><th colspan="2" style="font-size:1.25em; font-weight:bold; text-align:left;">Структура сайта</th></tr>
<?foreach($args['modules'] as $i)if($i['section']=='struct'){?>
	<tr>
	<td><a href="/admin/?mod=<?=htmlspecialchars($i['name'])?>" style="margin-left:20px"><?=htmlspecialchars($i['title'])?></a></td>
	<td>&nbsp;<?=htmlspecialchars($i['descr'])?></td>
	</tr>
<?}?>
	<tr><th colspan="2" style="font-size:1.25em; font-weight:bold; text-align:left;">Пользователи</th></tr>
<?foreach($args['modules'] as $i)if($i['section']=='access'){?>
	<tr>
	<td><a href="/admin/?mod=<?=htmlspecialchars($i['name'])?>" style="margin-left:20px"><?=htmlspecialchars($i['title'])?></a></td>
	<td>&nbsp;<?=htmlspecialchars($i['descr'])?></td>
	</tr>
<?}?>
	</table>
	<!--/splash -->
<?}?>
	<!--/content -->

	</td>
	</tr></table>
	<!--/main table -->

</td>
<td><img src="/admin/images/none.gif" width="5" height="1" alt="" ></td>
</tr>
<!--/middle -->
<!-- foot -->
<tr height="90">
<td><img src="/admin/images/none.gif" width="5" height="1" alt="" /></td>
<td align="center" valign="bottom" class="copy" nowrap></td>
<td><img src="/admin/images/none.gif" width="5" height="1" alt="" /></td>
</tr>
<!--/foot -->
</table>
</html>
