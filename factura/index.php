<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui"> 
 	<link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
 	<link href="https://unpkg.com/vuetify/dist/vuetify.min.css" rel="stylesheet">
</head>
<body>	
	<div id="app">
	  <v-app id="inspire">
	    <v-app id="example-2" toolbar>
	    <v-navigation-drawer absolute persistent light :mini-variant.sync="mini" v-model="drawer" overflow>
	      <v-toolbar flat class="transparent">
	        <v-list class="pa-0">
	          <v-list-tile avatar>
	            <v-list-tile-avatar>
	             	<img src="https://randomuser.me/api/portraits/men/85.jpg" />
	            </v-list-tile-avatar>
	            <v-list-tile-content>
	              	<v-list-tile-title>TOYOTA SURMOTRIZ</v-list-tile-title>
	            </v-list-tile-content>
	            <v-list-tile-action>
					<v-btn icon @click.native.stop="mini = !mini">
						<v-icon>chevron_left</v-icon>
					</v-btn>
	            </v-list-tile-action>
	          </v-list-tile>
	        </v-list>
	      </v-toolbar>
	      <v-list class="pt-0" dense>
	        <v-divider></v-divider>
	        <v-list-tile v-for="item in itemMenus" :key="item.title" @click="">
	          <v-list-tile-action>
	            <v-icon>{{ item.icon }}</v-icon>
	          </v-list-tile-action>
	          <v-list-tile-content>
	            <v-list-tile-title>{{ item.title }}</v-list-tile-title>
	          </v-list-tile-content>
	        </v-list-tile>
	      </v-list>
	    </v-navigation-drawer>
	    <v-toolbar fixed class="indigo darken-4" dark>
	      <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
	      <v-toolbar-title>Facturacion Electronica</v-toolbar-title>
	    </v-toolbar>
	    <main>
	      	<v-container fluid>
		      	<h4>Documentos <small>día 08-09-2017</small></h4>		      
			    <v-data-table v-bind:headers="headers" :items="items" hide-actions class="elevation-1">
				    <template slot="items" scope="props">
						<td class="text-xs-center">{{ props.item.id }}</td>			      
				      	<td class="text-xs-center">{{ props.item.docu }}</td>			      
				      	<td class="text-xs-center">{{ props.item.cliente }}</td>			      
				      	<td class="text-xs-center">{{ props.item.eliminado }}</td>			      
				      	<td class="text-xs-center">{{ props.item.sunat }}</td>			      
				      	<td class="text-xs-right">{{ props.item.total }}</td>			      
				      	<td class="text-xs-right">{{ props.item.acciones }} <v-btn primary dark slot="activator">Open Dialog</v-btn></td>			      
				    </template>
			  	</v-data-table>
			  	<v-layout row justify-center>
				    <v-dialog v-model="dialog" persistent>
				      <v-btn primary dark slot="activator">Open Dialog</v-btn>
				      <v-card>
				        <v-card-title class="headline">Use Google's location service?</v-card-title>
				        <v-card-text>Let Google help apps determine location. This means sending anonymous location data to Google, even when no apps are running.</v-card-text>
				        <v-card-actions>
				          <v-spacer></v-spacer>
				          <v-btn class="green--text darken-1" flat="flat" @click.native="dialog = false">Disagree</v-btn>
				          <v-btn class="green--text darken-1" flat="flat" @click.native="dialog = false">Agree</v-btn>
				        </v-card-actions>
				      </v-card>
				    </v-dialog>
				</v-layout>
	      	</v-container>
	    </main>
	  </v-app>
	  </v-app>
	</div>	
 
 <script src="https://unpkg.com/vue/dist/vue.js"></script>
 <script src="https://unpkg.com/vuetify/dist/vuetify.js"></script>
 <script>
 	new Vue({
		el: '#app',
	  	data () {
	      	return {
	        	drawer: true,
		        itemMenus: [
		          	{ title: 'Documentos', icon: 'dashboard' },
		          	{ title: 'Resumenes', icon: 'question_answer' },
		          	{ title: 'Bajas', icon: 'question_answer' },
		          	{ title: 'Reportes por Mes', icon: 'question_answer' },
		        ],
	        	mini: false,
	        	right: null,

	        	headers: [
		        		{ text: 'id', value: 'id', align: 'center' },
						{ text: 'Nro Docu', value: 'docu', align: 'center' },
						{ text: 'Cliente', value: 'cliente', align: 'center' },
						{ text: 'Eliminado', value: 'eliminado', align: 'center' },
						{ text: 'Sunat', value: 'sunat', align: 'center' },
						{ text: 'Total S/.', value: 'total', align: 'right' },
						{ text: 'Acciones', value: 'acciones' }					
					],
					items: [
						{ id: '01', docu: 'F001-17153', cliente: 'Caceres Mamani Angela cec', eliminado: 'Si', sunat: '0001', total: '3,338.72 S/.', acciones: 'PDF XML COMP'},					
					],
				dialog: false
	      	}
	    }
	})
 </script>
</body>
</html>