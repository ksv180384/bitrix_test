<?php

namespace UsersRatingTest;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\SystemException;
use Bitrix\Rest\Api\User;

class URTResultsTable extends DataManager{

    public static function getTableName(){
        return 'i_results';
    }

    public static function getUfId(){
        return 'RESULTS';
    }

    public static function getMap(){
        try {
            return [
                // ID
                new IntegerField('ID', [
                    'primary' => true,
                    'autocomplete' => true,
                ]),
                new IntegerField('USER_ID', [
                    'primary' => true,
                    'autocomplete' => true,
                ]),
                new ReferenceField(
                    'USER',
                    \UsersRatingTest\User::class,
                    ['this.USER_ID' => 'ref.ID']
                ),
                new IntegerField('DISCIPLINE_ID', [
                    'primary' => true,
                    'autocomplete' => true,
                ]),
                new ReferenceField(
                    'DISCIPLINE',
                    \UsersRatingTest\URTDisciplines::class,
                    ['this.DISCIPLINE_ID' => 'ref.ID']
                ),
                new StringField('SCORE', [
                    'primary' => true,
                    'autocomplete' => true,
                ]),
            ];

        } catch (SystemException $e) {

        }
    }

    /**
     * @param array $filter
     * @param array $nPageSize
     * @return array
     * @throws ArgumentException
     * @throws SystemException
     * @throws \Bitrix\Main\ObjectPropertyException
     */
    public static function getResultsList($filter = [], $nPageSize = 0){
        $result = [];

        // Есл задан размер страницы, то получаем данные для пагинаци
        if(!empty($nPageSize)){
            // Считаем записи для пагинации
            $navParams['numResults'] = self::getList([
                'select' => [new \Bitrix\Main\Entity\ExpressionField('CNT', 'COUNT(*)')],
                'filter' => $filter
            ])->fetch()['CNT'];
            $navParams['count'] = ceil($navParams['numResults'] / $nPageSize);

            $navParams['nPageSize'] = $nPageSize;
            $navParams['iNumPage'] = !empty($_GET['page']) ? $_GET['page'] : 1;
            $navParams['url'] = self::getUrl('page');

            $result['navParams'] = $navParams;
        }

        $result['ITEMS'] = self::getList([
            'select' => [
                'ID',
                'USER_ID',
                'U_ID' => 'USER_ID',
                'DISCIPLINE_ID',
                'D_ID' => 'DISCIPLINE_ID',
                'SCORE',
                'S' => 'SCORE',
                'USER_NAME' => 'USER.NAME',
                'USER_LAST_NAME' => 'USER.LAST_NAME',
                'DISCIPLINE_NAME' => 'DISCIPLINE.NAME',
                'RATING_DISCIPLINE',
                //'rr2'
            ],
            'filter' => $filter,
            'order'  => ['DISCIPLINE_ID' => 'ASC', 'RATING_DISCIPLINE' => 'ASC'],
            'runtime' => [
                'RATING_DISCIPLINE' => [
                    'data_type' => 'integer',
                    'expression' => ['(SELECT COUNT(*) FROM `i_results` AS `r` WHERE r.`SCORE` > S AND r.`DISCIPLINE_ID` = `D_ID`) + 1']
                ],
            ],
            'offset' => $navParams['nPageSize'] * ($navParams['iNumPage'] - 1),
            'limit' => $navParams['nPageSize']
        ])->fetchAll();

        return $result;
    }

    public function countDiscipline(){
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

        $result = array_combine(
            array_column($countDiscipline, 'DISCIPLINE_ID'), $countDiscipline
        );

        return $result;
    }

    private static function getUrl($key) {
        $url = '';
        $strParams = explode('?', $_SERVER['REQUEST_URI']);
        if($strParams[1]){
            $arrParams = explode('&', $strParams[1]);
            foreach ($arrParams as $item){
                $param = explode('=', $item);
                if($param[0] == $key){
                    continue;
                }
                $url .= (empty($url) ? '?' : '&') . $item;

            }
        }
        $resultUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'] .($url ? $url . '&' : '?');

        return $resultUrl;
    }
}