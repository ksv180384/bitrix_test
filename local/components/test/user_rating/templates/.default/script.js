
document.addEventListener('DOMContentLoaded', function() {
    const filterRatingBtn = document.getElementById('filterRatingBtn');
    const tableUserRating = document.getElementById('tableUserRating');
    const disciplineSelect = document.getElementById('disciplineSelect');
    const searchUserInput = document.getElementById('searchUserInput');
    const usersSelect = document.getElementById('usersSelect');
    const generateExcelBtn = document.getElementById('generateExcelBtn');
    const tableUserRatingSearch = document.getElementById('tableUserRatingSearch');

    const path = '/local/components/test/user_rating/ajax/index.php';

    // Подгружем таблицу со списком пользователей и рейтингов
    filterRatingBtn.addEventListener('click', (e) => {
        const discipline_id = disciplineSelect.value;
        const user_id = usersSelect.value;
        if(user_id < 1 && discipline_id < 1){
            clearSearch();
            return true;
        }
        getData();
    });

    // Поиск по имени пользователя
    searchUserInput.addEventListener('keyup', (e) => {
        const user_name = searchUserInput.value;
        if(user_name.length < 2){
            clearSearch();
            return true;
        }
        getData();
    });

    // Генереруем Excel
    generateExcelBtn.addEventListener('click', (e) => {
        generateExcel();
    });

    function getData() {
        const discipline_id = disciplineSelect.value ? '&DISCIPLINE=' + disciplineSelect.value : '';
        const user_name = searchUserInput.value ? '&USER_NAME=' + searchUserInput.value : '';
        const user_id = usersSelect.value ? '&USER_ID=' + usersSelect.value : '';

        const url = path + '?action=USERS_RATING' + discipline_id + user_name + user_id;
        axios.get(url).then(res => {
            const result = res.data;
            tableUserRatingSearch.innerHTML = result.html;
            tableUserRatingSearch.style.display = 'block';
            tableUserRating.style.display = 'none';

        });
    }

    function generateExcel() {
        const discipline = disciplineSelect.value ? '&DISCIPLINE=' + disciplineSelect.value : '';
        const user_name = searchUserInput.value ? '&USER_NAME=' + searchUserInput.value : '';
        const user_id = usersSelect.value ? '&USER_ID=' + usersSelect.value : '';

        const url = path + '?action=GENERATE_EXCEL' + discipline + user_name + user_id;
        axios.get(url, {responseType: 'blob'}).then(({ data }) => {
            const downloadUrl = window.URL.createObjectURL(new Blob([data]));

            const link = document.createElement('a');

            link.href = downloadUrl;

            link.setAttribute('download', 'file.xls'); //any other extension
            document.body.appendChild(link);
            link.click();
            link.remove();
        });
    }

    function clearSearch() {
        tableUserRatingSearch.style.display = 'none';
        tableUserRating.style.display = 'block';
        tableUserRatingSearch.innerHTML = '';
    }
}, false);

