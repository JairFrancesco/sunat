<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style type="text/css" media="screen">
      resumen {
        background-color: #563d7c;
      }
      resumen a:hover {
        background-color: #fff;
      }
    </style>
      <script src="https://unpkg.com/vue"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.7.2/vue-resource.min.js"></script>
  </head>
  <body>
    <!-- Menu Navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #563d7c;  font-size: 120%;">
      <div class="container">
        <a class="navbar-brand" href="#"><i class="fa fa-car"></i> Surmotriz S.R.L</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="#">Inicio <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
              <a class="nav-link disabled" href="#">Disabled</a>
            </li>
          </ul>          
        </div>
      </div>
    </nav>  

    <div class="container" id="app">
      <p><h2>Documentos <small>15/09/2017</small></h2></p> 
      <div class="row">
        <div class="col">

          <form>
            <div class="form-row">
              <div class="col">
                <div class="input-group mb-2 mr-sm-2 mb-sm-0">
              <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
              <input type="text" class="form-control" id="inlineFormInputGroupUsername2" placeholder="15-09-2017" value="15-09-2017">
            </div>
              </div>
              <div class="col">
                <a href="#" class="btn btn-dark" style="background-color: #563d7c;"><i class="fa fa-search"></i> Buscar</a> 
              </div>
            </div>
          </form>

        </div>
        <div class="col-8 text-right">          
          <a href="#" class="btn btn-dark"  style="background-color: #563d7c;"><i class="fa fa-envelope-open-o"></i> Resumen Mes</a>
          <a href="#" class="btn btn-dark" style="background-color: #563d7c;"><i class="fa fa-envelope-open-o"></i> Resumen Dia</a>
        </div>        
      </div>
          
                
      <p>
        <table class="table table-md table-bordered table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Serie <i class="fa fa-caret-up"></i></th>
              <th>Numero</th>
              <th>Cliente</th>
              <th>Imp</th>
              <th>Anu</th>
              <th class="text-center">OT</th>
              <th>Sunat</th>
              <th class="text-right">Total</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="document in documents">
              <th>{{document.id}}</th>
              <td>{{document.serie}}</td>
              <td>{{document.numero}}</td>
              <td>{{document.cliente}}</td>
              <td>{{document.impresion}}</td>
              <td>{{document.anulado}}</td>
              <td class="text-center">{{document.ot}}</td>
              <td>{{document.sunat_codigo}}</td>
              <td class="text-right">{{document.moneda}} {{document.total}}</td>
              <td class="text-center">
                <a href="" target="_blank"  class="btn btn-sm btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">PDF</a>
                <a href="#" class="btn btn-sm btn-info">XML</a>
                <a href="#" class="btn btn-sm btn-secondary">COM</a>
              </td>
            </tr>            
          </tbody>
        </table>
        <pre>{{ $data || documents}}</pre>
      </p>

      <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">Factura F001-17456</h5>

                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <p>
                    <table class="table table-striped table-sm">
                        <tr>
                            <td colspan="4">
                                <div>Fecha : 19-09-2017</div>
                                <div>Cliente : EPS TACNA S.A</div>
                                <div>RUC : 20134052989</div>
                                <div>Dirección : AV. DOS DE MAYO 372</div>
                                <div>Forma de Pago : CREDITO</div>
                                <div>Ubigeo : Tacna-Tacna-Tacna</div>
                            </td>
                            <td colspan="4">
                                <div>Ord. Trab : 23563</div>
                                <div>Placa/Serie : Z4O711</div>
                                <div>Modelo/Año : HILUX - 2007</div>
                                <div>Motor/Chasis : 8AJFX22G976002363</div>
                                <div>Color : BLANCO</div>
                                <div>Km : 234592</div>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-left">Código</th>
                            <th class="text-left">Descripción</th>
                            <th class="text-center">Cant</th>
                            <th class="text-right">P. Unit</th>
                            <th class="text-right">Import</th>
                            <th class="text-right">Descto</th>
                            <th class="text-right">V. Venta</th>
                        </tr>
                        <tr>
                            <td class="text-center">1</td>
                            <td class="text-left">9036340020P</td>
                            <td class="text-left">RODAJE ALT. DE AC KOYO</td>
                            <td class="text-center">4</td>
                            <td class="text-right">101.69</td>
                            <td class="text-right">101.69</td>
                            <td class="text-right">10.17</td>
                            <td class="text-right">91.52</td>
                        </tr><tr>
                            <td class="text-center">1</td>
                            <td class="text-left">9036340020P</td>
                            <td class="text-left">RODAJE ALT. DE AC KOYO</td>
                            <td class="text-center">4</td>
                            <td class="text-right">101.69</td>
                            <td class="text-right">101.69</td>
                            <td class="text-right">10.17</td>
                            <td class="text-right">91.52</td>
                        </tr><tr>
                            <td class="text-center">1</td>
                            <td class="text-left">9036340020P</td>
                            <td class="text-left">RODAJE ALT. DE AC KOYO</td>
                            <td class="text-center">4</td>
                            <td class="text-right">101.69</td>
                            <td class="text-right">101.69</td>
                            <td class="text-right">10.17</td>
                            <td class="text-right">91.52</td>
                        </tr><tr>
                            <td class="text-center">1</td>
                            <td class="text-left">9036340020P</td>
                            <td class="text-left">RODAJE ALT. DE AC KOYO</td>
                            <td class="text-center">4</td>
                            <td class="text-right">101.69</td>
                            <td class="text-right">101.69</td>
                            <td class="text-right">10.17</td>
                            <td class="text-right">91.52</td>
                        </tr><tr>
                            <td class="text-center">1</td>
                            <td class="text-left">9036340020P</td>
                            <td class="text-left">RODAJE ALT. DE AC KOYO</td>
                            <td class="text-center">4</td>
                            <td class="text-right">101.69</td>
                            <td class="text-right">101.69</td>
                            <td class="text-right">10.17</td>
                            <td class="text-right">91.52</td>
                        </tr><tr>
                            <td class="text-center">1</td>
                            <td class="text-left">9036340020P</td>
                            <td class="text-left">RODAJE ALT. DE AC KOYO</td>
                            <td class="text-center">4</td>
                            <td class="text-right">101.69</td>
                            <td class="text-right">101.69</td>
                            <td class="text-right">10.17</td>
                            <td class="text-right">91.52</td>
                        </tr>
                    </table>
                  </p>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-primary">Save changes</button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
          </div>
        </div>
      </div>


    </div>
    <script>
        Vue.component('modal',{
            template: '#modal-template'
        })
        var app = new Vue({
            el: '#app',
            created: function () {
                this.getDocuments();
            },
            data: {
                documents: []
            },

            methods: {
                getDocuments: function(){
                    this.$http.get('http://localhost/sunat/factura/apis/index.php').then(function (response) {
                        this.documents = response.data;
                    })
                }
            }
        })
    </script>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

  </body>
</html>