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
                           'seo_description' => $seodescription), 'topic');
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
                           'seo_description' => $seodescription), 'topic', array('id' => $id));
    }
}

$tpl_all = new FastTemplate('templates/stores/add_storecat');
$tpl_all ->DefineTemplate(array('main' => 'main.htm'));

if($_GET['id'])
{
    $id = $_GET['id'];
    
    $topic = $gDb->fetchRow("SELECT * FROM topic WHERE id='$id'");
    
    $tpl_all->assign(array('TOPICNAME' => $topic['name'],
                           'TOPICDESCR' => $topic['descr'],
                           'SEOTITLE' => $topic['seo_title'],
                           'SEOKEYWORDS' => $topic['seo_keywords'],
                           'SEODESCRIPTION' => $topic['seo_description'],
                           'ACTION' => 'edit'));
}
else
{
    $tpl_all->assign(array('TOPICNAME' => '',
                           'TOPICDESCR' => '',
                           'SEOTITLE' => '',
                           'SEOKEYWORDS' => '',
                           'SEODESCRIPTION' => '',
                           'ACTION' => 'add'));
}


$tpl_all->parse('MAIN', 'main');
$gTpl->assign('CONTENT', $tpl_all->fetch('MAIN'));

?>