<?php

/**
 * @var \Bitrix\Main\Application $APPLICATION
 */

use Bitrix\Main\Localization\Loc;

if(!check_bitrix_sessid()) return;

IncludeModuleLangFile(__FILE__);
?>

<form action="<?=$APPLICATION->GetCurPage()?>">
    <?=bitrix_sessid_post()?>
    <input type="hidden" name="lang" value="<?=LANG ?>">
    <input type="hidden" name="id" value="users_rating_test">
    <input type="hidden" name="install" value="Y">
    <input type="hidden" name="step" value="2">
    <p>
        <label for="add_data">
            <input type="checkbox" name="add_data" id="add_data" value="Y">
            Сгенерировать тестовые данные?
        </label>
    </p>
    <input type="submit" name="" value="Продолжить">
</form>