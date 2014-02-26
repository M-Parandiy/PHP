<?php

defined('RUNNING') or die('Restricted access');


/** DELETE **/
if(isset($_GET['del_order']))
{
    $gDb->delete('orders', array('id' => $_GET['del_order']));
    $gDb->delete('orders_store', array('order_id' => $_GET['del_order']));
}

$tpl_all = new FastTemplate('templates/orders');
$tpl_all ->DefineTemplate(array('main' => 'main.htm'));

/** TPL_ORDERS **/
$stores_cat = $gDb->fetchAll("SELECT * FROM orders");
if($stores_cat)
{
    $tpl_cat = new FastTemplate('templates/orders/cat');
    $tpl_cat->DefineTemplate(array('main' => 'main.htm', 'unit' => 'unit.htm', 'stores' => 'stores.htm', 'sub' => 'sub.htm'));
    
    foreach($stores_cat as $cat)
    {
        $tpl_cat->assign(array('ID' => $cat['id'],
                               'FIO' => $cat['fio'],
                               'PHONE' => $cat['phone'],
                               'EMAIL' => $cat['email'],
                               'SUMA' => $cat['suma']));
        $stores = $gDb->fetchAll("SELECT stores.name, orders_store.suma, orders_store.how FROM stores, orders_store WHERE stores.id=orders_store.store_id AND orders_store.order_id=".$cat['id']);
        if($stores)
        {
            $tpl_sub = new FastTemplate('templates/orders/cat/stores');
            $tpl_sub->DefineTemplate(array('main' => 'main.htm', 'unit' => 'unit.htm'));
            foreach($stores as $st)
            {
                $tpl_sub->assign(array('SUBNAME' => $st['name'],
                                       'SUBSUMA' => $st['suma'],
                                       'SUBHOW' => $st['how']));
                $tpl_sub->parse('UNIT', '.unit');
            }
            $tpl_sub->parse('MAIN', 'main');
            $tpl_cat->assign('STORES', $tpl_sub->fetch('MAIN'));
        }
        else
        {
            $tpl_cat->assign('STORES', '');
        }
        
        $tpl_cat->parse('UNIT', '.unit');
    }
    $tpl_cat->parse('MAIN', 'main');
    $tpl_all->assign('ORDERS', $tpl_cat->fetch('MAIN'));
}
else
{
    $tpl_all->assign('ORDERS', '');
}


$tpl_all->parse('MAIN', 'main');
$gTpl->assign('CONTENT', $tpl_all->fetch('MAIN'));

?>