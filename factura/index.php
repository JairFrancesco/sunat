<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title></title>
  <link rel="stylesheet" href="">
  <!-- Add this to <head> -->
  <link type="text/css" rel="stylesheet" href="//unpkg.com/bootstrap@next/dist/css/bootstrap.min.css"/>
  <link type="text/css" rel="stylesheet" href="//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.css"/>

  
    
  <!-- Add this after vue.js -->
  <script src="https://unpkg.com/vue@2.4.2/dist/vue.js"></script>
  <script src="//unpkg.com/babel-polyfill@latest/dist/polyfill.min.js"></script>
  <script src="//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.js"></script>
  
</head>
<body >
  <div id="app">
  <b-navbar type="dark" variant="primary" toggleable>
    <b-nav-toggle target="nav_dropdown_collapse"></b-nav-toggle>
    <b-collapse is-nav id="nav_dropdown_collapse">
    <b-container>
      <b-nav is-nav-bar>
        <b-nav-item href="#">SURMOTRIZ S.R.L</b-nav-item>
        <b-nav-item href="#">Link</b-nav-item>
        <!-- Navbar dropdowns -->
        <b-nav-item-dropdown text="Lang" right>
          <b-dropdown-item href="#">EN</b-dropdown-item>
          <b-dropdown-item href="#">ES</b-dropdown-item>
          <b-dropdown-item href="#">RU</b-dropdown-item>
          <b-dropdown-item href="#">FA</b-dropdown-item>
        </b-nav-item-dropdown>
        <b-nav-item-dropdown text="User" right>
          <b-dropdown-item href="#">Account</b-dropdown-item>
          <b-dropdown-item href="#">Settings</b-dropdown-item>
        </b-nav-item-dropdown>
      </b-nav>
      </b-container>
    </b-collapse>
  </b-navbar>
    <b-container>
      <br>
      <br>
      <b-row>
        <b-col>
          
            <template>
<div>
  <div class="my-1 row">
    <div class="col-md-6">
      <b-form-fieldset horizontal label="Rows per page" :label-cols="6">
        <b-form-select :options="pageOptions" v-model="perPage" />
      </b-form-fieldset>
    </div>
    <div class="col-md-6">
      <b-form-fieldset horizontal label="Filter" :label-cols="3">
        <b-form-input v-model="filter" placeholder="Type to Search" />
      </b-form-fieldset>
    </div>
  </div>

  <div class="row my-1">
    <div class="col-sm-8">
      <b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" />
    </div>
    <div class="col-sm-4 text-md-right">
      <b-button :disabled="!sortBy" @click="sortBy = null">Clear Sort</b-button>
    </div>
  </div>

  <!-- Main table element -->
  <b-table striped hover show-empty
           :items="items"
           :fields="fields"
           :current-page="currentPage"
           :per-page="perPage"
           :filter="filter"
           :sort-by.sync="sortBy"
           :sort-desc.sync="sortDesc"
           @filtered="onFiltered"
  >
    <template slot="name" scope="row">{{row.value.first}} {{row.value.last}}</template>
    <template slot="isActive" scope="row">{{row.value?'Yes :)':'No :('}}</template>
    <template slot="actions" scope="row">
      <!-- We use click.stop here to prevent a 'row-clicked' event from also happening -->
      <b-btn size="sm" @click.stop="details(row.item,row.index,$event.target)">Details</b-btn>
    </template>
  </b-table>

  <p>
    Sort By: {{ sortBy || 'n/a' }}, Direction: {{ sortDesc ? 'descending' : 'ascending' }}
  </p>

  <!-- Details modal -->
  <b-modal id="modal1" @hide="resetModal" ok-only>
    <h4 class="my-1 py-1" slot="modal-header">Index: {{ modalDetails.index }}</h4>
    <pre>{{ modalDetails.data }}</pre>
  </b-modal>

</div>
</template>
          
        </b-col>
      </b-row>
    </b-container>
  </div>
  
  <script>
  const items = [
  { isActive: true,  age: 40, name: { first: 'Dickerson', last: 'Macdonald' } },
  { isActive: false, age: 21, name: { first: 'Larsen', last: 'Shaw' } },
  { _rowVariant: 'success',
    isActive: false, age: 9,  name: { first: 'Mini', last: 'Navarro' } },
  { isActive: false, age: 89, name: { first: 'Geneva', last: 'Wilson' } },
  { isActive: true,  age: 38, name: { first: 'Jami', last: 'Carney' } },
  { isActive: false, age: 27, name: { first: 'Essie', last: 'Dunlap' } },
  { isActive: true,  age: 40, name: { first: 'Thor', last: 'Macdonald' } },
  { _cellVariants: { age: 'danger', isActive: 'warning' },
    isActive: true,  age: 87, name: { first: 'Larsen', last: 'Shaw' } },
  { isActive: false, age: 26, name: { first: 'Mitzi', last: 'Navarro' } },
  { isActive: false, age: 22, name: { first: 'Genevive', last: 'Wilson' } },
  { isActive: true,  age: 38, name: { first: 'John', last: 'Carney' } },
  { isActive: false, age: 29, name: { first: 'Dick', last: 'Dunlap' } }
];

var app = new Vue({
   el: '#app',
  data: {
    items: items,
    fields: {
      name:     { label: 'Person Full name', sortable: true },
      age:      { label: 'Person age', sortable: true, 'class': 'text-center'  },
      isActive: { label: 'is Active' },
      actions:  { label: 'Actions' }
    },
    currentPage: 1,
    perPage: 5,
    totalRows: items.length,
    pageOptions: [{text:5,value:5},{text:10,value:10},{text:15,value:15}],
    sortBy: null,
    sortDesc: false,
    filter: null,
    modalDetails: { index:'', data:'' }
  },
  methods: {
    details(item, index, button) {
      this.modalDetails.data = JSON.stringify(item, null, 2);
      this.modalDetails.index = index;
      this.$root.$emit('show::modal','modal1', button);
    },
    resetModal() {
      this.modalDetails.data = '';
      this.modalDetails.index = '';
    },
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    }
  }
})
  </script>
</body>
</html>