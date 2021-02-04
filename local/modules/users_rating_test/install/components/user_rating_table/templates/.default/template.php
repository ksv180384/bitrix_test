<table class="table">
    <thead>
        <tr>
            <th>Имя</th>
            <th>Предмет</th>
            <th>Балл</th>
            <th>Рейтинг результата</th>
            <th>Рейтинг пользователя</th>
        </tr>
    </thead>
    <tbody>
        <? foreach($arResult['RESULTS']['ITEMS'] as $result): ?>
            <tr>
                <td><?=$result['USER_NAME']; ?></td>
                <td><?=$result['DISCIPLINE_NAME']; ?></td>
                <td><?=$result['SCORE']; ?></td>
                <td><?=$result['RATING_DISCIPLINE']; ?> из <?=$arResult['COUNT_DISCIPLINE'][$result['DISCIPLINE_ID']]['COUNT']; ?></td>
                <td> - </td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>
<? if(!empty($arResult['RESULTS']['navParams'])): ?>
    <nav>
        <ul class="pagination">
            <? for ($i = 1; $i <= $arResult['RESULTS']['navParams']['count']; $i++): ?>
                <li class="page-item">
                    <a href="<?=$arResult['RESULTS']['navParams']['url']?>page=<?=$i?>" class="page-link"><?=$i?></a>
                </li>
            <? endfor; ?>
        </ul>
    </nav>
<? endif; ?>

