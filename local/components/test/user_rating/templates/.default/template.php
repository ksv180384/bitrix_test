<div class="row">
    <div class="col-10">
        <div class="row">
            <div class="col-4">
                <label>
                    <input id="searchUserInput"
                           class="form-control"
                           type="text"
                           list="users"
                           placeholder="Поиск по имени"
                    />
                </label>
            </div>

            <div class="col-3">
                <select id="usersSelect" name="users" class="form-control">
                    <option value="0">Выбрать пользователя</option>
                    <? foreach ($arResult['users'] as $discipline): ?>
                        <option value="<?=$discipline['ID'] ?>"><?=$discipline['NAME'] ?></option>
                    <? endforeach; ?>
                </select>
            </div>

            <div class="col-3">
                <select id="disciplineSelect" name="discipline" class="form-control">
                    <option value="0">Выбрать предмет</option>
                    <? foreach ($arResult['disciplines'] as $discipline): ?>
                        <option value="<?=$discipline['ID'] ?>"><?=$discipline['NAME'] ?></option>
                    <? endforeach; ?>
                </select>
            </div>
            <div class="col-2">
                <button id="filterRatingBtn" class="btn-sm btn-success" type="button">Посмотреть</button>
            </div>
        </div>
    </div>

    <div class="col-2">
        <button id="generateExcelBtn" class="btn-sm btn-info" type="button">Скачать в Excel</button>
    </div>
</div>

<div id="tableUserRating" class="mt-4">
    <? $APPLICATION->IncludeComponent('test:user_rating_table', '.default', ['nPageSize' => 10], false); ?>
</div>

<div id="tableUserRatingSearch" class="mt-4 hidden">

</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
