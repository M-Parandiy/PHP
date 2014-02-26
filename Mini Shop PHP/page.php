<?php

defined('RUNNING') or die('Restricted access');

$tpl_all = new FastTemplate('templates/page');
$tpl_all ->DefineTemplate(array('main' => 'main.htm'));

if(!isset($url_arr[1]))
{
    header('Location: ./');
}

$data = $gDb->FetchRow("SELECT * FROM pages WHERE id='$url_arr[1]'");
if(count($data) == 0)
{
    header('Location: ./');
}

$tpl_all->assign(array('NAME' => $data['name'],
                       'DESCR' => $data['descr']));

$tpl_all->parse('MAIN', 'main');
$gTpl->assign('CONTENT', $tpl_all->fetch('MAIN'));
$gTpl->assign(array('SEO_TITLE' => $data['seo_title'],
                    'SEO_KEYWORDS' => $data['seo_keywords'],
                    'SEO_DESCRIPTION' => $data['seo_description']));

?>