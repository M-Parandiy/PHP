<?php

$error = '';
if(!empty($_POST['enter'])) 
{ 
    $login = $_POST['login']; 
    $password = $_POST['password'];
    if($login == ADMIN_LOGIN && $password == ADMIN_PASSWORD)
    {
        $_SESSION['login'] = $login;
        $_SESSION['password'] = $password;
        header('Location: ./');
    }
    else 
    {
        $error = '<div class="error">Неправильный логин или пароль!</div>';
    }
} 

$gTpl = new FastTemplate('templates');
$gTpl->DefineTemplate(array('main' => 'auth.htm'));

$gTpl->assign('ERROR', $error);

$gTpl->Parse('MAIN', 'main');

$gTpl->FastPrint('MAIN');

?>