<?php
require_once($_SERVER['DOCUMENT_ROOT']. '/bitrix/modules/main/include/prolog_before.php');

if(!CModule::IncludeModule('users_rating_test')){
    echo json_encode([
        'success' => 'N',
        'message' => 'Неверно задан модуль "user_rating_test"',
    ]);
    exit();
}

switch ($_GET['action']){
    case 'USERS_RATING':
        usersRating();
        break;
    case 'GENERATE_EXCEL':
        generateExcel();
        break;
}

function usersRating(){
    global $APPLICATION;

    if(!CModule::IncludeModule('users_rating_test')){
        echo json_encode([
            'success' => 'N',
            'message' => 'Неверно задан модуль "user_rating_test"',
        ]);
        exit();
    }

    $filter = [];
    if(!empty($_GET['DISCIPLINE'])){
        $filter['DISCIPLINE_ID'] = $_GET['DISCIPLINE'];
    }
    if(!empty($_GET['USER_NAME'])){
        $filter['%=USER_NAME'] = $_GET['USER_NAME'] . '%';
    }
    if(!empty($_GET['USER_ID'])){
        $filter['%=USER_ID'] = $_GET['USER_ID'] . '%';
    }

    $arParams = [
        'filter' => $filter,
    ];
    ob_start();
    $APPLICATION->IncludeComponent('test:user_rating_table', '.default', $arParams, false);
    $html = ob_get_contents();
    ob_end_clean();

    echo json_encode([
        'success' => 'Y',
        'html' => $html,
    ]);
}

function generateExcel(){
    if(!CModule::IncludeModule('nkhost.phpexcel')){
        echo json_encode([
            'success' => 'N',
            'message' => 'Не найден модуль "nkhost.phpexcel"',
        ]);
        exit();
    }

    $filter = [];
    if(!empty($_GET['DISCIPLINE'])){
        $filter['DISCIPLINE_ID'] = $_GET['DISCIPLINE'];
    }
    if(!empty($_GET['USER_NAME'])){
        $filter['%=USER_NAME'] = $_GET['USER_NAME'] . '%';
    }
    if(!empty($_GET['USER_ID'])){
        $filter['%=USER_ID'] = $_GET['USER_ID'] . '%';
    }

    $arResult['RESULTS'] = \UsersRatingTest\URTResultsTable::getResultsList($filter);
    $arResult['COUNT_DISCIPLINE'] = \UsersRatingTest\URTResultsTable::countDiscipline();

    global $PHPEXCELPATH;
    require_once($PHPEXCELPATH.'/PHPExcel.php');
    // Подключаем класс для вывода данных в формате excel
    require_once($PHPEXCELPATH.'/PHPExcel/Writer/Excel5.php');
    $PHPExcel = new PHPExcel();

    // Устанавливаем индекс активного листа
    $PHPExcel->setActiveSheetIndex(0);
    // Получаем активный лист
    $sheet = $PHPExcel->getActiveSheet();
    // Подписываем лист
    $sheet->setTitle('Таблица пользователей');

    // Вставляем текст в ячейку
    $sheet->setCellValue('A1', 'Имя');
    $sheet->setCellValue('B1', 'Предмет');
    $sheet->setCellValue('C1', 'Балл');
    $sheet->setCellValue('D1', 'Рейтинг результата');
    $sheet->setCellValue('E1', 'Рейтинг пользователя');
    $i = 2;
    foreach ($arResult['RESULTS'] as $resultItem){
        $sheet->setCellValue('A' . $i, $resultItem['USER_NAME']);
        $sheet->setCellValue('B' . $i, $resultItem['DISCIPLINE_NAME']);
        $sheet->setCellValue('C' . $i, $resultItem['SCORE']);
        $sheet->setCellValue('D' . $i, $resultItem['RATING_DISCIPLINE'] . ' из ' . $arResult['COUNT_DISCIPLINE'][$resultItem['DISCIPLINE_ID']]['COUNT']);
        $sheet->setCellValue('E' . $i, ' - ');
        $i++;
    }

    // Выводим HTTP-заголовки
    header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
    header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
    header ( "Cache-Control: no-cache, must-revalidate" );
    header ( "Pragma: no-cache" );
    header ( "Content-type: application/vnd.ms-excel" );
    header ( "Content-Disposition: attachment; filename=matrix.xls" );

    // Выводим содержимое файла
    $objWriter = new PHPExcel_Writer_Excel5($PHPExcel);
    $objWriter->save('php://output');
    exit();

}