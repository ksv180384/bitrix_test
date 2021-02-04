<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Рейтинг пользователей");

$APPLICATION->IncludeComponent(
    'test:user_rating',
    '',
    []);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>