<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

Bitrix\Main\UI\Extension::load('ui.bootstrap4');

class TUserRatingComponent extends CBitrixComponent{

    public function executeComponent(){

        if(!CModule::IncludeModule('users_rating_test')){
            echo 'Неверно задан модуль "user_rating_test"';
            exit();
        }


        $this->arResult['disciplines'] = \UsersRatingTest\URTDisciplinesTable::getList([
            'select' => [
                'ID',
                'NAME',
            ],
        ])->fetchAll();

        $this->arResult['users'] = \UsersRatingTest\UserTable::getList([
            'select' => [
                'ID',
                'NAME',

            ],
        ])->fetchAll();

        $this->includeComponentTemplate();
    }
}