<?php

defined('RUNNING') or die('Restricted access');

if(isset($_POST['action']))
{
    if($_POST['action'] == 'add')
    {
        $name = mysql_real_escape_string($_POST['name']);
        $descr = mysql_real_escape_string($_POST['descr']);
        $gDb->insert(array('name' => $name, 'descr' => $descr), 'reviews');
    }
    elseif($_POST['action'] == 'edit')
    {
        $id = $_GET['id'];
        
        $name = mysql_real_escape_string($_POST['name']);
        $descr = mysql_real_escape_string($_POST['descr']);
        $gDb->update(array('name' => $name, 'descr' => $descr), 'reviews', array('id' => $id));
    }
}

$tpl_all = new FastTemplate('templates/reviews/add_review');
$tpl_all ->DefineTemplate(array('main' => 'main.htm'));

if($_GET['id'])
{
    $id = $_GET['id'];
    
    $page = $gDb->fetchRow("SELECT * FROM reviews WHERE id='$id'");
    
    $tpl_all->assign(array('PAGENAME' => $page['name'],
                           'PAGEDESCR' => $page['descr'],
                           'ACTION' => 'edit'));
}
else
{
    $tpl_all->assign(array('PAGENAME' => '',
                           'PAGEDESCR' => '',
                           'ACTION' => 'add'));
}


$tpl_all->parse('MAIN', 'main');
$gTpl->assign('CONTENT', $tpl_all->fetch('MAIN'));

?>