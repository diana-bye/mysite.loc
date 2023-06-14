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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
    <?php
        require_once("settings.php");
    ?>

    <script>

    

    var dataDB = (function($) {

        var ui = {
            $form: $('#filters-form'),
            $groups: $('#groups'),
            $groupInput: $('#groups input'),
            $sort: $('#sort'),
            $students: $('#students'),
            $template: $('#students-template')
        };
        
        var template = _.template(ui.$template.html());

        // Инициализация модуля
        function init() {
            _bindHandlers();
            _getData({needsData: 'groups'});
        }

        function _bindHandlers() {
            ui.$groupInput.on('change', _getData);
            ui.$sort.on('change', _getData);
        }

        function _dataSuccess(responce) {
            console.log(responce);
            ui.$students.html(template({students: responce.students}));
        }

        function _getData() {
            var data = ui.$form.serialize();
            if (options && options.needsData) {
                data += '&needs_data=' + options.needsData;
            }            
            $.ajax({
                url: 'search.php',
                data: data,
                type: 'GET',
                cache: false,
                dataType: 'json',
                error: dataError,
                success: function(responce) {
                    if (responce.code === 'success') {
                        dataSuccess(responce);
                    } else {
                        dataError(responce);
                    }
                }
            });
        }
        // Экспортируем наружу
        return {
            init: init
        }
    });

    </script>

    <form id="filters-form" role="form">
        <div class="col-md-4">
            <h4>Группы</h4>
            <div id="groups">
                <div class="checkbox"><label><input type="checkbox" name="groups[]" value="1"> КММО-02-21</label></div>
                <div class="checkbox"><label><input type="checkbox" name="groups[]" value="2"> КМБО-02-22</label></div>
                <div class="checkbox"><label><input type="checkbox" name="groups[]" value="3"> КМБО-02-21</label></div>
                <div class="checkbox"><label><input type="checkbox" name="groups[]" value="4"> КМБО-05-21</label></div>
            </div>
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
            <?php foreach ($students as $i => $student){ ?>
                <tr id="tr-<?=$i?>">
                    <td><?php echo $student['grade']; ?></td>
                    <td><?php echo $student['full_name'] ?></td>
                    <td><?php echo $student['semester'] ?></td>
                    <td><?php echo $student['group'] ?></td>
                </tr>
                <?php } ?>                
        </tbody>
    </table>
</body>
</html>