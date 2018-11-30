/*=============================================
MOSTRAR GRUPOS AL SELECCIONAR UN PERIODO 
=============================================*/
$('#tPeriodo').on('change',function(){
    
    var tPeriodoId = document.getElementById("tPeriodo").value;
    $.ajax({
        type:'POST',
        url:'../ajax/grupohorario.ajax.php',
        data: {'tPeriodoId':tPeriodoId,'tNivelId':1},
        success:function(html){   
            $('#tiGrupo').html(html);   
        }
    });
    
    $.ajax({
        type:'POST',
        url:'../ajax/grupohorario.ajax.php',
        data: {'tPeriodoId':tPeriodoId,'tNivelId':2},
        success:function(html){   
            $('#tpGrupo').html(html);   
        }
    });
    
    $.ajax({
        type:'POST',
        url:'../ajax/grupohorario.ajax.php',
        data: {'tPeriodoId':tPeriodoId,'tNivelId':3},
        success:function(html){   
            $('#tsGrupo').html(html);   
        }
    });
    
});


$('#tiGrupo').on('change',function(){
    
    var tPeriodoId = document.getElementById("tPeriodo").value;
    var tiGrupo = document.getElementById("tiGrupo").value;
    var condicioni = document.getElementById("condicioni").value;
    var fecha = document.getElementById("fechai").value;
    
    if(fecha==""){
        f =new Date();
        fecha = f.getFullYear()+"-"+(f.getMonth()+1)+"-"+f.getDate();
        document.getElementById('fechai').value=fecha;
    }
    
    $.ajax({
        type:'POST',
        url:'../Reportes/tablatelefonos.php',
        data: {'tPeriodoId':tPeriodoId,'tiGrupo':tiGrupo,'condicioni':condicioni,'fecha':fecha},
        success:function(html){   
            $('#tablainicial').html(html);   
        }
    }); 
    
    
});

$('#condicioni').on('change',function(){
    
    var tPeriodoId = document.getElementById("tPeriodo").value;
    var tiGrupo = document.getElementById("tiGrupo").value;
    var condicioni = document.getElementById("condicioni").value;
    var fecha = document.getElementById("fechai").value;
    if(fecha==""){
        f =new Date();
        fecha = f.getFullYear()+"-"+(f.getMonth()+1)+"-"+f.getDate();
        document.getElementById('fechai').value=fecha;
    }
    
    $.ajax({
        type:'POST',
        url:'../Reportes/tablatelefonos.php',
        data: {'tPeriodoId':tPeriodoId,'tiGrupo':tiGrupo,'condicioni':condicioni,'fecha':fecha},
        success:function(html){   
            $('#tablainicial').html(html);   
        }
    }); 
});

function fechai() {
  var tPeriodoId = document.getElementById("tPeriodo").value;
  var tiGrupo = document.getElementById("tiGrupo").value;
  var condicioni = document.getElementById("condicioni").value;
  var fecha = document.getElementById("fechai").value;

  $.ajax({
    type:'POST',
    url:'../Reportes/tablatelefonos.php',
    data: {'tPeriodoId':tPeriodoId,'tiGrupo':tiGrupo,'condicioni':condicioni,'fecha':fecha},
    success:function(html){   
        $('#tablainicial').html(html);   
    }
  }); 

}

/*=============================================
MOSTRAR GRUPOS AL SELECCIONAR UN PERIODO PRIMARIA
=============================================*/

$('#tpGrupo').on('change',function(){
    var tPeriodoId = document.getElementById("tPeriodo").value;
    var tpGrupo = document.getElementById("tpGrupo").value;
    var condicionp = document.getElementById("condicionp").value;
    var fecha = document.getElementById("fechap").value;
    if(fecha==""){
        f =new Date();
        fecha = f.getFullYear()+"-"+(f.getMonth()+1)+"-"+f.getDate();
        document.getElementById('fechap').value=fecha;
    }
    
    $.ajax({
        type:'POST',
        url:'../Reportes/tablatelefonosp.php',
        data: {'tPeriodoId':tPeriodoId,'tpGrupo':tpGrupo,'condicionp':condicionp,'fecha':fecha},
        success:function(html){   
            $('#tablaprimaria').html(html);   
        }
    }); 
});

$('#condicionp').on('change',function(){
    
    var tPeriodoId = document.getElementById("tPeriodo").value;
    var tpGrupo = document.getElementById("tpGrupo").value;
    var condicionp = document.getElementById("condicionp").value;
    var fecha = document.getElementById("fechap").value;
    if(fecha==""){
        f =new Date();
        fecha = f.getFullYear()+"-"+(f.getMonth()+1)+"-"+f.getDate();
        document.getElementById('fechap').value=fecha;
    }
    
    $.ajax({
        type:'POST',
        url:'../Reportes/tablatelefonosp.php',
        data: {'tPeriodoId':tPeriodoId,'tpGrupo':tpGrupo,'condicionp':condicionp,'fecha':fecha},
        success:function(html){   
            $('#tablaprimaria').html(html);   
        }
    }); 
});

function fechap() {
  var tPeriodoId = document.getElementById("tPeriodo").value;
  var tpGrupo = document.getElementById("tpGrupo").value;
  var condicionp = document.getElementById("condicionp").value;
  var fecha = document.getElementById("fechap").value;

  $.ajax({
    type:'POST',
    url:'../Reportes/tablatelefonosp.php',
    data: {'tPeriodoId':tPeriodoId,'tpGrupo':tpGrupo,'condicionp':condicionp,'fecha':fecha},
    success:function(html){   
        $('#tablaprimaria').html(html);   
    }
  }); 

}

/*=============================================
MOSTRAR GRUPOS AL SELECCIONAR UN PERIODO SECUNDARIA
=============================================*/

$('#tsGrupo').on('change',function(){
    var tPeriodoId = document.getElementById("tPeriodo").value;
    var tsGrupo = document.getElementById("tsGrupo").value;
    var condicions = document.getElementById("condicions").value;
    var fecha = document.getElementById("fechas").value;
    if(fecha==""){
        f =new Date();
        fecha = f.getFullYear()+"-"+(f.getMonth()+1)+"-"+f.getDate();
        document.getElementById('fechas').value=fecha;

    }
    $.ajax({
        type:'POST',
        url:'../Reportes/tablatelefonoss.php',
        data: {'tPeriodoId':tPeriodoId,'tsGrupo':tsGrupo,'condicions':condicions,'fecha':fecha},
        success:function(html){   
            $('#tablasecundaria').html(html);   
        }
    }); 
});

$('#condicions').on('change',function(){
    
    var tPeriodoId = document.getElementById("tPeriodo").value;
    var tsGrupo = document.getElementById("tsGrupo").value;
    var condicions = document.getElementById("condicions").value;
    var fecha = document.getElementById("fechas").value;
    if(fecha==""){
        f =new Date();
        fecha = f.getFullYear()+"-"+(f.getMonth()+1)+"-"+f.getDate();
        document.getElementById('fechas').value=fecha;
    }
    $.ajax({
        type:'POST',
        url:'../Reportes/tablatelefonoss.php',
        data: {'tPeriodoId':tPeriodoId,'tsGrupo':tsGrupo,'condicions':condicions,'fecha':fecha},
        success:function(html){   
            $('#tablasecundaria').html(html);   
        }
    }); 
});

function fechas() {
  var tPeriodoId = document.getElementById("tPeriodo").value;
  var tsGrupo = document.getElementById("tsGrupo").value;
  var condicions = document.getElementById("condicions").value;
  var fecha = document.getElementById("fechas").value;

  $.ajax({
    type:'POST',
    url:'../Reportes/tablatelefonoss.php',
    data: {'tPeriodoId':tPeriodoId,'tsGrupo':tsGrupo,'condicions':condicions,'fecha':fecha},
    success:function(html){   
        $('#tablasecundaria').html(html);   
    }
  }); 

}



