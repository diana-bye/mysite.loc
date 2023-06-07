<?
    // function convertGradeToInt($grade) {
    //     switch ($grade) {
    //     case 'неуд':
    //         return 2;
    //     case 'уд':
    //         return 3;
    //     case 'хор':
    //         return 4;
    //     case 'отл':
    //         return 5;
    //     }
    // };

    $search = @$_GET["search"];
    $sort = @$_GET["sort"];

    if (array_key_exists($sort, $sort_list)) {
        $sort_sql = $sort_list[$sort];
    }
    else {
        $sort_sql = reset($sort_list);
    }

    $query = "SELECT s.full_name, s.group, c.semester, r.grade FROM students s
    JOIN rating r ON s.student_id = r.student_id 
    JOIN control c ON r.control_id = c.id
    WHERE
    s.full_name ILIKE '%$search%' OR 
    s.group ILIKE '%$search%' OR 
    c.semester = '$search' 
    ORDER BY $sort_sql;";

    $result = pg_query($dbconnect, $query) or die('Database error' . pg_last_error());

    $rows = array();
    while ($row = pg_fetch_assoc($result)) {
        $rows[] = $row;
    }

    print json_encode($rows);
?>