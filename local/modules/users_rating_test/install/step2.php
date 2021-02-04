<?php

/**
 * @var \Bitrix\Main\Application $APPLICATION
 */

use Bitrix\Main\Localization\Loc;

if(!check_bitrix_sessid()) return;

IncludeModuleLangFile(__FILE__);

if ($errorException = $APPLICATION->getException()) {
    // ошибка при установке модуля
    CAdminMessage::showMessage(
        Loc::getMessage('test.user_rating_test_INSTALL_ERROR').': '.$errorException->GetString()
    );
} else {
    // модуль успешно установлен
    CAdminMessage::showNote(
        Loc::getMessage('test.user_rating_test_INSTALL_SUCCESS')
    );
}
?>

<form action="<?=$APPLICATION->GetCurPage()?>">
    <input type="hidden" name="lang" value="<?=LANG ?>">
    <input type="submit" name="" value="<?=GetMessage('MOD_BACK') ?>">
</form>
