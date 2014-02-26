<?php

define('RUNNING', true);

include('config.php');
include('includes/class.Database.php');
include('includes/class.FastTemplate.php');


if(isset($_POST['action']) && $_POST['action'] == 'buy')
{
    $fio = mysql_real_escape_string($_POST['fio']);
    $phone = mysql_real_escape_string($_POST['phone']);
    $email = mysql_real_escape_string($_POST['email']);
    $storeid = $_GET['id'];
    $how = $_POST['how'];
    
    $storelist = "";
    
    $store = $gDb->fetchRow("SELECT * FROM stores WHERE id=".$storeid);
    if(($store['newprice']*1) > 0)
    {
        $storeprice = $store['newprice'];
    }
    else
    {
        $storeprice = $store['price'];
    }
    
    $storeallprice = ($storeprice*1)*($how*1);
    
    $allprice = $storeallprice*1;
    if(isset($_POST['add']))
    {
        foreach($_POST['add'] as $k => $v)
        {
            if($v == 1)
            {
                $temp = $gDb->FetchRow("SELECT * FROM stores WHERE id=".$k);
                $storeadd[] = $temp;
                if(($temp['newprice']*1) > 0)
                {
                    $allprice += ($temp['newprice']*1);
                }
                else
                {
                    $allprice += ($temp['price']*1);
                }
            }
        }
    }
    
    $order_id = $gDb->insert(array('fio' => $fio, 'phone' => $phone, 'email' => $email, 'suma' => $allprice), 'orders');
    
    $gDb->insert(array('order_id' => $order_id, 'store_id' => $store['id'], 'how' => $how, 'suma' => $storeprice), 'orders_store');
    
    $storelist .= "$store[name] - $how \n";
    
    if(isset($storeadd))
    {
        foreach($storeadd as $sa)
        {
            if(($sa['newprice']*1) > 0)
            {
                $gpr = $sa['newprice'];
            }
            else
            {
                $gpr = $sa['price'];
            }
            $gDb->insert(array('order_id' => $order_id, 'store_id' => $sa['id'], 'how' => '1', 'suma' => $gpr), 'orders_store');
            $storelist .= "$sa[name] - 1 \n";
        }
    }
    
    /** MAILS **/
    $adm = $gDb->fetchRow("SELECT value FROM options WHERE name='adminemail'");
    mail($adm[0], "Новый заказ", "Имя: $fio\nТелефон: $phone\nE-mail: $email\nТовары: $storelist\n ID заказа: $order_id");
    
    if(preg_match('|([a-z0-9_\.\-]{1,20})@([a-z0-9\.\-]{1,20})\.([a-z]{2,4})|is', $email))
    {
        mail($email, "Новый заказ", "Имя: $fio\nТелефон: $phone\nE-mail: $email\nТовары: $storelist\n ID заказа: $order_id");
    }
    
    $gTpl = new FastTemplate('templates/buy');
    $gTpl->DefineTemplate(array('main' => 'ok.htm'));
    
    
    /** OPTIONS **/
    $data = $gDb->fetchAll("SELECT * FROM options");
    
    if($data && count($data) != 0)
    {
        foreach($data as $d)
        {
            $gTpl->assign(strtoupper($d['name']), $d['value']);
        }
    }
    
    $gTpl->assign('SITEURL', 'http://'.$_SERVER['HTTP_HOST']);
    $gTpl->Parse('MAIN', 'main');

    $gTpl->FastPrint('MAIN');
    exit();
}


$gTpl = new FastTemplate('templates/buy');
$gTpl->DefineTemplate(array('main' => 'main.htm',
                             'newprice' => 'newprice.htm',
                             'price' => 'price.htm',
                             'add' => 'add.htm'));

$store = $gDb->fetchRow("SELECT * FROM stores WHERE id=".$_GET['id']);

$gTpl->assign(array('ID' => $store['id'],
                    'STORENAME' => $store['name']));

if(($store['newprice']*1) > 0)
{
    $gTpl->assign(array('NEWPRICE' => $store['newprice'],
                             'PRICE' => $store['price']));
    $gTpl->parse('PRICEOFFER', 'newprice');
}
else
{
    $gTpl->assign(array('PRICE' => $store['price']));
    $gTpl->parse('PRICEOFFER', 'price');
}        

/** STOREADD **/
$dataadd = $gDb->fetchAll("SELECT stores.* FROM stores, stores_add WHERE stores_add.store_add_id=stores.id AND stores_add.store_id=".$_GET['id']);
if($dataadd)
{
    foreach($dataadd as $da)
    {
        $gTpl->assign(array('ADDID' => $da['id'],
                            'ADDNAME' => $da['name']));
                            
        if(($da['newprice']*1) > 0)
        {
            $gTpl->assign(array('ADDPRICE' => $da['newprice']));
        }
        else
        {
            $gTpl->assign(array('ADDPRICE' => $da['price']));
        }             
        
        $gTpl->parse('ADD', '.add');
    }
}
else
{
    $gTpl->assign('ADD', '');
}


/** OPTIONS **/
$data = $gDb->fetchAll("SELECT * FROM options");

if($data && count($data) != 0)
{
    foreach($data as $d)
    {
        $gTpl->assign(strtoupper($d['name']), $d['value']);
    }
}

$gTpl->assign('SITEURL', 'http://'.$_SERVER['HTTP_HOST']);

$gTpl->Parse('MAIN', 'main');

$gTpl->FastPrint('MAIN');