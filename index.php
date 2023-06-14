<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>

<body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
    <?php
        require_once("settings.php");
    ?>

    <form id="filters-form" role="form">
        <div class="col-md-4">
            <h4>Группы</h4>
            <script id="groups-template" type="text/template">
                <% _.each(groups, function(item) { %>
                <div class="checkbox"><label><input type="checkbox" name="groups[]" value="<%= item.group %>"> <%= item.group %></label></div>
                <% }); %>
            </script>
        </div>
        <div class="col-md-4">
            <label for="sort">Сортировка</label> 
            <br>
            <select id="sort" name="sort" class="form-control">
                <option value="s.full_name_asc">По имени, А-Я</option>
                <option value="s.full_name_desc">По имени, Я-А</option>
                <option value="r.grade_asc">По среднему баллу, по возрастанию</option>
                <option value="r.grade_desc">По среднему баллу, по убыванию</option>
                <option value="c.semester_desc">По семестру, по возрастанию</option>
                <option value="c.semester_desc">По семестру, по убыванию</option>
            </select>
        </div>
    </form>

    <table class="table table-borderless">
        <thead>
            <tr>
                <th>Средний балл</th>
                <th>ФИО</th>
                <th>Семестр</th>
                <th>Группа</th>
            </tr>
        </thead>

        <?php
            require_once("search.php");
         ?>

        <tbody>
        <script type="text/javasc">
        $('#students-template')
            <% _.each(students, function(student) { %>
                <tr id="tr-<?=$i?>">
                    <td><%= student.grade %></td>
                    <td><%- student.full_name %></td>
                    <td><%= student.semester %></td>
                    <td><%- student.group %></td>
                </tr>
            <% }); %>
        </script>                
        </tbody>

        <script>
        var dataDB = (function($) {

            var ui = {
                $form: $('#filters-form'),
                $groups: $('#groups'),
                $groupInput: $('#groups input'),
                $sort: $('#sort'),
                $students: $('#students'),
                $studentsTemplate: $('#students-template'),
                $groupsTemplate: $('#groups-template')
            };
            var studentsTemplate = _.template(ui.$studentsTemplate.html());
            var groupsTemplate = _.template(ui.$groupsTemplate.html());

            // Инициализация модуля
            function init() {
                _bindHandlers();
            }

            function _bindHandlers() {
                ui.$groupInput.on('change', _getData);
                ui.$sort.on('change', _getData);
            }

            // Сброс фильтра
            function _resetFilters() {
                ui.$groups.find('input').removeAttr('checked');
                ui.$minPrice.val(0);
            }

            // Ошибка получения данных
            function _dataError(responce) {
                console.error('responce', responce);
            }

            // Успешное получение данных
            function _dataSuccess(responce) {
                ui.$students.html(studentsTemplate({students: responce.data.students}));
                if (responce.data.groups) {
                    ui.$groups.html(groupsTemplate({groups: responce.data.groups}));
                }
            }

            function _getData() {
                var myData = ui.$form.serialize();
                $.ajax({
                    url: 'search.php',
                    data: myData,
                    type: 'GET',
                    cache: false,
                    dataType: 'json',
                    error: _dataError,
                    success: function(responce) {
                        if (responce.code === 'success') {
                            _dataSuccess(responce);
                        } else {
                            _dataError(responce);
                        }
                    }
                });
            }
            // Экспортируем наружу
            return {
                init: init
            }
        })(jQuery);
    </script>

    </table>
</body>
</html>