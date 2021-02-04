<?php


\Bitrix\Main\Loader::registerAutoLoadClasses(
    'users_rating_test',
    [
        'UsersRatingTest\\UserTable' => 'lib/User.php',
        'UsersRatingTest\\URTDisciplinesTable' => 'lib/URTDisciplines.php',
        'UsersRatingTest\\URTResultsTable' => 'lib/URTResults.php',
    ]
);

