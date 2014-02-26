<?php

include('config.php');
include('includes/class.Database.php');
include('includes/class.FastTemplate.php');

$gTpl = new FastTemplate('templates/store');
$gTpl ->DefineTemplate(array('main' => 'main.htm',
                                'newprice' => 'newprice.htm',
                                'price' => 'price.htm',
                                'img' => 'img.htm'));
$id = $_GET['id'];

$data = $gDb->fetchRow("SELECT * FROM stores WHERE id='$id' AND visible=1");
if(count($data) == 0)
{
    header('Location: ./');
}

$gTpl->assign(array('ID' => $data['id'],
                       'STORENAME' => $data['name'],
                       'DESCRIPTION' => $data['descr'],
                       'SEO_TITLE' => $data['seo_title'],
                       'SEO_KEYWORDS' => $data['seo_keywords'],
                       'SEO_DESCRIPTION' => $data['seo_description']));

if(($data['newprice']*1) > 0)
{
    $gTpl->assign(array('NEWPRICE' => $data['newprice'],
                             'PRICE' => $data['price']));
    $gTpl->parse('PRICEOFFER', 'newprice');
}
else
{
    $gTpl->assign(array('PRICE' => $data['price']));
    $gTpl->parse('PRICEOFFER', 'price');
} 


/** IMAGES **/
if ($dir = @opendir($_SERVER['DOCUMENT_ROOT'] . "/files/store_".$id))  {
    $img_isset = false;
     while (false !== ($file = readdir($dir))) {         
         if ($file == "." || $file == ".." || (is_dir($_SERVER['DOCUMENT_ROOT'] . "/files/store_".$id."/".$file))) continue; 
         
         $gTpl->assign('SRC', "/files/store_".$id.'/'.$file);
         $gTpl->assign('IMGNAME', $file);
         $gTpl->parse('IMAGES', '.img');
         $img_isset = true;
     } 
     closedir($dir); 
     if(!$img_isset)
     {
        $gTpl->assign('IMAGES', '');
     }
}
else
{
    $gTpl->assign('IMAGES', '');
}        


/** OPTIONS **/
$data = $gDb->fetchAll("SELECT * FROM options");

if($data && count($data) != 0)
{
    foreach($data as $d)
    {
        $gTpl->assign(strtoupper($d['name']), $d['value']);
    }
}
$gTpl->assign('SITEURL', 'http://'.$_SERVER['HTTP_HOST']);

$gTpl->parse('MAIN', 'main');
$gTpl->fastPrint('MAIN');

?>