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

        alert("Se actualizarón los datos de forma correcta...");

        location.reload();
      }
      else{
        alert("Error...");
      }

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

function agregarUsuarios(nombre,apellidoP,apellidoM,calle,numCalle,cp,ciudad,estado,cel,nombre_Recibe,apellidoP_Recibe,apellidoM_Recibe,email,pass,roll){
  debugger;
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
      debugger;
      if(result==1){

        alert("Se registro el usuario de forma correcta...");

        $('#txtNombre').val('');
        $('#txtApellidoP').val('');
        $('#txtApellidoM').val('');
        $('#txtCalle').val('');
        $('#txtNumCalle').val('');
        $('#txtCp').val('');
        $('#txtCiudad').val('');
        $('#txtEstado').val('');
        $('#txtCel').val('');
        $('#txtEmail').val('');
        $('#txtPass').val('');
        $("#cbmRoll option[value=0]").attr("selected",true);

        $('#ModalRegistroUsuarios').hide();
      }
      else{
        alert("Error...");
      }

    }

  });
}

function loginValidaCostoEnv(email, pass){
  debugger;
  cadena = "EMAIL=" + email + "&PASS=" + pass;

  $.ajax({
    type:"POST",
    url: "php/valUser.php",
    data:cadena,
    success:function(result){
      debugger;
      if(result==1){

        alert("Inicio de sesión correcto, ya puede continuar comprando...");
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
          alert('Debe seleccionar un metodo de envío.');
        }
        location.reload();
      }
      else{
        alert("Usuario o contraseña incorrectos...");
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

        alert("Inicio de sesión correcto, ya puede continuar comprando...");
        $('#txt_Email').val('');
        $('#txt_Pass').val('');
        $('#ModalLogin').hide();

        location.reload();
      }
      else{
        alert("Usuario o contraseña incorrectos...");
      }

    }

  });
}

function AddCart(id, nombre, precio, url, cantidad){

  cadena = "ID=" + id + "&NOMBRE=" + nombre + "&PRECIO=" + precio + "&URL=" + url + "&CANTIDAD=" + cantidad;

  $.ajax({
    type:"POST",
    url: "joyas-h.php",
    data:cadena,
    success:function(result){

      $('#quickview' + id ).hide();
      location.reload();
    }

  });
}

function filtros(minval, maxval, material,accesorio, query){
  debugger;
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
      debugger;
      location.href ="joyas-h.php";
    }
  });
}
function filtrosMujer(minval, maxval, material,accesorio, query){
  debugger;
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
      debugger;
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
      location.reload();
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
