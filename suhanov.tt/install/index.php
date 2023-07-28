<?php
//подключаем основные классы для работы с модулем
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
//в данном модуле создадим адресную книгу, и здесь мы подключаем класс, который создаст нам эту таблицу
use Module\Adress\AdressTable;
Loc::loadMessages(__FILE__);
//в названии класса пишем название директории нашего модуля, только вместо точки ставим нижнее подчеркивание
class suhanov_tt extends CModule
{
    public function __construct()
    {
        $arModuleVersion = array();
				//подключаем версию модуля (файл будет следующим в списке)
        include __DIR__ . '/version.php';
				//присваиваем свойствам класса переменные из нашего файла
        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
				//пишем название нашего модуля как и директории
        $this->MODULE_ID = 'suhanov.tt';
        // название модуля
        $this->MODULE_NAME = Loc::getMessage('MYMODULE_MODULE_NAME');
        //описание модуля
        $this->MODULE_DESCRIPTION = Loc::getMessage('MYMODULE_MODULE_DESCRIPTION');
        //используем ли индивидуальную схему распределения прав доступа, мы ставим N, так как не используем ее
        $this->MODULE_GROUP_RIGHTS = 'N';
        //название компании партнера предоставляющей модуль
        $this->PARTNER_NAME = Loc::getMessage('MYMODULE_MODULE_PARTNER_NAME');
        $this->PARTNER_URI = 'http://tt.gccit2.ru/';//адрес вашего сайта
    }
    //здесь мы описываем все, что делаем до инсталляции модуля, мы добавляем наш модуль в регистр и вызываем метод создания таблицы
    public function doInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->installDB();
    }
		//вызываем метод удаления таблицы и удаляем модуль из регистра
    public function doUninstall()
    {
        $this->uninstallDB();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }
		//вызываем метод создания таблицы из выше подключенного класса
    public function installDB()
    {
        if (Loader::includeModule($this->MODULE_ID)) {
            AdressTable::getEntity()->createDbTable();
            
        }
    }
		//вызываем метод удаления таблицы, если она существует
    public function uninstallDB()
    {
        if (Loader::includeModule($this->MODULE_ID)) {
            if (Application::getConnection()->isTableExists(Base::getInstance('\Suhanov\ThanksApp\DepartmentTable')->getDBTableName())) {
                $connection = Application::getInstance()->getConnection();
                $connection->dropTable(AdressTable::getTableName());
            }
        }
    }
}