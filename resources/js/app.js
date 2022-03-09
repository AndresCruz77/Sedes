/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import 'vue-select/dist/vue-select.css';    
window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//componente vue-select

import Vue from 'vue'; 
import vSelect from 'vue-select';
Vue.component('v-select', vSelect)
//Vue.component('v-select', VueSelect.VueSelect); 



Vue.component('admin-users', require('./components/Administrador/AdminUsersComponent.vue').default);
Vue.component('admin-roles', require('./components/Administrador/AdminRolesComponent.vue').default);

Vue.component('admin-informacion-usuario', require('./components/Administrador/InformacionUsarioComponent.vue').default);
Vue.component('admin-roles-usuario', require('./components/Administrador/RolesUsuarioComponent.vue').default);

Vue.component('menu-user-component', require('./components/App/UserMenuComponent.vue').default);


/* administrar sedes */
Vue.component('admin-sedes', require('./components/Administrativo/sedesComponent.vue').default); 
Vue.component('table-sedes', require('./components/Administrativo/tablasedesComponent.vue').default); 

/* administrar terceros */
Vue.component('admin-terceros', require('./components/Administrativo/tercerosComponent.vue').default); 
Vue.component('table-terceros', require('./components/Administrativo/tablatercerosComponent.vue').default); 

/* administrar periodos */
Vue.component('admin-periodos', require('./components/Administrativo/periodosComponent.vue').default); 

/* modulo obligaciones */
Vue.component('nueva-obligacion', require('./components/Obligaciones/nuevaObligacionComponent.vue').default); 
Vue.component('visor-obligaciones', require('./components/Obligaciones/visorObligacionesComponent.vue').default); 


/* anticipos */
Vue.component('anticipos-nuevo', require('./components/Anticipos/nuevoComponent.vue').default); 
Vue.component('anticipos-visormedico', require('./components/Anticipos/visormedicoComponent.vue').default);
Vue.component('anticipomed-formulario-visor', require('./components/Anticipos/visorformularioComponent.vue').default);

Vue.component('recobros-visor', require('./components/Recobros/visorComponent.vue').default);
Vue.component('recobros-formulario-visor', require('./components/Recobros/visorformularioComponent.vue').default);


Vue.component('contraloria-visor', require('./components/Contraloria/visorComponent.vue').default);
Vue.component('contraloria-formulario-visor', require('./components/Contraloria/visorformularioComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


const app = new Vue({
    el: '#app',
});
