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
        <v-navigation-drawer  :mini-variant.sync="mini" permanent light v-model="drawer" class="elevation-1">
          <v-toolbar flat class="transparent">
            <v-list class="pa-0">
              <v-list-tile avatar>
                <v-list-tile-avatar>
                  <img src="https://randomuser.me/api/portraits/men/85.jpg" />
                </v-list-tile-avatar>
                <v-list-tile-content>
                  <v-list-tile-title>Surmotriz SRL</v-list-tile-title>
                </v-list-tile-content>
                <v-list-tile-action>
                  <v-btn icon @click.native.stop="mini = !mini">
                    <v-icon>chevron_left</v-icon>
                  </v-btn>
                </v-list-tile-action>
              </v-list-tile>
            </v-list>
          </v-toolbar>
          <v-divider></v-divider>
          <v-list>
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
        <v-toolbar class="blue darken-3 toolbar--fixed" dark>
          <v-toolbar-side-icon @click.native.stop="mini = !mini"></v-toolbar-side-icon>
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
            <v-card>
              <v-card-title class="elevation-2">
                <h4>Documentos <small v-on:click="modal=true">14/09/2017</small></h4>
                <v-spacer></v-spacer>
                <v-text-field append-icon="search" label="Buscar" single-line hide-details v-model="search"></v-text-field>
              </v-card-title>
              <v-data-table v-bind:headers="headers" :items="items"  class="elevation-2">
                <template slot="items" scope="props">
                  <td style="width: 15%;" class="text-xs-left ">
                    <v-chip>
                      <v-avatar class="primary">F</v-avatar>
                      {{ props.item.document }}
                    </v-chip>
                  </td>
                  <td>
                    <span>Neumaticos Del sur soc. Cosurmotriz - </span> 
                    <span class="grey--text" ellipsis>Imp D | Ref F-18974 | OT 4578</span>                    
                  </td>
                  <td style="width: 15%;">
                    <strong>S/. 1,133.88</strong>
                  </td>
                  <td>
                    0001
                  </td>
                  <td>
                    <v-btn icon dark class="blue darken-2" slot="activator" v-on:click="dialog=true">
                      <v-icon dark>description</v-icon>
                    </v-btn>
                  </td>
                </template>
                <template slot="footer">
                  <td colspan="100%">
                    <strong>Facturas</strong> 1256.00 | 
                    <strong>Boletas</strong> 1256.00 | 
                    <strong>Notas</strong> 1256.00 | 
                  </td>
                </template>
              </v-data-table>
            </v-card>
            <v-dialog v-model="dialog" width="600" persistent>
              <v-card>
              <v-card-title class="headline">F001-15789</v-card-title>
              <v-card-text>                        
                Let Google help apps determine location. This means sending anonymous location data to Google, even when no apps are running
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn class="green--text darken-1" flat="flat" @click.native="dialog = false">Disagree</v-btn>
                <v-btn class="green--text darken-1" flat="flat" @click.native="dialog = false">Agree</v-btn>
              </v-card-actions>
            </v-dialog>
            <v-dialog persistent v-model="modal" lazy full-width>
              <v-date-picker v-model="e3" scrollable >
                <template scope="{ save, cancel }">
                  <v-card-actions>
                    <v-btn flat primary @click.native="cancel()">Cancel</v-btn>
                    <v-btn flat primary @click.native="save()">Save</v-btn>
                  </v-card-actions>
                </template>
              </v-date-picker>
            </v-dialog>
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
      
                  drawer: true,            
      
                  mini: false,            
      
                  right: null,
      
                  modal: false,
      
                  e3: null,
      
                  items2: [
      
      
      
                      {heading: 'Principal'},
      
      
      
                      {icon: 'contacts', text: 'Documentos'},
      
      
      
                      {icon: 'history', text: 'Frequently contacted'},                    
      
      
      
                      {heading: 'Labels'},
      
      
      
                      {
      
      
      
                          icon: 'keyboard_arrow_up',
      
      
      
                          'icon-alt': 'keyboard_arrow_down',
      
      
      
                          text: 'Labels',
      
      
      
                          model: false,
      
      
      
                          children: [
      
      
      
                              {icon: 'add', text: 'Create label'}
      
      
      
                          ]
      
      
      
                      },
      
      
      
                      {
      
      
      
                          icon: 'keyboard_arrow_up',
      
      
      
                          'icon-alt': 'keyboard_arrow_down',
      
      
      
                          text: 'More',
      
      
      
                          model: false,
      
      
      
                          children: [
      
      
      
                              {text: 'Import'},
      
      
      
                              {text: 'Export'},
      
      
      
                              {text: 'Print'},
      
      
      
                              {text: 'Undo changes'},
      
      
      
                              {text: 'Other contacts'}
      
      
      
                          ]
      
      
      
                      },
      
      
      
                      {icon: 'settings', text: 'Settings'},
      
      
      
                      {icon: 'chat_bubble', text: 'Send feedback'},
      
      
      
                      {icon: 'help', text: 'Help'},                                
      
                  ],
      
                  search: '',
      
                  picker: null,
      
                  headers: [            
      
                      {text: 'Documento', align: 'left', value: 'document'},
      
                      {text: 'Descripcion', align: 'left', value: 'description'},
      
                      {text: 'Total', align: 'left', value: 'total'},
      
                      {text: 'Sunat', align: 'left', value: 'sunat'},
      
                      {text: 'Acciones', align: 'left', value: 'accion', sortable:false}
      
                  ],            
      
                  items: [            
      
                      { value: false, document: '13946'},
      
                      { value: false, document: '13946'},
      
                      { value: false, document: '13946'},
      
                      { value: false, document: '13946'},
      
                      { value: false, document: '13946'},
      
                      { value: false, document: '13946'},
      
                      { value: false, document: '13946'},
      
                      { value: false, document: '13946'},
      
                      { value: false, document: '13946'},
      
                      { value: false, document: '13946'},
      
                  ],
      
      
      
                  dialog: false
      
      
      
              }
      
      
      
          }
      
      
      
      })
      
      
      
    </script>
  </body>
</html>