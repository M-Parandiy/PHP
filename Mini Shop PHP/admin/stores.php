<?php

defined('RUNNING') or die('Restricted access');

$tpl_all = new FastTemplate('templates/stores');
$tpl_all ->DefineTemplate(array('main' => 'main.htm'));

/** DELETE **/
if(isset($_GET['del_cat']))
{
    $gDb->delete('topic', array('id' => $_GET['del_cat']));
    $gDb->delete('topic_stores', array('topic_id' => $_GET['del_cat']));
}
if(isset($_GET['del_store']))
{
    $gDb->delete('stores', array('id' => $_GET['del_store']));
    $gDb->delete('topic_stores', array('store_id' => $_GET['del_store']));
    $gDb->delete('stores_add', array('store_id' => $_GET['del_cat']));
    $gDb->delete('stores_add', array('store_add_id' => $_GET['del_cat']));
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/files/store-" . $_GET['del_store'] . '.jpg'))
    {
        unlink($_SERVER['DOCUMENT_ROOT'] . "/files/store-" . $_GET['del_store'] . '.jpg');
    }
}

/** TPL_CATEG **/
$stores_cat = $gDb->fetchAll("SELECT * FROM topic");
if($stores_cat)
{
    $tpl_cat = new FastTemplate('templates/stores/cat');
    $tpl_cat->DefineTemplate(array('main' => 'main.htm', 'unit' => 'unit.htm'));
    
    foreach($stores_cat as $cat)
    {
        $tpl_cat->assign(array('ID' => $cat['id'],
                               'NAME' => $cat['name'],
                               'LINKEDIT' => './?section=add_storecat&id='.$cat['id']));
        $tpl_cat->parse('UNIT', '.unit');
    }
    $tpl_cat->parse('MAIN', 'main');
    $tpl_all->assign('CATEG', $tpl_cat->fetch('MAIN'));
}
else
{
    $tpl_all->assign('CATEG', '');
}



/** TPL_STORES **/
if(isset($_GET['cat']))
{
    $sql = 'SELECT stores.* FROM stores, topic_stores WHERE topic_stores.store_id=stores.id AND topic_stores.topic_id='.$_GET['cat'];
}
else
{
    $sql = "SELECT * FROM stores";
}
$stores_cat = $gDb->fetchAll($sql);
if($stores_cat)
{
    $tpl_cat = new FastTemplate('templates/stores/store');
    $tpl_cat->DefineTemplate(array('main' => 'main.htm', 'unit' => 'unit.htm'));
    
    foreach($stores_cat as $cat)
    {
        $tpl_cat->assign(array('NAME' => $cat['name'],
                               'ID' => $cat['id'],
                               'LINKEDIT' => './?section=add_store&id='.$cat['id']));
        $tpl_cat->parse('UNIT', '.unit');
    }
    $tpl_cat->parse('MAIN', 'main');
    $tpl_all->assign('STORE', $tpl_cat->fetch('MAIN'));
}
else
{
    $tpl_all->assign('STORE', '');
}

$tpl_all->parse('MAIN', 'main');
$gTpl->assign('CONTENT', $tpl_all->fetch('MAIN'));

?>