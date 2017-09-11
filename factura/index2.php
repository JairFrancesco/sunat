<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui"> 
 	<link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
 	<link href="https://unpkg.com/vuetify/dist/vuetify.min.css" rel="stylesheet">
</head>
<body>	
	<div id="app">		
	    <v-app>
		    <v-navigation-drawer class="pb-0" permanent clipped height="100%" light>
		      	<v-list dense>
			        <template v-for="(item, i) in items2">
			          	<v-layout row v-if="item.heading" align-center :key="i">
			            	<v-flex xs6>
				              	<v-subheader v-if="item.heading">
				                	{{ item.heading }}
				              	</v-subheader>
			            	</v-flex>
			            	<v-flex xs6 class="text-xs-center">
			              		<a href="#!" class="body-2 black--text"></a>
			            	</v-flex>
			          	</v-layout>
			          <v-list-group v-else-if="item.children" v-model="item.model" no-action>
			            <v-list-tile slot="item" @click="">
			              <v-list-tile-action>
			                <v-icon>{{ item.model ? item.icon : item['icon-alt'] }}</v-icon>
			              </v-list-tile-action>
			              <v-list-tile-content>
			                <v-list-tile-title>
			                  {{ item.text }}
			                </v-list-tile-title>
			              </v-list-tile-content>
			            </v-list-tile>
			            <v-list-tile v-for="(child, i) in item.children" :key="i" @click="">
			              <v-list-tile-action v-if="child.icon">
			                <v-icon>{{ child.icon }}</v-icon>
			              </v-list-tile-action>
			              <v-list-tile-content>
			                <v-list-tile-title>
			                  {{ child.text }}
			                </v-list-tile-title>
			              </v-list-tile-content>
			            </v-list-tile>
			          </v-list-group>
			          <v-list-tile v-else @click="">
			            <v-list-tile-action>
			              <v-icon>{{ item.icon }}</v-icon>
			            </v-list-tile-action>
			            <v-list-tile-content>
			              <v-list-tile-title>
			                {{ item.text }}
			              </v-list-tile-title>
			            </v-list-tile-content>
			          </v-list-tile>
			        </template>
		      	</v-list>
		    </v-navigation-drawer>
		    <v-toolbar class="blue darken-3 toolbar--fixed" dark >
		    	<v-toolbar-side-icon></v-toolbar-side-icon>
		      	<v-toolbar-title>Facturacion Electronica</v-toolbar-title>
		      	<v-spacer></v-spacer>
		      	<v-btn icon>
      <v-icon>search</v-icon>
    </v-btn>
    <v-btn icon>
      <v-icon>apps</v-icon>
    </v-btn>
    <v-btn icon>
      <v-icon>refresh</v-icon>
    </v-btn>
    <v-btn icon>
      <v-icon>more_vert</v-icon>
    </v-btn>
		    </v-toolbar>
		    <main>
		      	<v-container fluid>
	      	
		      		<h4>Facturas <small>dia 11-09-2017</small></h4>
					<template>
					  <v-data-table v-bind:headers="headers" :items="items" hide-actions class="elevation-2">
					    <template slot="items" scope="props">
					      <td><v-chip><v-avatar class="teal">NF</v-avatar>{{ props.item.name }}</v-chip> </td>					      
					      <td >
					      
            <v-chip label small class="cyan lighten-4 cyan--text text--darken-1 ml-0">0001</v-chip>
            <strong>Total 1,133.88</strong>
            <span >, Neumaticos Del sur soc. Cosurmotriz -</span><span class="grey--text" ellipsis> Ref F-18974</span>
          				      
					      </td>
					      <td class="text-xs-right">{{ props.item.calcium }}</td>
					      <td>
						      <span class="group pa-2">						        
						        <v-icon>event</v-icon>						        
						      </span>					      	
					      </td>
					    </template>
					  </v-data-table>
					</template>
		      	</v-container>
		    </main>
	  	</v-app>		
	</div>
 
<script src="https://unpkg.com/vue/dist/vue.js"></script>
<script src="https://unpkg.com/vuetify/dist/vuetify.js"></script>
<script>
 	new Vue({
		el: '#app',
	  	data () {
	      	return {
	        	items2: [
	        		{ heading: 'Principal' },
        			{ icon: 'contacts', text: 'Documentos' },
			        { icon: 'history', text: 'Frequently contacted' },
			        { icon: 'content_copy', text: 'Duplicates' },
			        { heading: 'Labels' },
			        {
			          	icon: 'keyboard_arrow_up',
			          	'icon-alt': 'keyboard_arrow_down',
			          	text: 'Labels',
			          	model: false,
			          	children: [
			            	{ icon: 'add', text: 'Create label' }
			          	]
			        },
			        {
			          	icon: 'keyboard_arrow_up',
			          	'icon-alt': 'keyboard_arrow_down',
			          	text: 'More' ,
			          	model: false,
			          	children: [
				            { text: 'Import' },
				            { text: 'Export' },
				            { text: 'Print' },
				            { text: 'Undo changes' },
				            { text: 'Other contacts' }
			          	]
			        },
			        { icon: 'settings', text: 'Settings' },
			        { icon: 'chat_bubble', text: 'Send feedback' },
			        { icon: 'help', text: 'Help' },
			        { icon: 'phonelink', text: 'App downloads' },
			        { icon: 'keyboard', text: 'Got to the old version' }
      			],
      			headers: [
          			{
            			text: 'Dessert (100g serving)',
            			align: 'left',
            			sortable: false,
            			value: 'name'
          			},		          	
		          	{ text: 'Sodium (mg)', value: 'sodium' },
		          	{ text: 'Calcium (%)', value: 'calcium' },
		          	{ text: 'Iron (%)', value: 'iron' }
        ],
      			items: [
          {
            value: false,
            name: '13946',
            calories: 159,
            fat: 6.0,
            carbs: 24,
            protein: 4.0,
            sodium: 87,
            calcium: '14%',
            iron: '1%'
          },
          {
            value: false,
            name: ' 17614',
            calories: 237,
            fat: 9.0,
            carbs: 37,
            protein: 4.3,
            sodium: 129,
            calcium: '8%',
            iron: '1%'
          },
          {
            value: false,
            name: ' 13945',
            calories: 262,
            fat: 16.0,
            carbs: 23,
            protein: 6.0,
            sodium: 337,
            calcium: '6%',
            iron: '7%'
          },
          {
            value: false,
            name: ' 13944',
            calories: 305,
            fat: 3.7,
            carbs: 67,
            protein: 4.3,
            sodium: 413,
            calcium: '3%',
            iron: '8%'
          },
          {
            value: false,
            name: 'Gingerbread',
            calories: 356,
            fat: 16.0,
            carbs: 49,
            protein: 3.9,
            sodium: 327,
            calcium: '7%',
            iron: '16%'
          },
          {
            value: false,
            name: 'Jelly bean',
            calories: 375,
            fat: 0.0,
            carbs: 94,
            protein: 0.0,
            sodium: 50,
            calcium: '0%',
            iron: '0%'
          },
          {
            value: false,
            name: 'Lollipop',
            calories: 392,
            fat: 0.2,
            carbs: 98,
            protein: 0,
            sodium: 38,
            calcium: '0%',
            iron: '2%'
          },
          {
            value: false,
            name: 'Honeycomb',
            calories: 408,
            fat: 3.2,
            carbs: 87,
            protein: 6.5,
            sodium: 562,
            calcium: '0%',
            iron: '45%'
          },
          {
            value: false,
            name: 'Donut',
            calories: 452,
            fat: 25.0,
            carbs: 51,
            protein: 4.9,
            sodium: 326,
            calcium: '2%',
            iron: '22%'
          },
          {
            value: false,
            name: 'KitKat',
            calories: 518,
            fat: 26.0,
            carbs: 65,
            protein: 7,
            sodium: 54,
            calcium: '12%',
            iron: '6%'
          }
        ],        
	      	}
	    }
	})
</script>
</body>
</html>