<?php

require_once("settings.php");

function getOptions() {
 
    // Бренды
    $groups = (isset($_GET['groups'])) ? implode(',', $_GET['groups']) : null;
 
    // Сортировка
    $sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'r.grade_asc';
    $sort = explode('_', $sort);
    $sortBy = $sort[0];
    $sortDir = $sort[1];
 
    return array(
        'groups' => $groups,
        'sort_by' => $sortBy,
        'sort_dir' => $sortDir
    );
}

function getData($options) {
    global $dbconnect;
    $sortBy = $options['sort_by'];
    $sortDir = $options['sort_dir'];
 
    // Необязательные параметры

    $groups = $options['groups'];
    $groupsWhere =
        ($groups !== null)
            ? "WHERE s.group in ($groups)"
            : '';
 
    $query = "
        SELECT s.full_name, s.group, c.semester, r.grade FROM students s
        JOIN rating r ON s.student_id = r.student_id 
        JOIN control c ON r.control_id = c.id
            $groupsWhere
        ORDER BY $sortBy $sortDir;
    ";
 
    $data = pg_query($dbconnect, $query);
    return pg_fetch_all($data);
}

try {
    
    // Получаем данные от клиента
    $options = getOptions();
    
    // Получаем товары
    $students = getData($options);
}

catch (Exception $e) {
    // Возвращаем клиенту ответ с ошибкой
    echo json_encode(array(
        'code' => 'error',
        'message' => $e->getMessage()
    ));
}
?>