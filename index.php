<!DOCTYPE html>
<html>
<head>
  <title>Поиск студентов</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    .container {
      margin-top: 20px;
    }
  </style>
</head>

<body>
    <?php

        include_once("./settings.php");
        include_once("./search.php");

        function sort_link_th($title, $a, $b) {
            $sort = @$_GET['sort'];

            if ($sort == $a) {
                return '<a class="active" id="sort-select" href="?sort=' . $b . '">' . $title . ' <i>▲</i></a>';
            } else if ($sort == $b) {
                return '<a class="active"  id="sort-select" href="?sort=' . $a . '">' . $title . ' <i>▼</i></a>';
            } else {
                return '<a href="?sort=' . $a . '">' . $title . '</a>';  
            }
        }

    $sort_list = array(
        'full_name_asc' => 's.full_name',
        'full_name_desc' => 's.full_name DESC',
        'group_asc' => 's.group',
        'group_desc' => 's.group DESC',
        'semester_asc'  => 'c.semester',
        'semester_desc'  => 'c.semester DESC',
        'grade_asc' => 'r.grade',
        'grade_desc' => 'r.grade DESC'
    );
?>

    <div class="container">
        <h1>Рейтинг студентов</h1>
        <form id="search-form">
            <div class="form-group row">
                <label for="search-input" class="col-sm-2 col-form-label">Поиск</label>
                <div class="col-sm-8">
                <input type="text" class="form-control" id="search-input" placeholder="Введите имя, группу или номер семестра">
                </div>
                <div class="col-sm-2">
                <button type="submit" class="btn btn-primary mb-2">Искать</button>
                </div>
            </div>
        </form>
        
    </div>
    <table>
        <thead>
            <tr>
                <th><?php echo sort_link_th('Имя', 'full_name_asc', 'full_name_desc'); ?></th>
                <th><?php echo sort_link_th('Группа', 'group_asc', 'group_desc'); ?></th>
                <th><?php echo sort_link_th('Семестр', 'semester_asc', 'semester_desc'); ?></th>
                <th><?php echo sort_link_th('Оценка', 'grade_asc', 'grade_desc'); ?></th>
            </tr>
        </thead>
        <tbody>
            <div id="search-results"></div>
        </tbody>
    </table>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script>
    $(document).ready(function() {
      $("#search-form").submit(function(event) {
        event.preventDefault();
        var search = $("#search-input").val();
        var sort = $("#sort-select").val();
        $.ajax({
          url: "/search.php",
          data: {
            search: search,
            sort: sort
          },
          dataType: "json",
          success: function(data) {
            showResults(data);
          }
        });
      });

      $("#search-input").autocomplete({
        source: function(request, response) {
          $.ajax({
            url: "/search.php",
            data: { search: request.term },
            dataType: "json",
            success: response
          });
        },
        select: function(event, ui) {
          $("#search-input").val(ui.item.full_name);
          $("#search-form").submit();
        },
        minLength: 2
      });

      function showResults(data) {
        var table;
        $.each(data, function(index, row) {
          table += "<tr><td>" + row.full_name + "</td><td>" + row.group + "</td><td>" + row.semester + "</td><td>" + row.grade + "</td></tr>";
        });
        $("#search-results").html(table);
      }
    });
  </script>
</body>
</html>