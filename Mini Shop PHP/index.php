<?php

define('RUNNING', true);

session_start();

include('config.php');
include('includes/class.Database.php');
include('includes/class.FastTemplate.php');

$gTpl = new FastTemplate('templates');
$gTpl->DefineTemplate(array('main' => 'main.htm'));

$url = parse_url($_SERVER['REQUEST_URI']);
$url_arr = explode( '/', trim($url['path'], '/'));

if($url_arr[0] == 'category')
{
    include('category.php');
}
elseif($url_arr[0] == 'page')
{
    include('page.php');
}
elseif($url_arr[0] == 'store')
{
    include('store.php');
}
elseif($url_arr[0] == '')
{
    include('default.php');
}
else
{
    header('Location: ./');
}

/** MENU CATEGORIES **/
$stores_cat = $gDb->fetchAll("SELECT * FROM topic");
if($stores_cat)
{
    $tpl_cat = new FastTemplate('templates/menu_cats');
    $tpl_cat->DefineTemplate(array('main' => 'main.htm', 'unit' => 'unit.htm'));
    
    foreach($stores_cat as $cat)
    {
        $tpl_cat->assign(array('ID' => $cat['id'],
                               'NAME' => $cat['name']));
        $tpl_cat->parse('UNIT', '.unit');
    }
    $tpl_cat->parse('MAIN', 'main');
    $gTpl->assign('CATEG', $tpl_cat->fetch('MAIN'));
}
else
{
    $gTpl->assign('CATEG', '');
}

/** MENU PAGES **/
$pages_cat = $gDb->fetchAll("SELECT * FROM pages");
if($pages_cat)
{
    $tpl_pages = new FastTemplate('templates/menu_pages');
    $tpl_pages->DefineTemplate(array('main' => 'main.htm', 'unit' => 'unit.htm'));
    
    foreach($pages_cat as $cat)
    {
        $tpl_pages->assign(array('ID' => $cat['id'],
                               'NAME' => $cat['name']));
        $tpl_pages->parse('UNIT', '.unit');
    }
    $tpl_pages->parse('MAIN', 'main');
    $gTpl->assign('PAGES', $tpl_pages->fetch('MAIN'));
}
else
{
    $gTpl->assign('PAGES', '');
}

/** REVIEWS **/
$pages_cat = $gDb->fetchAll("SELECT * FROM reviews");
if($pages_cat)
{
    $tpl_pages = new FastTemplate('templates/reviews');
    $tpl_pages->DefineTemplate(array('main' => 'main.htm', 'unit' => 'unit.htm'));
    
    foreach($pages_cat as $cat)
    {
        $tpl_pages->assign(array('DESCR' => $cat['descr'],
                               'NAME' => $cat['name']));
        $tpl_pages->parse('UNIT', '.unit');
    }
    $tpl_pages->parse('MAIN', 'main');
    $gTpl->assign('REVIEWS', $tpl_pages->fetch('MAIN'));
}
else
{
    $gTpl->assign('REVIEWS', '');
}

/** COUNTER **/
$data_counter = unserialize(file_get_contents('./admin/counter.cfg'));
$tpl_counter =  new FastTemplate('templates/counter');
if($data_counter['status'] == 1)
{
    $tpl_counter->DefineTemplate(array('main' => 'main.htm'));
    
    $tpl_counter->assign(array('DAYS' => $data_counter['days'],
                               'MONTH' => $data_counter['month'],
                               'YEAR' => $data_counter['years'],
                               'HOURS' => $data_counter['hours'],
                               'MINUTES' => $data_counter['minutes'],
                               'SECONDS' => $data_counter['seconds']));
    
    $tpl_counter->parse('MAIN', 'main');
    $gTpl->assign('COUNTER', $tpl_counter->fetch('MAIN'));
}
else
{
    $gTpl->assign('COUNTER', '');
}

$data = $gDb->fetchAll("SELECT * FROM options");

if($data && count($data) != 0)
{
    foreach($data as $d)
    {
        $gTpl->assign(strtoupper($d['name']), $d['value']);
        $op[$d['name']] = $d['value'];
    }
}

if($url_arr[0] == '')
{
    $gTpl->assign(array('SEO_TITLE' => $op['homeseotitle'],
                        'SEO_KEYWORDS' => $op['homeseokeywords'],
                        'SEO_DESCRIPTION' => $op['homeseodescription']));
}

$gTpl->assign('SITEURL', 'http://'.$_SERVER['HTTP_HOST']);

$gTpl->Parse('MAIN', 'main');

$gTpl->FastPrint('MAIN');

?>