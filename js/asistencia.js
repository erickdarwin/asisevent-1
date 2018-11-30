/*=============================================
MOSTRAR GRUPOS AL SELECCIONAR UN PERIODO 
=============================================*/
$('#aperiodo').on('change',function(){
    
    var PeriodoId = document.getElementById("aperiodo").value; 
    $.ajax({
        type:'POST',
        url:'../ajax/asistencias.ajax.php',
        data: {'PeriodoId':PeriodoId},
        success:function(html){   
            $('#agrupo').val(html);     
            $('#agrupo').html(html);     
        }
    }); 
});

$('#agrupo').on('change',function(){
    
    var GrupoId = document.getElementById("agrupo").value;
    var PeriodoId = document.getElementById("aperiodo").value; 
    
    $.ajax({
        type:'POST',
        url:'../ajax/asistencias.ajax.php',
        data: {'PeriodoId':PeriodoId, 'GrupoId':GrupoId},
        success:function(html){   
            $("#inicial").load('inicial.php')            
        }
    }); 
});
