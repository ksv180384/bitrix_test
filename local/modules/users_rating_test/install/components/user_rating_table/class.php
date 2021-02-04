<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class TUserRatingTableComponent extends CBitrixComponent{

    public function executeComponent(){

        if(!CModule::IncludeModule('users_rating_test')){
            echo 'Неверно задан модуль "user_rating_test"';
        }

        $filter = !empty($this->arParams['filter']) ? $this->arParams['filter'] : [];
        $nPageSize = !empty($this->arParams['nPageSize']) ? $this->arParams['nPageSize'] : 0;

        $this->arResult['RESULTS'] = \UsersRatingTest\URTResultsTable::getResultsList($filter, $nPageSize);
        $this->arResult['COUNT_DISCIPLINE'] = \UsersRatingTest\URTResultsTable::countDiscipline();

        //echo '<pre>', $this->arResult['results']->getTrackerQuery()->getSql(), '</pre>';

        $this->includeComponentTemplate();
    }
}