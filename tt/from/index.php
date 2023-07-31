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


if (!isset($_POST['user_dept'])) {
    $_POST['user_dept'] = 0;
}
print_r($_POST);
$objDep = Tt\DepartmentTable::getList(array());
$arDep = $objDep->fetchAll();
$arResult = $dateFilter = [];


if (!empty($_POST['dStart']) || !empty($_POST['dEnd'])) {
    $timestampStart = strtotime($_POST['dStart']);
    $dStart = date("d.m.Y", $timestampStart);
    $timestampEnd = strtotime($_POST['dEnd']);
    $dEnd = date("d.m.Y", $timestampEnd);

    if (!empty($_POST['dStart']) && empty($_POST['dEnd'])) {
        $dateFilter = array(
            '>DATE' => \Bitrix\Main\Type\DateTime::createFromUserTime($dStart . ' 23:59:59')
        );
    } elseif (empty($_POST['dStart']) && !empty($_POST['dEnd'])) {
        $dateFilter = array(
            '<DATE' => \Bitrix\Main\Type\DateTime::createFromUserTime($dEnd . ' 23:59:59')
        );
    } elseif (!empty($_POST['dStart']) && !empty($_POST['dEnd'])) {
        $dateFilter = array(
            array('>DATE' => \Bitrix\Main\Type\DateTime::createFromUserTime($dStart . ' 23:59:59')),
            array('<DATE' => \Bitrix\Main\Type\DateTime::createFromUserTime($dEnd . ' 23:59:59'))
        );
    }
}

if ($_POST['user_dept'] == 0) {

    $nav = new \Bitrix\Main\UI\PageNavigation("PAGE");
    $nav->allowAllRecords(true)
        ->setPageSize(20)
        ->initFromUri();
    $objThanks = Tt\ThankTable::getList(array(
        'select' => array('USER_' => 'USER_FROM_ID_LIST', 'CNT_FROM'),
        'group' => array('USER_FROM_ID_LIST.ID'),
        'order' => array('CNT_FROM' => 'DESC'),
        'filter' => $dateFilter,
        'count_total' => true,
        'offset' => $nav->getOffset(),
        'limit' => $nav->getLimit(),
        'runtime' => array(
            new Entity\ExpressionField('CNT_FROM', 'count(*)'),
        )
    ));

    $arResult = $objThanks->fetchAll();
    $nav->setRecordCount($objThanks->getCount());
} else {
    if ($_POST['user_dept'] != 0) {
        $objDep2 = Tt\DepartmentTable::getList(array(
            'select' => array('*', 'USER_' => 'USERS'),
            'filter' => array('=NAME' => $_POST['user_dept']),
        ));
        $arDep2 = $objDep2->fetchAll();
    }

    foreach ($arDep2 as $key => $user) {
        $row = Tt\ThankTable::getRow(array(
            'select' => array('USER_' => 'USER_FROM_ID_LIST', 'CNT_FROM'),
            'filter' => array('USER_FROM_ID_LIST.ID' => $user['USER_ID'],$dateFilter),
            'group' => array('USER_FROM_ID_LIST.ID'),
            'runtime' => array(
                new Entity\ExpressionField('CNT_FROM', 'count(*)'),
            ),
        ));

        $arResult[$key] = $row;
    }
}

$objUsDep = Tt\UserDepartmentTable::getList(array());
$arUsDep = $objUsDep->fetchAll();

function cmp($a, $b)
{
    return $b['CNT_FROM'] <=> $a['CNT_FROM'];
}
usort($arResult, "cmp");



?>
<!-- <pre><? print_r($arResult) ?></pre> -->

<style>
    html {
        font-family: sans-serif;
    }

    table {
        border-collapse: collapse;
        border: 2px solid rgb(200, 200, 200);
        letter-spacing: 1px;
        font-size: 0.8rem;
    }

    td,
    th {
        border: 1px solid rgb(190, 190, 190);
        padding: 10px 20px;
    }

    th {
        background-color: rgb(235, 235, 235);
    }

    td {
        text-align: center;
    }

    tr:nth-child(even) td {
        background-color: rgb(250, 250, 250);
    }

    tr:nth-child(odd) td {
        background-color: rgb(245, 245, 245);
    }

    caption {
        padding: 10px;
    }
</style>

<form action="/tt/" method="POST">
    <input type="date" name="dStart" id="dStart">
    <input type="date" name="dEnd" id="dEnd">
    <select name="user_dept">
        <option value="0">Выберите отдел</option>
        <? foreach ($arDep as $key => $dep) { ?>
            <option value="<?= $dep['ID'] ?>"><?= $dep['NAME'] ?></option>
        <? } ?>
    </select>
    <table>
        <thead>
            <th>Пользователь</th>
            <th>Количество благодарностей</th>
        </thead>
        <tbody>
            <? foreach ($arResult as $key => $user) { ?>
                <tr>
                    <td><?= $user['USER_NAME'] ?></td>
                    <td><?= $user['CNT_FROM'] ?></td>
                </tr>
            <? } ?>
        </tbody>
    </table>
    <input type="submit" value="Применить!">
</form>
<?
$APPLICATION->IncludeComponent(
    "bitrix:main.pagenavigation",
    "modern",
    array(
        "NAV_OBJECT" => $nav,
        "SEF_MODE" => "N",
    ),
    false
);
?>


<?
// подключение служебной части эпилога
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");
?>