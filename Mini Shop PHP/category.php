<?php

defined('RUNNING') or die('Restricted access');

if(!isset($url_arr[1]))
{
    header('Location: ./');
}

$tpl_all = new FastTemplate('templates/category');
$tpl_all ->DefineTemplate(array('main' => 'main.htm'));

$data_page = $gDb->fetchRow("SELECT * FROM topic WHERE id='$url_arr[1]'");

if(count($data_page) == 0)
{
    header('Location: ./');
}

$tpl_all->Assign(array('CATNAME' => $data_page['name'],
                       'CATDESCR' => $data_page['descr']));

$data = $gDb->fetchAll("SELECT stores.* FROM stores, topic_stores WHERE stores.id=topic_stores.store_id AND topic_stores.topic_id='$url_arr[1]' AND stores.visible=1 ORDER BY locomotiv DESC");

if($data && count($data) > 0)
{
    $tpl_goods = new FastTemplate('templates/goodslist');
    $tpl_goods->DefineTemplate(array('main' => 'main.htm',
                                     'unit' => 'unit.htm',
                                     'unit_loc' => 'unit_loc.htm',
                                     'newprice' => 'newprice.htm',
                                     'price' => 'price.htm',
                                     'betweenrow' => 'betweenrow.htm'));
    $i = 1;
    foreach($data as $store)
    {
        if($i == 2)
        {
            $tpl_goods->parse('BETWEENROW', 'betweenrow');
            $i = 0;
        }
        else
        {
            $tpl_goods->assign('BETWEENROW', '');
        }
        $tpl_goods->assign(array('ID' => $store['id'],
                                 'NAME' => $store['name'],
                                 'SHORTDESCR' => $store['short_descr']));
        
        if(($store['newprice']*1) > 0)
        {
            $tpl_goods->assign(array('NEWPRICE' => $store['newprice'],
                                     'PRICE' => $store['price']));
            $tpl_goods->parse('PRICEOFFER', 'newprice');
        }
        else
        {
            $tpl_goods->assign(array('PRICE' => $store['price']));
            $tpl_goods->parse('PRICEOFFER', 'price');
        }             
        
        if($store['locomotiv'] == 1)
        {
            $tpl_goods->parse('BETWEENROW', 'betweenrow');
            $tpl_goods->parse('UNIT', '.unit_loc');
            $i = 0;
        }
        else
        {
            $tpl_goods->parse('UNIT', '.unit');
        }
        
        $i++;
    }
    
    $tpl_goods->parse('MAIN', 'main');
    $tpl_all->assign('GOODS', $tpl_goods->fetch('MAIN'));
}
else
{
    $tpl_all->assign('GOODS', '');
}


$tpl_all->parse('MAIN', 'main');
$gTpl->assign('CONTENT', $tpl_all->fetch('MAIN'));
$gTpl->assign(array('SEO_TITLE' => $data_page['seo_title'],
                    'SEO_KEYWORDS' => $data_page['seo_keywords'],
                    'SEO_DESCRIPTION' => $data_page['seo_description']));

?>