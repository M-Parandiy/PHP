<?php

define('RUNNING', true);

session_start();

include('../config.php');
include('../includes/class.Database.php');
include('../includes/class.FastTemplate.php');

if(isset($_GET['exit']))
{
    session_destroy();
    header('Location: ./');
}

if(!isset($_SESSION['login']) && !isset($_SESSION['password']))
{
    include('auth.php');
    exit();
}

$gTpl = new FastTemplate('templates');
$gTpl->DefineTemplate(array('main' => 'main.htm'));

if($_GET['section'] == 'stores')
{
    include('stores.php');
}
elseif($_GET['section'] == 'pages')
{
    include('pages.php');
}
elseif($_GET['section'] == 'add_page')
{
    include('add_page.php');
}
elseif($_GET['section'] == 'add_storecat')
{
    include('add_storecat.php');
}
elseif($_GET['section'] == 'add_store')
{
    include('add_store.php');
}
elseif($_GET['section'] == 'orders')
{
    include('orders.php');
}
elseif($_GET['section'] == 'reviews')
{
    include('reviews.php');
}
elseif($_GET['section'] == 'add_review')
{
    include('add_review.php');
}
elseif($_GET['section'] == 'options')
{
    include('options.php');
}
elseif($_GET['section'] == 'counter')
{
    include('counter.php');
}
else
{
    include('default.php');
}

$gTpl->Parse('MAIN', 'main');

$gTpl->FastPrint('MAIN');

?>