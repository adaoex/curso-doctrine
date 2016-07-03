$(function () {
    
    $('input[name="valor"]').mask("#.##0,00", {reverse: true});
    
//    var campos = [];
//    $("#form-").change(function (event) {
//        campos = $(this).serialize();
//        campos.append('imagem', event.target.files[0]);
//        //var name = event.target.files[0].content.name; // para capturar o nome do arquivo com sua extenção
//    });
    
    $("#form-produto").submit(function (event) {
        event.preventDefault();
        
        var formData = new FormData($(this)[0]);
        
        var $form = $(this),
            id = $form.find("input[name='id']").val(),
            campos = $form.serialize(), 
            el_tags = $form.find("input[name='tags']:checked"), 
            arr_tags = [];
        $.each(el_tags, function(idx, val){
            arr_tags.push( $(val).val() );
        });
       
        var success = function (data) {
            if (data.success){
                $('.starter-template h2')
                        .after('<div class="alert alert-success alert-dismissible" role="alert"> \n\
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">\n\
                        <span aria-hidden="true">&times;</span></button>\n\
                        <strong>OK!</strong> Salvo com sucesso. </div>');
            }else{
                var mensagem = "";
                if ( data.errors !== undefined ){
                    for (i=0, len = data.errors.length; i<len; i++ ){
                        mensagem += data.errors[i] + "<br>";
                    }
                }
                $('.starter-template h2')
                        .after('<div class="alert alert-warning alert-dismissible" role="alert"> \n\
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">\n\
                        <span aria-hidden="true">&times;</span></button>\n\
                        <strong>Erro!</strong> Ocorreu um erro. <br>\n\
                        '+mensagem+' </div>');
            }
            setTimeout(function () {
                location.reload();
            }, 4000);
        };
        
        if ( id.length === 0 ){
            $.ajax({type: "POST",contentType:false, processData:false, url: '/api/produtos',data: formData,success: success,dataType: 'json'});
        }else{
            $.ajax({type: "POST",contentType:false, processData:false, url: '/api/produtos/'+id,data: formData,success: success,dataType: 'json'});
        }
    });
    
    $(".btn-danger").click(function(e){
        var id = $(this).attr('data-id');
        
        $.ajax({
            type: "DELETE",
            data: {_method: 'DELETE'},
            url: '/api/produtos/' + id,
            success: function(){
                window.location.reload();
            },
            dataType: 'json'
        });
        return false;
    });
    
});