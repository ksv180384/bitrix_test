<?php

IncludeModuleLangFile(__FILE__);

use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;

//Loc::loadMessages(__FILE__);

class users_rating_test extends CModule{

    public $MODULE_ID = 'users_rating_test';

    public function __construct(){
        $arrModuleVersion = [];

        require_once 'version.php';

        if(is_array($arrModuleVersion) && array_key_exists('VERSION', $arrModuleVersion)){
            $this->MODULE_VERSION = $arrModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arrModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_NAME = Loc::getMessage('test.user_rating_test_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('test.user_rating_test_MODULE_DESCRIPTION');

        $this->PARTNER_NAME = Loc::getMessage('test.user_rating_test_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('test.user_rating_test_PARTNER_URI');
    }

    public function DoInstall(){
        global $step;

        $step = intval($step);
        if($step < 2){
            $GLOBALS['APPLICATION']->includeAdminFile(
                Loc::getMessage('test.user_rating_test_INSTALL_TITLE'),
                __DIR__.'/step.php'
            );
        }else if($step === 2){
            $addData = $_REQUEST['add_data'] ?: false;
            $this->InstallDB(['add_data' => $addData]);
            $this->InstallEvents();
            $this->InstallFiles();
            //RegisterModule($this->MODULE_ID);

            $GLOBALS['APPLICATION']->includeAdminFile(
                Loc::getMessage('test.user_rating_test_INSTALL_TITLE'),
                __DIR__.'/step2.php'
            );
        }
        return true;
    }

    public function DoUninstall(){

        $this->UnInstallDB();
        $this->UnInstallEvents();
        $this->UnInstallFiles();
        UnRegisterModule($this->MODULE_ID);

        $GLOBALS['APPLICATION']->includeAdminFile(
            Loc::getMessage('test.user_rating_test_UNINSTALL_TITLE'),
            __DIR__.'/unstep.php'
        );
        return true;
    }

    public function InstallDB($arrParams = []){
        global $DB;
        $this->errors = false;
        $this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/local/modules/users_rating_test/install/db/install.sql');

        RegisterModule($this->MODULE_ID);
        if(!empty($arrParams['add_data'])){
            $this->generateData();
        }

        if (!$this->errors) {
            return true;
        }
        return $this->errors;
    }

    public function UnInstallDB(){
        global $DB;
        $this->errors = false;
        $this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/local/modules/users_rating_test/install/db/uninstall.sql');
        if (!$this->errors) {
            return true;
        }

        return $this->errors;
    }

    function InstallEvents(){
        return true;
    }

    function UnInstallEvents(){
        return true;
    }

    public function InstallFiles(){
        $pathFiles = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/users_rating_test/install/components/';

        CopyDirFiles($pathFiles, $_SERVER['DOCUMENT_ROOT'] . '/local/components/test',true, true);
    }

    public function UnInstallFiles(){
        $pathFiles = $_SERVER['DOCUMENT_ROOT'] . '/local/components/test/user_rating';
        Directory::deleteDirectory($pathFiles);

        $pathFiles = $_SERVER['DOCUMENT_ROOT'] . '/local/components/test/user_rating_table';
        Directory::deleteDirectory($pathFiles);
    }

    private function generateData(){
        global $DB;

        CModule::IncludeModule('users_rating_test');

        $DB->Query('INSERT INTO `i_disciplines` (`NAME`) VALUES ("Метематика"), ("Физика"), ("История")');

        $disciplines = \UsersRatingTest\URTDisciplinesTable::getList([
            'select' => [
                'ID',
                'NAME',
            ],
        ])->fetchAll();

        $users = \UsersRatingTest\UserTable::getList([
            'select' => [
                'ID',
                'NAME',
            ],
        ])->fetchAll();

        $sqlInsert = 'INSERT INTO `i_results` (`USER_ID`, `DISCIPLINE_ID`, `SCORE`) VALUES ';
        foreach ($disciplines as $disciplineItem){
            foreach ($users as $userItem){
                $sqlInsert .= '(' . $userItem['ID'] . ', ' . $disciplineItem['ID'] . ', ' . rand(1, 100) . '),';
            }
        }
        $sqlInsert = trim($sqlInsert, ',');
        $DB->Query($sqlInsert);
        //exit();
    }
}