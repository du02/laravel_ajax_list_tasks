<!DOCTYPE html>
<html lang="pt-br">

<head>
    <!-- Meta tags Obrigatórias -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- Jquery-confirm -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    <link rel="stylesheet" href="{{ asset('css/global.css') }}">

    <!-- Font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
          integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>To-do Ajax</title>
</head>

<body>
<div>
    <div class="header container-fluid shadow">
        <div class="d-flex justify-content-center align-items-center">
            <img src="{{ asset('img/T-removebg-preview.png') }}" alt="logo" class="img-logo">
        </div>
    </div>
    <div class="d-flex justify-content-center">
        <div class="g-button col-md-4 row justify-content-end">
            <div class="">
                <button type="button" class="button-adding btn-lg" id="modalAddingButton">
                    <i class="fa-solid fa-pencil"></i>
                </button>
            </div>
        </div>
    </div>

    <main class="main container-fluid" id="main">

    </main>
</div>


<!-- Modal -->
<div class="modal fade" id="modalAdding" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fa-solid fa-folder-plus"> Nova Tarefa</i>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="formTask">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <div class="form-group col-md-12">
                        <input type="text" class="form-control @error('task') is-invalid @enderror" name="task" id="task" placeholder="Tarefa" required>
                    </div>
                    @error('task')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="form-group col-md-12">
                        <select class="custom-select @error('difficulty') is-invalid @enderror" name="difficulty" id="difficulty" required>
                            <option selected>Escolha a dificuldade</option>
                            <option value="easy">Fácil</option>
                            <option value="medium">Médio</option>
                            <option value="hard">Dificil</option>
                        </select>
                    </div>
                    @error('difficulty')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror

                    <div class="d-flex justify-content-end mt-5">
                        <button type="button" class="btn btn-secondary mr-1" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>


<script>
    // Setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });

    // Get Tasks
    $(document).ready(function () {
        const url = '{{ route("getTasks.ajax") }}';

        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json',
            success: function (response) {

                let listHtml = '';

                // forEach - Array Js
                function list(elem, index){

                    listHtml += '<div class="d-flex justify-content-center mt-1">';
                    listHtml += '<div class="card card-border-'+ elem.difficulty +' col-sm-12 col-md-4 col-lg-4 shadow-sm">';
                    listHtml += '<div class="d-flex align-items-center">';
                    listHtml += '<div class="card-body font-weight-bold text-uppercase">';
                    listHtml +=  elem.task;
                    listHtml += '</div>';
                    listHtml += '<div>';
                    listHtml += '<button id="deleteTask" class="btn btn-light" type="button" data-id="'+ elem.id +'">';
                    listHtml += '<i class="fa-solid fa-trash"></i>';
                    listHtml += '</button>';
                    listHtml += '</div>';
                    listHtml += '</div>';
                    listHtml += '</div>';
                    listHtml += '</div>';

                }
                response.forEach(list);

                // Adding list Tasks
                $('#main').html(listHtml);
            }
        });
    });

    // Adding Tasks
    $(document).on('submit', '#formTask', function (e) {
        e.preventDefault();

        const url = '{{ route("store.ajax") }}';
        let data = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (response) {

                if(response.success === true) {
                    // Adding success
                    $.alert({
                        title: 'Sucesso!',
                        content: response.message + '🎉🥳',
                        buttons: {
                            confirm: function () {
                                // Reload page, 0.5s later
                                setInterval(function (){
                                    location.reload();
                                }, 500);
                            },
                        },
                    });

                    // Close modal
                    $('#modalAdding').modal('hide');

                } else {
                    // Adding error
                    $.alert({
                        title: 'Erro!',
                        content: response.message + '😟',
                    });
                }
            }

        });
    });

    // Delete
    $(document).on('click', '#deleteTask', function () {
        let data = $(this).data('id');
        const url = `/remove/${data}`;

        $.post(url, data, function (response){

            // Adding success
            $.alert({
                title: 'Removido!',
                content: response.message + '🎉',
                buttons: {
                    confirm: function () {
                        // Reload page, 0.5s later
                        setInterval(function (){
                            location.reload();
                        }, 500);
                    },
                },
            });

        }, 'json');
    });

    // Modal Show
    $(document).on('click', '#modalAddingButton', function () {
        $('#modalAdding').modal('show');
    });
</script>
</body>

</html>
