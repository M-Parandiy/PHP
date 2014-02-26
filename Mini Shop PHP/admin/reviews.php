<?php

defined('RUNNING') or die('Restricted access');


/** DELETE **/
if(isset($_GET['del_review']))
{
    $gDb->delete('reviews', array('id' => $_GET['del_review']));
}

$tpl_all = new FastTemplate('templates/reviews');
$tpl_all ->DefineTemplate(array('main' => 'main.htm'));

/** TPL_PAGES **/
$stores_cat = $gDb->fetchAll("SELECT * FROM reviews");
if($stores_cat)
{
    $tpl_cat = new FastTemplate('templates/reviews/cat');
    $tpl_cat->DefineTemplate(array('main' => 'main.htm', 'unit' => 'unit.htm'));
    
    foreach($stores_cat as $cat)
    {
        $tpl_cat->assign(array('ID' => $cat['id'],
                               'NAME' => $cat['name'],
                               'LINKEDIT' => './?section=add_review&id='.$cat['id']));
        $tpl_cat->parse('UNIT', '.unit');
    }
    $tpl_cat->parse('MAIN', 'main');
    $tpl_all->assign('PAGES', $tpl_cat->fetch('MAIN'));
}
else
{
    $tpl_all->assign('PAGES', '');
}


$tpl_all->parse('MAIN', 'main');
$gTpl->assign('CONTENT', $tpl_all->fetch('MAIN'));

?>