<?php

defined('RUNNING') or die('Restricted access');


$tpl_all = new FastTemplate('templates/counter');
$tpl_all ->DefineTemplate(array('main' => 'main.htm'));

if(isset($_POST['action']) && $_POST['action'] == 'save')
{
    if(isset($_POST['status']))
    {
        file_put_contents('counter.cfg', serialize($_POST));
    }
    else
    {
        foreach($_POST as $k => $v)
        {
            $post[$k] = '';
        }
        file_put_contents('counter.cfg', serialize($post));
    }
}

$data = unserialize(file_get_contents('counter.cfg'));

foreach($data as $k =>$v)
{
    if($k == 'status')
    {
        $tpl_all->assign('CHECK', 'checked');
    }
    $tpl_all->assign(strtoupper($k), $v);
}

$tpl_all->parse('MAIN', 'main');
$gTpl->assign('CONTENT', $tpl_all->fetch('MAIN'));

?>