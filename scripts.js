$(document).ready(function() {
    $('#form-cadastro-localidade').on('submit', function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: '../server/LocalidadeControl.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status === 'success') {
                    Swal.fire(
                        'Sucesso!',
                        'Localidade cadastrada com sucesso!',
                        'success'
                    ).then(() => {
                        window.location.href = 'listaLocalidade.php';
                    });
                } else {
                    Swal.fire(
                        'Erro!',
                        data.message,
                        'error'
                    );
                }
            }
        });
    });



    $('#form-cadastro-franquia').on('submit', function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: '../server/FranquiaControl.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status === 'success') {
                    Swal.fire(
                        'Sucesso!',
                        'Franquia cadastrada com sucesso!',
                        'success'
                    ).then(() => {
                        window.location.href = 'listaFranquia.php';
                    });
                } else {
                    Swal.fire(
                        'Erro!',
                        data.message,
                        'error'
                    );
                }
            }
        });
    });


    $('#form-cadastro-franqueado').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '../server/FranqueadoControl.php', // ajuste o caminho conforme necessário
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire(
                        'Sucesso!',
                        'Franqueado cadastrado com sucesso.',
                        'success'
                    ).then(() => {
                        window.location.href = 'listaFranqueado.php';
                    });
                } else {
                    let errorMessages = '';
                    $.each(response.message, function(key, value) {
                        errorMessages += value + '<br>';
                    });
                    Swal.fire(
                        'Erro!',
                        errorMessages,
                        'error'
                    );
                }
            },
            error: function() {
                Swal.fire(
                    'Erro!',
                    'Erro ao cadastrar franqueado. Por favor, tente novamente.',
                    'error'
                );
            }
        });
    });


    $('#solicitarFranquiaForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'server/FranqueadoControl.php', // ajuste o caminho conforme necessário
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire(
                        'Sucesso!',
                        'Franqueado cadastrado com sucesso.',
                        'success'
                    ).then(() => {
                        window.location.href = 'index.php';
                    });
                } else {
                    let errorMessages = '';
                    $.each(response.message, function(key, value) {
                        errorMessages += value + '<br>';
                    });
                    Swal.fire(
                        'Erro!',
                        errorMessages,
                        'error'
                    );
                }
            },
            error: function() {
                Swal.fire(
                    'Erro!',
                    'Erro ao cadastrar franqueado. Por favor, tente novamente.',
                    'error'
                );
            }
        });
    });




    $(document).on('click', '.excluir-franqueado', function() {
        var idFranqueado = $(this).data('id');
        Swal.fire({
            title: 'Tem certeza?',
            text: "Você não poderá reverter isso!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, exclua!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../server/FranqueadoControl.php', { action: 'deletar', idFranqueado: idFranqueado }, function(response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        Swal.fire(
                            'Excluído!',
                            'O franqueado foi excluído.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Erro!',
                            'Ocorreu um erro ao excluir o franqueado.',
                            'error'
                        );
                    }
                });
            }
        });
    });

    $(document).on('click', '.excluir-localidade', function() {
        var idLocalidade = $(this).data('id');
        Swal.fire({
            title: 'Tem certeza?',
            text: "Você não poderá reverter isso!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, exclua!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../server/LocalidadeControl.php', { action: 'deletar', idLocalidade: idLocalidade }, function(response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        Swal.fire(
                            'Excluído!',
                            'A localidade foi excluído.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Erro!',
                            'Ocorreu um erro ao excluir a localidade.',
                            'error'
                        );
                    }
                });
            }
        });
    });

    $(document).on('click', '.excluir-franquia', function() {
        var idFranquia = $(this).data('id');
        Swal.fire({
            title: 'Tem certeza?',
            text: "Você não poderá reverter isso!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, exclua!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../server/FranquiaControl.php', { action: 'deletar', idFranquia: idFranquia }, function(response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        Swal.fire(
                            'Excluído!',
                            'A franquia foi excluído.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Erro!',
                            'Ocorreu um erro ao excluir o franquia.',
                            'error'
                        );
                    }
                });
            }
        });
    });


//############### ALTERAÇÕES
    $('#form-edicao-localidade').on('submit', function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: '../server/LocalidadeControl.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status === 'success') {
                    Swal.fire(
                        'Sucesso!',
                        'Localidade alterada com sucesso!',
                        'success'
                    ).then(() => {
                        window.location.href = 'listaLocalidade.php';
                    });
                } else {
                    Swal.fire(
                        'Erro!',
                        data.message,
                        'error'
                    );
                }
            }
        });
    });

    $('#form-edicao-franquia').on('submit', function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: '../server/FranquiaControl.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status === 'success') {
                    Swal.fire(
                        'Sucesso!',
                        'Franquia alterada com sucesso!',
                        'success'
                    ).then(() => {
                        window.location.href = 'listaFranquia.php';
                    });
                } else {
                    Swal.fire(
                        'Erro!',
                        data.message,
                        'error'
                    );
                }
            }
        });
    });

    $('#form-edicao-franqueado').on('submit', function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: '../server/FranqueadoControl.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status === 'success') {
                    Swal.fire(
                        'Sucesso!',
                        'Franqueado alterado com sucesso!',
                        'success'
                    ).then(() => {
                        window.location.href = 'listaFranqueado.php';
                    });
                } else {
                    Swal.fire(
                        'Erro!',
                        data.message,
                        'error'
                    );
                }
            }
        });
    });

});