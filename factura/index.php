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

    <div class="container">
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
        <table class="table table-md table-bordered">
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
            <tr>
              <th>1</th>
              <td>B001</td>
              <td>13979</td>
              <td>Shaika Muhammad Farooq</td>
              <td>D</td>
              <td>N</td>
              <td class="text-center">--</td>
              <td>0001</td>
              <td class="text-right">S/ 1,416.65</td>
              <td class="text-center">
                <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target=".bd-example-modal-sm">PDF</a>
                <a href="#" class="btn btn-sm btn-info">XML</a>
                <a href="#" class="btn btn-sm btn-secondary">COM</a>
              </td>
            </tr>            
          </tbody>
        </table>
      </p>
      <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            
          </div>
        </div>
      </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
  </body>
</html>