<?php

namespace UsersRatingTest;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\SystemException;

class URTDisciplinesTable extends DataManager{

    public static function getTableName(){
        return 'i_disciplines';
    }

    public static function getUfId(){
        return 'DISCIPLINES';
    }

    public static function getMap(){
        try {
            return [
                // ID
                new IntegerField('ID', [
                    'primary' => true,
                    'autocomplete' => true,
                ]),
                // NAME
                new StringField('NAME', [
                    'required' => true,
                ]),
            ];
        } catch (SystemException $e) {
        }
    }
}