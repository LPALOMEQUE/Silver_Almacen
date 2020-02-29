

function ModDatosUsuarios(nombre,nombre_recibe,calle,numCalle,cp,ciudad,estado,cel,email){
debugger;
  cadena = "NOMBRE=" +nombre +
  "&NOMBRE_RECIBE=" +nombre_recibe +
  "&CALLE=" + calle +
  "&numCalle=" + numCalle +
  "&CP=" + cp +
  "&CIUDAD=" + ciudad +
  "&ESTADO=" + estado +
  "&CEL=" + cel +
  "&EMAIL=" + email;

  $.ajax({
    type:"POST",
    url: "php/modUsuarios.php",
    data:cadena,
    success:function(result){
      debugger;

      if(result==1){
        alertify.success("Se actualizarón los datos de forma correcta.");

        // location.reload();
      }
      else{
        alertify.error("Error.");

      }

    }

  });

}

function valStock(){
debugger;
cadena = '';
  $.ajax({
    type:"POST",
    url: "checkStock.php",
    data:cadena,
    success:function(result){
      debugger;

      if(result==1){
        alertify.error("Hay un artículo que ya no esta disponible, verifiquelo.");
      }
      else{
        location.href = 'VerificadorConsig.php';

      }
    }
  });
}

function busClienteConsigna(nombreCliente){
  cadena = "NOMBREC_Consigna=" +nombreCliente;

  $.ajax({
    type:"POST",
    url: "index.php",
    data:cadena,
    success:function(result){
      location.href = 'index.php?o_cli=1';
    }
  });
}



function envioDatosEmail(nombre,apellidoP,apellidoM,calle,numCalle,cp,ciudad,estado,cel,email,paymentToken,paymentID){
  cadena = "NOMBRE=" +nombre +
  "&apellidoP=" + apellidoP +
  "&apellidoM=" +apellidoM +
  "&CALLE=" + calle +
  "&numCalle=" + numCalle +
  "&CP=" + cp +
  "&CIUDAD=" + ciudad +
  "&ESTADO=" + estado +
  "&CEL=" + cel +
  "&EMAIL=" + email +
  "&paymentToken=" +paymentToken +
  "&paymentID=" + paymentID;

  $.ajax({
    type:"GET",
    url: "php/verificador.php",
    data:cadena,
    success:function(result){
      location.href = 'php/verificador.php'
    }

  });

}

function getCliente(id_cliente,nombre){
  cadena = "ID_CLIENTEPost=" + id_cliente + "&NombreHide=" + nombre ;

  $.ajax({
    type:"POST",
    url: "index.php",
    data:cadena,
    success:function(result){
      // $('#ModalViewClientes').hide();
      location.href = 'joyas-m.php';

    }

  });

}

function agregarUsuarios(nombre,apellidoP,apellidoM,calle,numCalle,cp,ciudad,estado,cel,nombre_Recibe,apellidoP_Recibe,apellidoM_Recibe,email,pass,roll){

  cadena = "NOMBRE=" +nombre +
  "&apellidoP=" + apellidoP +
  "&apellidoM=" +apellidoM +
  "&CALLE=" + calle +
  "&numCalle=" + numCalle +
  "&CP=" + cp +
  "&CIUDAD=" + ciudad +
  "&ESTADO=" + estado +
  "&CEL=" + cel +
  "&NOMBRE_RECIBE=" + nombre_Recibe +
  "&apellidoP_Recibe=" + apellidoP_Recibe +
  "&apellidoM_Recibe=" + apellidoM_Recibe +
  "&EMAIL=" + email +
  "&PASS=" + pass +
  "&ROLL=" + roll;


  $.ajax({
    type:"POST",
    url: "php/agregarUsuarios.php",
    data:cadena,
    success:function(result){
      if(result==1){

        // CLIENTES REMISION
        $('#txtNombre').val('');
        $('#txtApellidoP').val('');
        $('#txtApellidoM').val('');
        $('#txtCalle').val('');
        $('#txtNumCalle').val('');
        $('#txtCp').val('');
        $('#txtCiudad').val('');
        $('#txtEstado').val('');
        $('#txtCel').val('');
        $('#txtNombre_Recibe').val('');
        $('#txtApellidoP_Recibe').val('');
        $('#txtApellidoM_Recibe').val('');
        $('#txtEmail').val('');
        $('#txtPass').val('');

        // CLIENTES CONSIGNA
        $('#txtNombreC').val('');
        $('#txtApellidoPC').val('');
        $('#txtApellidoMC').val('');
        $('#txtCalleC').val('');
        $('#txtNumCalleC').val('');
        $('#txtCpC').val('');
        $('#txtCiudadC').val('');
        $('#txtEstadoC').val('');
        $('#txtCelC').val('');
        $('#txtEmailC').val('');

        $('#ModalRegistroUsuarios').hide();

        alertify.success("Se registro el usuario de forma correcta.");

        // location.reload();
      }
      else{
        alertify.error("Error.");
      }
    }
  });
}

function loginValidaCostoEnv(email, pass){
  cadena = "EMAIL=" + email + "&PASS=" + pass;

  $.ajax({
    type:"POST",
    url: "php/valUser.php",
    data:cadena,
    success:function(result){
      if(result==1){
        $('#txt_Email').val('');
        $('#txt_Pass').val('');
        $('#ModalLogin').hide();

        if($("#customRadio1").is(':checked') || $("#customRadio2").is(':checked')) {

          precioEnvio = $('input:radio[name=rbDelivery]:checked').val();

          if (precioEnvio == 'express') {
            x = 700;
          }
          else{
            x = 250;
          }
          pruebas(x);
        }else{
          $('#ModalViewStatusEnvioError').modal('toggle');

        }
        location.reload();
      }
      else{
        $('#ModalViewStatusLoginError').modal('toggle');
      }
    }
  });
}

function login(email, pass){

  cadena = "EMAIL=" + email + "&PASS=" + pass;
  $.ajax({
    type:"POST",
    url: "php/valUser.php",
    data:cadena,
    success:function(result){
      if(result==1){
        alertify.success("Inicio de sesión correcto.");

        location.reload();
      }
      else{
        alertify.error("Usuario o contraseña incorrecto.");
      }
    }
  });
}

function AddCart(id, cantidad){

  cadena = "ID=" + id  + "&CANTIDAD=" + cantidad;

  $.ajax({
    type:"POST",
    url: "index.php",
    data:cadena,
    success:function(result){
      location.reload();
    }
  });
}

function filtros(minval, maxval, material,accesorio, query){
  cadena = 'MinVal=' + minval +
  '&MaxVal=' + maxval +
  '&Material=' + material +
  '&Accesorio=' + accesorio +
  '&QUERY=' + query;

  $.ajax({
    type: "POST",
    url: "joyas-h.php",
    data: cadena,
    success: function(result){
      location.href ="joyas-h.php";
    }
  });
}
function filtrosMujer(minval, maxval, material,accesorio, query){
  cadena = 'MinVal=' + minval +
  '&MaxVal=' + maxval +
  '&Material=' + material +
  '&Accesorio=' + accesorio +
  '&QUERY=' + query;

  $.ajax({
    type: "POST",
    url: "joyas-m.php",
    data: cadena,
    success: function(result){
      location.href ="joyas-m.php";
    }
  });
}

function limpiarPriceFilter(vaciar){
  cadena = "VaciarFilterP=" + vaciar;

  $.ajax({
    type: "POST",
    url: "joyas-h.php",
    data: cadena,
    success: function(result){
      location.href ="joyas-h.php";
    }
  });
}
function limpiarPriceFilterM(vaciar){
  cadena = "VaciarFilterP=" + vaciar;

  $.ajax({
    type: "POST",
    url: "joyas-m.php",
    data: cadena,
    success: function(result){
      location.href ="joyas-m.php";
    }
  });
}
function logOut(vaciar){

  cadena = "VACIAR_LOGIN=" + vaciar;

  $.ajax({

    type:"POST",
    url: "checkout.php",
    data: cadena,
    success: function(result){
      location.href ="index.php";
    }
  });
}

function cartModPrice(id, cantidad, posicion){
  cadena = "ID=" + id + "&CANTIDAD=" + cantidad + "&Posicion=" + posicion;

  $.ajax({
    type:"POST",
    url: "cart.php",
    data:cadena,
    success:function(result){
      location.reload();
    }

  });

}

function eliminarArticulo(id, posicion, valida){
  cadena = "ID=" + id + "&Posicion=" + posicion + "&DelArt=" + valida;

  $.ajax({
    type:"POST",
    url: "cart.php",
    data:cadena,
    success:function(result){
      // location.href="cart.php";
      location.reload();
    }
  });
}

function getPriceDeli(precioEnvio, total){

  cadena = "MONTO=" + precioEnvio + "&finalTotal=" + total;

  $.ajax({
    type:"POST",
    url: "cart.php",
    data:cadena,
    success:function(result){
      var ventaTotal = total;
      var montoEnvio = precioEnvio;
      const formatterDolar = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
      })
      $('#txtcost').val(formatterDolar.format(montoEnvio));
      $('#txtcostT').val(formatterDolar.format(ventaTotal));
    }

  });

}

function pruebas(envio, vtaTotal){
  cadena = "MONTO=" + envio + "&VTATOTAL=" + vtaTotal;

  $.ajax({
    type:"POST",
    url: "checkout.php",
    data:cadena,
    success:function(result){
      location.href ="checkout.php";
    }
  });
}

function guardarArt(nomArt,  descArt, barCode, modelArt, marcaArt, precioArt, categoria, subCatego, statusArt, nameArticulo){
  cadena = "NomArt=" + nomArt +
  "&DescArt=" + descArt +
  "&BarCode=" + barCode +
  "&ModelArt=" + modelArt +
  "&MarcaArt=" + marcaArt +
  "&PrecioArt=" + precioArt +
  "&Categoria=" + categoria +
  "&SubCatego=" + subCatego +
  "&StatusArt=" + statusArt +
  "&NombreImg=" + nameArticulo;

  $.ajax({
    type:"POST",
    url: "php/agregarArticulos.php",
    data:cadena,
    success:function(result){

      if(result==1){

        alert("Se registro el artículo de forma correcta...");
        $('#txtNameArt').val('');
        $('#txtDescArt').val('');
        $('#txtBarCode').val('');
        $('#txtModelo').val('');
        $("#cbmMarca option[value=0]").attr("selected",true);
        $('#txtPrecio').val(0);
        $("#cbmCategoria option[value=0]").attr("selected",true);
        $("#cbmSubcategoria option[value=0]").attr("selected",true);
        $("#cbmStatus option[value=2]").attr("selected",true);

      }
      else{
        alert("Error...");
      }
    }

  });
}

function buscarArticulos(buscador){
  cadena = "BarCode=" + buscador;

  $.ajax({
    type:"POST",
    url: "index.php",
    data:cadena,
    success:function(result){

      x=result;

    }
  });
}
