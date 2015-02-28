
<h1>Новости</h1>

<ul >
    <?foreach($args['items'] as $v){?>
    <li>
        <div class="head"><a href="/news/id-<?=$v['id']?>"><?=$v['title']?></a></div>
        <p><?=$v['anons']?></p>
    </li>
    <?}?>
    
</ul>

<? if ($args['pages'] > 1) { ?>
<div class="b-paginator">
    <table align=center>
        <tr>
            <td>
                <a href="/news/page-<?= $args['page'] > 1 ? ($args['page'] - 1) : $args['page'] ?>" class="prev disabled"><span>предыдущая<br>страница</span><i class="i i-prev"></i></a>
            </td>
            <td>
                <div class="pages">
                    <? for ($i = 1; $i <= $args['pages']; $i++) { ?>
                    <? if ($args['page'] == $i) { ?>
                        <a href="#" class="active"><?= $i ?></a>
                    <? } else { ?>
                        <a href="/news/page-<?= $i ?>"><?= $i ?></a>
                    <?}?>
                    <? if ($i == 1 && ($args['page'] - 1) > 9) { ?>
                        <span>...</span>
                        <? $i = $args['page'] - 9;
                        continue;
                    } ?>
                    <? if ($i > ($args['page'] + 9) && ($args['pages'] - $i) > 1) { ?>
                            <span>...</span>
                            <a href="/news/page-<?= $args['pages'] ?>"><?= $args['pages'] ?></a>
                        <? break;
                    } ?>
                <? } ?>
                </div>
            </td>
            <td class="last">
                <a href="/news/page-<?= $args['page'] < $args['pages'] ? ($args['page'] + 1) : $args['page'] ?>" class="next"><span>следующая<br>страница</span><i class="i i-next"></i></a>
            </td>
        </tr>
    </table>
</div>
<?}?>