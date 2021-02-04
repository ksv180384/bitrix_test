<?php

/**
 * @var \Bitrix\Main\Application $APPLICATION
 */

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if(!check_bitrix_sessid()) return;

if ($errorException = $APPLICATION->getException()) {
    // ошибка при удалении модуля
    CAdminMessage::showMessage(
        Loc::getMessage('test.user_rating_test_UNINSTALL_FAILED').': '.$errorException->GetString()
    );
} else {
    // модуль успешно удален
    CAdminMessage:showNote(
        Loc::getMessage('test.user_rating_test_UNINSTALL_SUCCESS')
    );
}
?>

<form action="<?=$APPLICATION->GetCurPage()?>">
    <input type="hidden" name="lang" value="<?=LANG ?>">
    <input type="submit" name="" value="Вернуться в список">
</form>
