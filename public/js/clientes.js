$(function () {
    $("#form-cliente").submit(function (event) {
        event.preventDefault();

        var $form = $(this),
            id = $form.find("input[name='id']").val(),
            campos = $form.serialize();
        
        var success = function (data) {
            if (data.success){
                $('.starter-template h2')
                        .after('<div class="alert alert-success alert-dismissible" role="alert"> \n\
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">\n\
                        <span aria-hidden="true">&times;</span></button>\n\
                        <strong>OK!</strong> Salvo com sucesso. </div>');
            }else{
                $('.starter-template h2')
                        .after('<div class="alert alert-warning alert-dismissible" role="alert"> \n\
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">\n\
                        <span aria-hidden="true">&times;</span></button>\n\
                        <strong>Erro!</strong> Ocorreu um erro. </div>');
            }
        };
        
        if ( id.length === 0 ){
            $.ajax({type: "POST",url: '/api/clientes',data: campos,success: success,dataType: 'json'});
        }else{
            $.ajax({type: "POST",url: '/api/clientes/'+id,data: campos,success: success,dataType: 'json'});
        }
    });
    
    $(".btn-danger").click(function(e){
        var id = $(this).attr('data-id');
        
        $.ajax({
            type: "DELETE",
            data: {_method: 'DELETE'},
            url: '/api/clientes/' + id,
            success: function(){
                window.location.reload();
            },
            dataType: 'json'
        });
        return false;
    });
    
});