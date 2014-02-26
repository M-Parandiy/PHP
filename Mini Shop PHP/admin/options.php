<?php

defined('RUNNING') or die('Restricted access');

if(isset($_POST['action']) && $_POST['action'] == 'change')
{
    $values = $_POST;
    unset($values['action']);
    
    foreach($values as $k => $v)
    {
        if($gDb->countRows('options', array('name' => $k)) == 0)
        {
            $gDb->insert(array('name' => $k, 'value' => mysql_real_escape_string($v)), 'options');
        }
        else
        {
            $gDb->update(array('name' => $k, 'value' => $v), 'options', array('name' => $k));
        }
    }
}

$tpl_all = new FastTemplate('templates/options');
$tpl_all->DefineTemplate(array('main' => 'main.htm'));


$data = $gDb->fetchAll("SELECT * FROM options");

if($data && count($data) != 0)
{
    foreach($data as $d)
    {
        $tpl_all->assign(strtoupper($d['name']), $d['value']);
    }
}


$tpl_all->parse('MAIN', 'main');
$gTpl->assign('CONTENT', $tpl_all->fetch('MAIN'));

?>