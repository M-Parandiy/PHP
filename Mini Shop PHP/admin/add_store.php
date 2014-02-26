<?php

defined('RUNNING') or die('Restricted access');

if(isset($_POST['action']))
{
    if($_POST['action'] == 'add')
    {
        $name = mysql_real_escape_string($_POST['name']);
        $short_descr = mysql_real_escape_string($_POST['short_descr']);
        $descr = mysql_real_escape_string($_POST['descr']);
        $price = mysql_real_escape_string($_POST['price']);
        $newprice = mysql_real_escape_string($_POST['newprice']);
        $seotitle = mysql_real_escape_string($_POST['seotitle']);
        $seokeywords = mysql_real_escape_string($_POST['seokeywords']);
        $seodescription = mysql_real_escape_string($_POST['seodescription']);
        
        if(isset($_POST['visible']))
        {
            $visible = '1';
        }
        else
        {
            $visible = '0';
        }
        
        if(isset($_POST['locomotiv']))
        {
            $locomotiv = '1';
        }
        else
        {
            $locomotiv = '0';
        }
        
        $id = $gDb->insert(array('name' => $name,
                                 'short_descr' => $short_descr,
                                 'descr' => $descr,
                                 'price' => $price,
                                 'newprice' => $newprice,
                                 'visible' => $visible,
                                 'locomotiv' => $locomotiv,
                                 'seo_title' => $seotitle,
                                 'seo_keywords' => $seokeywords,
                                 'seo_description' => $seodescription), 'stores');
        
        if(isset($_POST['cat']) && count($_POST['cat'] > 0))
        {
            foreach($_POST['cat'] as $cat)
            {
                $gDb->insert(array('topic_id' => $cat, 'store_id' => $id), 'topic_stores');
            }
        }
        
        if(isset($_POST['add']) && count($_POST['add'] > 0))
        {
            foreach($_POST['add'] as $add)
            {
                $gDb->insert(array('store_add_id' => $add, 'store_id' => $id), 'stores_add');
            }
        }
        
        if (isset($_FILES['image']))
        {
            if (is_uploaded_file($_FILES["image"]["tmp_name"]))
            {
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/files/store-" . $id . '.jpg'))
                {
                    unlink($_SERVER['DOCUMENT_ROOT'] . "/files/store-" . $id . '.jpg');
                }
                move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/files/" . $_FILES["image"]["name"]);
                rename($_SERVER['DOCUMENT_ROOT'] . "/files/" . $_FILES["image"]["name"], $_SERVER['DOCUMENT_ROOT'] .
                    "/files/store-" . $id . '.jpg');
            }
        }
    }
    elseif($_POST['action'] == 'edit')
    {
        $id = $_GET['id'];
        $name = mysql_real_escape_string($_POST['name']);
        $short_descr = mysql_real_escape_string($_POST['short_descr']);
        $descr = mysql_real_escape_string($_POST['descr']);
        $price = mysql_real_escape_string($_POST['price']);
        $newprice = mysql_real_escape_string($_POST['newprice']);
        $seotitle = mysql_real_escape_string($_POST['seotitle']);
        $seokeywords = mysql_real_escape_string($_POST['seokeywords']);
        $seodescription = mysql_real_escape_string($_POST['seodescription']);
        
        if(isset($_POST['visible']))
        {
            $visible = '1';
        }
        else
        {
            $visible = '0';
        }
        if(isset($_POST['locomotiv']))
        {
            $locomotiv = '1';
        }
        else
        {
            $locomotiv = '0';
        }
        
        $gDb->update(array('name' => $name,
                           'short_descr' => $short_descr,
                           'descr' => $descr,
                           'price' => $price,
                           'newprice' => $newprice,
                           'visible' => $visible,
                           'locomotiv' => $locomotiv,
                           'seo_title' => $seotitle,
                           'seo_keywords' => $seokeywords,
                           'seo_description' => $seodescription),'stores', array('id' => $id));
        if(isset($_POST['cat']) && count($_POST['cat'] !== 0))
        {
            foreach($_POST['cat'] as $cat)
            {
                if($gDb->countRows('topic_stores', array('topic_id' => $cat, 'store_id' => $id)) == 0)
                {
                    $gDb->insert(array('topic_id' => $cat, 'store_id' => $id), 'topic_stores');
                }
                $cats = $gDb->fetchAll("SELECT id FROM topic");
                foreach($cats as $cat)
                {
                    if(!in_array($cat['id'], $_POST['cat']))
                    {
                        $gDb->delete('topic_stores', array('topic_id' => $cat['id'], 'store_id' => $id));
                    }
                }
            }
        }
        else
        {
            $gDb->delete('topic_stores', array('store_id' => $id));
        }
        
        if(isset($_POST['add']) && count($_POST['add'] !== 0))
        {
            foreach($_POST['add'] as $add)
            {
                if($gDb->countRows('stores_add', array('store_add_id' => $add, 'store_id' => $id)) == 0)
                {
                    $gDb->insert(array('store_add_id' => $add, 'store_id' => $id), 'stores_add');
                }
                $adds = $gDb->fetchAll("SELECT id FROM stores");
                foreach($adds as $add)
                {
                    if(!in_array($add['id'], $_POST['add']))
                    {
                        $gDb->delete('stores_add', array('store_add_id' => $add['id'], 'store_id' => $id));
                    }
                }
            }
        }
        else
        {
            $gDb->delete('stores_add', array('store_id' => $id));
        }
        
        if ($dir = opendir($_SERVER['DOCUMENT_ROOT'] . "/files/store_".$id))  {
             while (false !== ($file = readdir($dir))) {         
                 if ($file == "." || $file == ".." || (is_dir($_SERVER['DOCUMENT_ROOT'] . "/files/store_".$id."/".$file))) continue; 
                 
                 if(is_array($_POST['src']) && in_array($file, $_POST['src']))
                 {
                    
                 }
                 else
                 {
                    unlink($_SERVER['DOCUMENT_ROOT'] . "/files/store_".$id."/".$file);
                 }
             } 
             closedir($dir); 
        }
        
        if(isset($_POST['src']))
        {
            
        }
        
        if(isset($_FILES) && count($_FILES) >= 1)
        {
            if(!is_dir($_SERVER['DOCUMENT_ROOT'] . "/files/store_".$id))
            {
                mkdir($_SERVER['DOCUMENT_ROOT'] . "/files/store_".$id);
            }
            
            $count = count($_FILES['image'])-1;
            for($i = 0; $i <= $count; $i++)
            {
                //echo $_FILES['image']['name'][$i];
                //print_r($_FILES['image']);
                if (is_uploaded_file($_FILES["image"]["tmp_name"][$i]))
                {
                    $extension = strtolower(pathinfo($_FILES['image']['name'][$i], PATHINFO_EXTENSION));
                    $filename = 'store_'.$i.'_'.time().'.'.$extension;
                    
                    move_uploaded_file($_FILES["image"]["tmp_name"][$i], $_SERVER['DOCUMENT_ROOT'] . "/files/store_".$id.'/' . $_FILES["image"]["name"][$i]);
                    rename($_SERVER['DOCUMENT_ROOT'] . "/files/store_".$id.'/' . $_FILES["image"]["name"][$i], $_SERVER['DOCUMENT_ROOT'] .
                        "/files/store_".$id.'/'.$filename);
                }
            }
        }
        
        if (isset($_FILES['image_store']))
        {
            if (is_uploaded_file($_FILES["image_store"]["tmp_name"]))
            {
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/files/store-" . $id . '.jpg'))
                {
                    unlink($_SERVER['DOCUMENT_ROOT'] . "/files/store-" . $id . '.jpg');
                }
                move_uploaded_file($_FILES["image_store"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/files/" . $_FILES["image_store"]["name"]);
                rename($_SERVER['DOCUMENT_ROOT'] . "/files/" . $_FILES["image_store"]["name"], $_SERVER['DOCUMENT_ROOT'] .
                    "/files/store-" . $id . '.jpg');
            }
        }
    }
}

$tpl_all = new FastTemplate('templates/stores/add_store');
$tpl_all->DefineTemplate(array('main' => 'main.htm', 'cat' => 'cat.htm', 'add' => 'add.htm', 'img' => 'img.htm'));

if($_GET['id'])
{
    $id = $_GET['id'];
    
    $file = $_SERVER['DOCUMENT_ROOT'] . "/files/store-" . $id . '.jpg';
    
    if(is_file($file))
    {
        $file = "/files/store-" . $id . '.jpg';
    }
    else
    {
        $file = "/files/store-default.jpg";
    }
    
    $store = $gDb->fetchRow("SELECT * FROM stores WHERE id='$id'");
    $tpl_all->assign(array('STORENAME' => $store['name'],
                           'STORESHORTDESCR' => $store['short_descr'],
                           'STOREDESCR' => $store['descr'],
                           'STOREPRICE' => $store['price'],
                           'STORENEWPRICE' => $store['newprice'],
                           'SEOTITLE' => $store['seo_title'],
                           'SEOKEYWORDS' => $store['seo_keywords'],
                           'SEODESCRIPTION' => $store['seo_description'],
                           'ACTION' => 'edit',
                           'IMAGELINK' => $file));
    
    if($store['visible'] == 1)
    {
        $tpl_all->assign('CHECKVIS', 'checked');
    }
    else{
        $tpl_all->assign('CHECKVIS', '');
    }
    
    if($store['locomotiv'] == 1)
    {
        $tpl_all->assign('CHECKLOC', 'checked');
    }
    else{
        $tpl_all->assign('CHECKLOC', '');
    }
}
else
{
    $file = "/files/store-default.jpg";
    
    $tpl_all->assign(array('STORENAME' => '',
                           'STORESHORTDESCR' => '',
                           'STOREDESCR' => '',
                           'STOREPRICE' => '',
                           'STORENEWPRICE' => '',
                           'SEOTITLE' => '',
                           'SEOKEYWORDS' => '',
                           'SEODESCRIPTION' => '',
                           'ACTION' => 'add',
                           'IMAGELINK' => $file,
                           'CHECKVIS' => 'checked',
                           'CHECKLOC' => 'checked'));
}

$cats = $gDb->fetchAll("SELECT id, name FROM topic");
if(count($cats) > 0)
{
    foreach($cats as $cat)
    {
        if($_GET['id'] && $gDb->countRows('topic_stores', array('topic_id' => $cat['id'], 'store_id' => $_GET['id'])) > 0)
        {
            $tpl_all->assign('CHECK', 'checked');
        }
        else
        {
            $tpl_all->assign('CHECK', '');
        }
        $tpl_all->assign(array('ID' => $cat['id'], 'NAME' => $cat['name']));
        $tpl_all->parse('CAT', '.cat');
    }
}
else
{
    $tpl_all->assign('CAT', '');
}

$add = $gDb->fetchAll("SELECT id, name FROM stores");
if(count($add) > 0)
{
    foreach($add as $ad)
    {
        if($_GET['id'] == $ad['id']) continue;
        
        if($_GET['id'] && $gDb->countRows('stores_add', array('store_id' => $_GET['id'], 'store_add_id' => $ad['id'])) > 0)
        {
            $tpl_all->assign('CHECK', 'checked');
        }
        else
        {
            $tpl_all->assign('CHECK', '');
        }
        $tpl_all->assign(array('ID' => $ad['id'], 'NAME' => $ad['name']));
        $tpl_all->parse('ADD', '.add');
    }
}
else
{
    $tpl_all->assign('ADD', '');
}

if ($dir = @opendir($_SERVER['DOCUMENT_ROOT'] . "/files/store_".$id))  {
    $img_isset = false;
     while (false !== ($file = readdir($dir))) {         
         if ($file == "." || $file == ".." || (is_dir($_SERVER['DOCUMENT_ROOT'] . "/files/store_".$id."/".$file))) continue; 
         
         $tpl_all->assign('SRC', "/files/store_".$id.'/'.$file);
         $tpl_all->assign('IMGNAME', $file);
         $tpl_all->parse('IMAGES', '.img');
         $img_isset = true;
     } 
     closedir($dir); 
     if(!$img_isset)
     {
        $tpl_all->assign('IMAGES', '');
     }
}
else
{
    $tpl_all->assign('IMAGES', '');
}


$tpl_all->parse('MAIN', 'main');
$gTpl->assign('CONTENT', $tpl_all->fetch('MAIN'));

?>