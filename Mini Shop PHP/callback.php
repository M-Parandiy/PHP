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
    
    
    /** MAILS **/
    $adm = $gDb->fetchRow("SELECT value FROM options WHERE name='adminemail'");
    mail($adm[0], "Новый заказ консультации", "Имя: $fio\nТелефон: $phone\nE-mail: $email\n");
    
    if(preg_match('|([a-z0-9_\.\-]{1,20})@([a-z0-9\.\-]{1,20})\.([a-z]{2,4})|is', $email))
    {
        mail($email, "Вы заказали консультацию в интернет магазине", "Имя: $fio\nТелефон: $phone\nE-mail: $email\n");
    }
    
    $gTpl = new FastTemplate('templates/callback');
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


$gTpl = new FastTemplate('templates/callback');
$gTpl->DefineTemplate(array('main' => 'main.htm'));


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