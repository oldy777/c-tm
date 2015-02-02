<!DOCTYPE html>
<html>
<head>
<title><?=htmlspecialchars($args['title'])?> :: <?=htmlspecialchars($args['subtitle'])?></title>
<base href="http://<?=$_SERVER['HTTP_HOST']?>/admin/" >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="/admin/style.css" type="text/css" rel="stylesheet" />
<link href="/admin/sweet-alert.css" type="text/css" rel="stylesheet" />
<link href="/js/css/jquery-ui-1.8.23.custom.css" rel="stylesheet" />

<link rel="shortcut icon" href="/admin/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="/admin/favicon.gif" type="image/gif" />

<script src="/js/jquery-1.8.0.min.js" type="text/javascript"></script>
<script src="/js/jquery-ui-1.8.23.custom.min.js" type="text/javascript"></script>
<script src="/js/jquery.fancybox-1.2.1.pack.js" type="text/javascript"></script>
<script src="/js/datepickerRU.js" type="text/javascript"></script>
<script src="/admin/sweet-alert.min.js"  type="text/javascript"></script>
<script src="/admin/script.js"  type="text/javascript"></script>
</head>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" style="<?=$kernel['bg'] ? 'background: url(/admin/images/'.$kernel['bg'] .'.jpg)':''?>">
<style>
xmp{line-height:10px; margin:0; padding:5; font-size:11px; font-weight:bold;}
</style>
<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
<!-- head -->
<tr height="80" style="background: url('/admin/images/admin_top_x.png') repeat-x;">
<td width="40"><img src="/admin/images/none.gif" width="5" height="1" alt="" ></td>
<td width="250" style="vertical-align: top" class="head_1">
    <div class="logo">
        <img src="/admin/images/c-tm-logo.png" style="margin-left: -79px; margin-top: 8px; float: left; margin-right: 10px" width="80"/>
        Система управления сайтом
        <div class="logo_url"><a target="_blank" href="http://<?=$_SERVER['HTTP_HOST']?>/"><?=$_SERVER['HTTP_HOST']?></a></div>
    </div>
</td>
<!--<td width="10"><img src="/admin/images/none.gif" width="5" height="1" alt="" ></td>-->
<td width="" class="head_4">
    <div class="user_info">
	<span class="user_name">
            <a href="/admin/index.php?mod=users&act=edit&id=<?=$kernel['id_user']?>"><?=$kernel['username']?></a>
        </span>
        <span class="exit">
            <a href="/admin/logout.php">Выйти</a>
        </span>
    </div>
<!--    <table width="100%" height="60" cellpadding="0" cellspacing="0">
        <tr valign="middle">
            <td align="left">&nbsp</td>
            <td>
                <div class="global"><a href="/admin/logout.php"><img src="/admin/images/icon_exit.gif" width="28" height="28" alt="Выйти из системы" ></a><br ><a href="/admin/logout.php">выход</a></div>
                <div class="global"><a href="/"><img src="/admin/images/icon_start.gif" width="28" height="28" alt="На сайт" ></a><br ><a href="/">На сайт</a></div>
            </td>
        </tr>
    </table>-->
</td>
<td width="40"><img src="/admin/images/none.gif" width="5" height="1" alt="" ></td>
</tr>
<!--/head -->
<!-- middle -->
<tr>
<td><img src="/admin/images/none.gif" width="5" height="1" alt="" ></td>
<td valign="top" >

	<!-- main table -->
	<table width="250" height="100%" cellpadding="0" cellspacing="0"  ><tr>
	<td width="250" bgcolor="#EDF8FF" background="/admin/images/bg01.gif" valign="top" id="sidebar" class="col_1_top" nowrap>

		<!--<table width="100%" cellpadding="0" cellspacing="0">-->
<!--		<tr>
		<td width="1%"><img src="/admin/images/none.gif" width="1" height="15" alt="" ></td>
		<td width="99%"><img src="/admin/images/none.gif" width="1" height="1" alt="" ></td>
		</tr>-->

		<!-- modules -->
                
                <table width="100%" cellspacing="0">
                    <tbody>
                        <tr>
                            <td colspan="3" class="menu_tr_first">
                                <h1>Модули</h1>
                            </td>
                        </tr>
                        <?$cnt = 0;$cur=0;foreach($args['modules'] as $k=>$i)
                            if($i['section']=='modules'){
                         ?>
                            <?if($i['current']){$cur = $cnt+1;?>
                                <tr>
                                    <td class="menu_act_before" colspan="3"></td>
                                </tr>
                            <?}?>
                            <tr <?=($i['current']? 'class="menu_act"' : '')?> >
<!--                                <td class="menu_1"><img alt="" src="/admin/images/icons/<?=htmlspecialchars($i['name'])?>.png"></td>-->
                                <td class="menu_2"><a href="/admin/?mod=<?=htmlspecialchars($i['name'])?>" title="<?=htmlspecialchars($i['descr'])?>"><?=htmlspecialchars($i['title'])?></a></td>
                                <td class="menu_3"></td>
                            </tr>
                            <?if($i['current']){?>
                                <tr>
                                    <td class="menu_act_after" colspan="3"></td>
                                </tr>
                            <?}?>
                        <?$cnt++;}?>
                        </tbody>
                </table>
                <?if($cnt != $cur){?>
                <div class="left_sep" ></div>
                <?}?>

			<div class="command"><label for="btn_sectionmodules">Модули:</label><a id="btn_sectionmodules" href="javascript:onmodsection('sectionmodules')"><img src="/admin/images/rolldown.gif" width="11" height="11" hspace="4" border="0" ></a>
			<ul id="sectionmodules">
<?foreach($args['modules'] as $i)if($i['section']=='modules'){?>
			<li class="<?=($i['current']? 'selected' : '')?>"><a href="/admin/?mod=<?=htmlspecialchars($i['name'])?>" title="<?=htmlspecialchars($i['descr'])?>"><?=htmlspecialchars($i['title'])?></a></li>
<?}?>
			</ul>
			</div>

		</td>
		</tr>
		<tr><td colspan="2"><img src="/admin/images/none.gif" width="1" height="15" alt="" ></td></tr>-->

		<!-- tools -->
                <table width="100%" cellspacing="0">
                    <tbody>
                        <tr>
                            <td colspan="3" class="menu_tr_first">
                                <h1>Инструменты</h1>
                            </td>
                        </tr>
                        <?$cnt = 0;$cur=0;foreach($args['modules'] as $k=>$i)
                            if($i['section']=='tools'){
                         ?>
                            <?if($i['current']){$cur = $cnt+1;?>
                                <tr>
                                    <td class="menu_act_before" colspan="3"></td>
                                </tr>
                            <?}?>
                            <tr <?=($i['current']? 'class="menu_act"' : '')?> >
<!--                                <td class="menu_1"><img alt="" src="/admin/images/icons/<?=htmlspecialchars($i['name'])?>.png"></td>-->
                                <td class="menu_2"><a href="/admin/?mod=<?=htmlspecialchars($i['name'])?>" title="<?=htmlspecialchars($i['descr'])?>"><?=htmlspecialchars($i['title'])?></a></td>
                                <td class="menu_3"></td>
                            </tr>
                            <?if($i['current']){?>
                                <tr>
                                    <td class="menu_act_after" colspan="3"></td>
                                </tr>
                            <?}?>
                        <?$cnt++;}?>
                        </tbody>
                </table>
                <?if($cnt != $cur){?>
                <div class="left_sep"></div>
                <?}?>
<!--		<tr>
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
		<tr><td colspan="2"><img src="/admin/images/none.gif" width="1" height="15" alt="" ></td></tr>-->

		<!-- struct -->
                <table width="100%" cellspacing="0">
                    <tbody>
                        <tr>
                            <td colspan="3" class="menu_tr_first">
                                <h1>Структура&nbsp;сайта</h1>
                            </td>
                        </tr>
                        <?$cnt = 0;$cur=0;foreach($args['modules'] as $k=>$i)
                            if($i['section']=='struct'){
                         ?>
                            <?if($i['current']){$cur = $cnt+1;?>
                                <tr>
                                    <td class="menu_act_before" colspan="3"></td>
                                </tr>
                            <?}?>
                            <tr <?=($i['current']? 'class="menu_act"' : '')?> >
<!--                                <td class="menu_1"><img alt="" src="/admin/images/icons/<?=htmlspecialchars($i['name'])?>.png"></td>-->
                                <td class="menu_2"><a href="/admin/?mod=<?=htmlspecialchars($i['name'])?>" title="<?=htmlspecialchars($i['descr'])?>"><?=htmlspecialchars($i['title'])?></a></td>
                                <td class="menu_3"></td>
                            </tr>
                            <?if($i['current']){?>
                                <tr>
                                    <td class="menu_act_after" colspan="3"></td>
                                </tr>
                            <?}?>
                        <?$cnt++;}?>
                        </tbody>
                </table>
                <?if($cnt != $cur){?>
                <div class="left_sep"></div>
                <?}?>
<!--		<tr>
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
		<tr><td colspan="2"><img src="/admin/images/none.gif" width="1" height="15" alt="" ></td></tr>-->

		<!-- access -->
                <table width="100%" cellspacing="0">
                    <tbody>
                        <tr>
                            <td colspan="3" class="menu_tr_first">
                                <h1>Пользователи</h1>
                            </td>
                        </tr>
                        <?$cnt = 0;$cur=0;foreach($args['modules'] as $k=>$i)
                            if($i['section']=='access'){
                         ?>
                            <?if($i['current']){$cur = $cnt+1;?>
                                <tr>
                                    <td class="menu_act_before" colspan="3"></td>
                                </tr>
                            <?}?>
                            <tr <?=($i['current']? 'class="menu_act"' : '')?> >
<!--                                <td class="menu_1"><img alt="" src="/admin/images/icons/<?=htmlspecialchars($i['name'])?>.png"></td>-->
                                <td class="menu_2"><a href="/admin/?mod=<?=htmlspecialchars($i['name'])?>" title="<?=htmlspecialchars($i['descr'])?>"><?=htmlspecialchars($i['title'])?></a></td>
                                <td class="menu_3"></td>
                            </tr>
                            <?if($i['current']){?>
                                <tr>
                                    <td class="menu_act_after" colspan="3"></td>
                                </tr>
                            <?}?>
                        <?$cnt++;}?>
                        </tbody>
                </table>
                <?if($cnt != $cur){?>
                <div class="left_sep"></div>
                <?}?>
<!--		<tr>
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
		<tr><td colspan="2"><img src="/admin/images/none.gif" width="1" height="15" alt="" ></td></tr>-->
                </td></tr>
		</table>

	</td>
	<!--<td width="10" bgcolor="#B9D7DC" onclick="onsidebar();return false;" style="cursor:hand" title="скрыть/показать панель модулей" id="spliter"><a href="javascript:onsidebar()"><img src="/admin/images/splitter.gif" width="9" height="25" border="0" alt="" ></a></td>-->
	<td width="" valign="top" bgcolor="#F8FCFF" style="background-color: #fff; padding:20px 10px 10px 20px; background:url(/admin/images/col_2_x.gif) repeat-x top #fff" id="workspace">

	<!-- content -->
	<noscript><div class="error">В вашем броузере не включена поддержка скриптов</div></noscript>
	<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
	<td>
	<h1><?=htmlspecialchars($args['subtitle']==''? $args['title'] : $args['subtitle'])?></h1>
	<div class="path"><img src="/admin/images/bullet3.gif" width="9" height="7" alt="" >&nbsp;&nbsp;<a href="/admin/">CMS</a>
            <?foreach ($args['mod_parents'] as $v){?>
                / <a href="/admin/?mod=<?=htmlspecialchars($v['name'])?>"><?=htmlspecialchars($v['title'])?></a>
            <?}?>
            / <a href="/admin/?mod=<?=htmlspecialchars($args['mod'])?>"><?=htmlspecialchars($args['title'])?></a>
            <?if(isset($kernel['brums']) && $kernel['brums']){?>
                <?foreach ($kernel['brums'] as $v){?>
                    / <a href="<?=$v['url']?>"><?=htmlspecialchars($v['title'])?></a>
                <?}?>
            <?}?> 
            <?=($args['subtitle']? ' / '.htmlspecialchars($args['subtitle']) : '')?>
        </div>
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
<?if(isFlush()){?>
        <script>
            setTimeout(function(){$('.closeflush').trigger('click');}, 5000);
        </script>
        <div class="alert alert-success">
            <button data-dismiss="alert" class="closeflush" type="button">×</button>
            <?=showFlush();?>
        </div>
<?}?>
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
	<!--</tr></table>-->
	<!--/main table -->

<!--</td>-->
<td class="col_3_top">
   
    <a style="background: url(/admin/images/wood_min.png) no-repeat 100% 0;" class="theme_wood" href="#"></a>
    <a style="background: url(/admin/images/cacomile_min.png) no-repeat 100% 0;" class="theme_cacomile" href="#"></a>
    <a style="background: url(/admin/images/texture_min.png) no-repeat 100% 0;" class="theme_texture" href="#"></a>
    <a style="background: url(/admin/images/water_min.png) no-repeat 100% 0;" class="theme_water" href="#"></a>
    <a style="background: url(/admin/images/weaves_min.png) no-repeat 100% 0;" class="theme_weaves" href="#"></a>
    <a style="background: url(/admin/images/stone_min.png) no-repeat 100% 0;" class="theme_stone" href="#"></a>
    <a style="background: url(/admin/images/space_min.png) no-repeat 100% 0;" class="theme_space" href="#"></a>
    <a style="background: url(/admin/images/metall_min.png) no-repeat 100% 0;" class="theme_metall" href="#"></a>
        <img src="/admin/images/none.gif" width="5" height="1" alt="" >
        <style>
            .col_3_top{vertical-align: top; padding-top: 15px;}
            .col_3_top a:link, .col_3_top a:visited {
        display: block;
        height: 43px;
        overflow-x: hidden;
        overflow-y: hidden;
        width: 21px;
        ;
    }
    .col_3_top a:hover{
        width: 30px;
    }
        </style>
</td>
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

<p id="back-top">
    <a href="#top"><span></span></a>
</p>

</body>
</html>
