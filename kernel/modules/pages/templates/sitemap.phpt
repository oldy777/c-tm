
<style>
    .sitemap {display: block; font-size: 16px; padding: 30px 0 10px 20px; margin-left: 50px;}
    .sitemap li, .sitemap ul {display: block}
    .sitemap li {margin: 10px 0 0 20px}
    .sitemap li a:hover{color:#4E5769}
</style>
<div >
    <h1 class="title-indent">Карта сайта </h1>
       <ul class="sitemap">
           <?$tmp = $args['pre'] ? $args['items'][1]:$args['items'][0] ?>
           <?foreach($tmp as $v){?>
                <li>
                    <a href="<?=$v['fullpath']?>"><?=$v['title']?></a>
                    <?if(isset($args['items'][$v['id']])){?>
                        <ul>
                            <?foreach($args['items'][$v['id']] as $v1){?>
                            <li>
                                 <a href="<?=$v1['fullpath']?>"><?=$v1['title']?></a>
                            </li>
                           
                            <?}?>
                        </ul>
                    <?}?>
                </li>
           <?}?>
       </ul>
</div>