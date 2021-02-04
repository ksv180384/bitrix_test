<?php
require_once($_SERVER['DOCUMENT_ROOT']. '/bitrix/modules/main/include/prolog_before.php');

if(!CModule::IncludeModule('users_rating_test')){
    echo json_encode([
        'success' => 'Y',
        'message' => 'Неверно задан модуль "user_rating_test"',
    ]);
    exit();
}

switch ($_GET['action']){
    case 'USERS_RATING':
        usersRating();
        break;
}

function usersRating(){
    global $APPLICATION;

    $arResult['results'] = \UsersRatingTest\URTResultsTable::getList([
        'select' => [
            'ID',
            'USER_ID',
            'U_ID' => 'USER_ID',
            'DISCIPLINE_ID',
            'D_ID' => 'DISCIPLINE_ID',
            'SCORE',
            'S' => 'SCORE',
            'USER_NAME' => 'USER.NAME',
            'DISCIPLINE_NAME' => 'DISCIPLINE.NAME',
            'RATING_DISCIPLINE',
            //'rr2'
        ],
        'order'  => ['DISCIPLINE_ID' => 'ASC', 'RATING_DISCIPLINE' => 'ASC'],
        'runtime' => [
            'RATING_DISCIPLINE' => [
                'data_type' => 'integer',
                'expression' => ['(SELECT COUNT(*) FROM `i_results` AS `r` WHERE r.`SCORE` > S AND r.`DISCIPLINE_ID` = `D_ID`) + 1']
            ],
            /*'rr2' => [
                'data_type' => 'integer',
                'expression' => ['(SELECT COUNT(*) FROM `i_results` AS `r` WHERE r.`SCORE` > S AND r.`USER_ID` = `U_ID`) + 1']
            ]*/
        ],
    ])->fetchAll();

    $countDiscipline = \UsersRatingTest\URTResultsTable::getList([
        'select' => [
            'DISCIPLINE_ID',
            'COUNT',
        ],
        'runtime' => [
            'COUNT' => [
                'data_type' => 'integer',
                'expression' => ['COUNT(*)']
            ],
        ],
    ])->fetchAll();

    $arResult['COUNT_DISCIPLINE'] = array_combine(
        array_column($countDiscipline, 'DISCIPLINE_ID'), $countDiscipline
    );

    ob_start();
    $APPLICATION->IncludeComponent('test:user_rating', '.default', $arResult, false);
    $html = ob_get_contents();
    ob_end_clean();

    echo json_encode([
        'success' => 'Y',
        'json' => $arResult,
        'html' => $html,
    ]);
}