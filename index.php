<?php
// подключение служебной части пролога
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader;
use Suhanov\Tt;
use Bitrix\Main\Entity;
use \Bitrix\Main\Entity\ExpressionField;
Loader::IncludeModule("suhanov.tt");
// здесь можно задать например, свойство страницы
// с помощью функции $APPLICATION->SetPageProperty
// и обработать затем его в визуальной части эпилога


$objUser = Tt\UserTable::getList(array(
    'select' => array('*', 'LIKE_'=>'LIKES'),
    'filter' => array('=ID' => 1),
));
$arUser = $objUser->fetchAll();
?>

<pre><? print_r($arUser); ?></pre>

<?
// подключение служебной части эпилога
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");
?>
