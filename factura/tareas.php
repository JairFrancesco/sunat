<!DOCTYPE html>
<html>
<head>
	<!-- bootstrap 3 -->
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">	
	<script src="../bootstrap/js/jquery.min.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://unpkg.com/vue"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<body>
	<?php 
		include "layout/__auth.php";
		include "layout/__nav_bar.php";
	?>
    <div class="container" id="app">
        <h1>
            Ultimas 50 Tareas Sistemas 
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#nuevo">
                Nuevo
            </button>
        </h1>
        <div class="text-center" v-show="loading_tareas">
            <i v-show="loading_tareas"  class="fa fa-spinner fa-3x fa-spin"></i>
        </div>
        <table class="table" v-show="!loading_tareas">
            <tr class="well">
                <th>#</th>
                <th width="35%">Nombre</th>
                <th>Descripcion</th>
                <th width="10%">Fecha</th>
                <th>Acciones</th>
            </tr>
            <tr v-for="(tarea,index) in tareas">
                <td>{{index+1}}</td>
                <td>{{tarea.NOMBRE}}</td>
                <td>{{tarea.DESCRIPCION}}</td>
                <td>{{tarea.FECHA}}</td>
                <td>
                    <a href="#" @click="getDetalle(tarea)">Detalle</a>
                </td>
            </tr>
        </table>      

        <!-- Modal Nuevo -->
        <div class="modal fade" id="nuevo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Agregar Nuevo</h4>
                </div>
                <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Nombre</label>
                        <div class="col-sm-10">
                        <input type="text" v-model="addTarea.nombre" class="form-control" placeholder="Nombre">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Descripcion</label>
                        <div class="col-sm-10">
                        <textarea name="" class="form-control" v-model="addTarea.descripcion" cols="30" rows="5" placeholder="Descripcion"></textarea>
                        </div>
                    </div>
                </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" data-dismiss="modal" v-on:click.prevent="agregarTarea(addTarea)" >Guardar</button>
                </div>

                </div>
            </div>
        </div>

        <!-- Modal Detalle -->
        <div class="modal fade" id="detalle" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Tarea Detalle {{tareaDetalle.FECHA}}</h4>
                    </div>
                    <div class="modal-body">
                        <h3>{{tareaDetalle.NOMBRE}}</h3>
                        <p>{{tareaDetalle.DESCRIPCION}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        var app = new Vue({
            el: '#app',            
            data: {
                tareas: [],
                loading_tareas: false,
                addTarea: [],
                tareaDetalle: []
            },
            methods: {
                getTareas: function () {
                    this.loading_tareas=true;
                    axios.get('./apis/tareas.php').then(response => {
                        this.loading_tareas=false;
                        this.tareas = response.data;
                    });
                },
                agregarTarea: function(addTarea){
                    axios.post('./apis/tareas_add.php',`nombre=${addTarea.nombre}&descripcion=${addTarea.descripcion}`)
                    .then(response => {
                        console.log(response.data);
                    });
                    this.addTarea = [];
                },
                getDetalle: function (tareaDetalle) {
                    $("#detalle").modal('show');
                    this.tareaDetalle = tareaDetalle;
                }
            },
            created: function () {
                this.getTareas();
            }
        });
    </script>

</body>

</html>
