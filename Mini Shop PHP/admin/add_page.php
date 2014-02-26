<?php

defined('RUNNING') or die('Restricted access');

if(isset($_POST['action']))
{
    if($_POST['action'] == 'add')
    {
        $name = mysql_real_escape_string($_POST['name']);
        $descr = mysql_real_escape_string($_POST['descr']);
        $seotitle = mysql_real_escape_string($_POST['seotitle']);
        $seokeywords = mysql_real_escape_string($_POST['seokeywords']);
        $seodescription = mysql_real_escape_string($_POST['seodescription']);
        $gDb->insert(array('name' => $name,
                           'descr' => $descr,
                           'seo_title' => $seotitle,
                           'seo_keywords' => $seokeywords,
                           'seo_description' => $seodescription), 'pages');
    }
    elseif($_POST['action'] == 'edit')
    {
        $id = $_GET['id'];
        
        $name = mysql_real_escape_string($_POST['name']);
        $descr = mysql_real_escape_string($_POST['descr']);
        $seotitle = mysql_real_escape_string($_POST['seotitle']);
        $seokeywords = mysql_real_escape_string($_POST['seokeywords']);
        $seodescription = mysql_real_escape_string($_POST['seodescription']);
        $gDb->update(array('name' => $name,
                           'descr' => $descr,
                           'seo_title' => $seotitle,
                           'seo_keywords' => $seokeywords,
                           'seo_description' => $seodescription), 'pages', array('id' => $id));
    }
}

$tpl_all = new FastTemplate('templates/pages/add_page');
$tpl_all ->DefineTemplate(array('main' => 'main.htm'));

if($_GET['id'])
{
    $id = $_GET['id'];
    
    $page = $gDb->fetchRow("SELECT * FROM pages WHERE id='$id'");
    
    $tpl_all->assign(array('PAGENAME' => $page['name'],
                           'PAGEDESCR' => $page['descr'],
                           'SEOTITLE' => $page['seo_title'],
                           'SEOKEYWORDS' => $page['seo_keywords'],
                           'SEODESCRIPTION' => $page['seo_description'],
                           'ACTION' => 'edit'));
}
else
{
    $tpl_all->assign(array('PAGENAME' => '',
                           'PAGEDESCR' => '',
                           'SEOTITLE' => '',
                           'SEOKEYWORDS' => '',
                           'SEODESCRIPTION' => '',
                           'ACTION' => 'add'));
}


$tpl_all->parse('MAIN', 'main');
$gTpl->assign('CONTENT', $tpl_all->fetch('MAIN'));

?>