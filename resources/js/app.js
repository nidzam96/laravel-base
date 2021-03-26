/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('jquery-ui')
require('datatables.net-bs4');
require('blueimp-file-upload');
window.moment 			= require('moment');
window.select2 			= require('select2');
window.slugify 			= require('slugify');
window.validators 	= require('vuelidate/lib/validators');
window.Vue 				  = require('vue');

/**
 * https://bootstrap-vue.js.org/docs/
 */
import BootstrapVue from 'bootstrap-vue';
Vue.use(BootstrapVue);

/**
 * Validation on frontend
 * https://vuelidate.js.org/#sub-installation
 */
import Vuelidate from 'vuelidate'; // validation form field
Vue.use(Vuelidate);

// Numbering format
import vueNumeralFilterInstaller from 'vue-numeral-filter';
Vue.use(vueNumeralFilterInstaller);

// Date picker
import VCalendar from 'v-calendar';
Vue.use(VCalendar);

import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
Vue.use(DatePicker);

import CKEditor from '@ckeditor/ckeditor5-vue';
Vue.use(CKEditor);
window.ClassicEditor = require('@ckeditor/ckeditor5-build-classic');

import Viewer from 'v-viewer';
Vue.use(Viewer);

import 'viewerjs/dist/viewer.css';

import VueMasonryGallery from 'vue-masonry-gallery/vendor/VueMasonryGallery/VueMasonryGallery'
Vue.component('vue-masonry-gallery', VueMasonryGallery);

import Swal from 'sweetalert2';

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Custom UI
Vue.component('nazrol-select2', require('./ui/Select2.vue').default);
Vue.component('select2', require('./ui/Select2.vue').default);
Vue.component('avatar-cropper', require('vue-avatar-cropper').default);
Vue.component('datepicker', require('./ui/DatePicker.vue').default);
Vue.component('monthpicker', require('./ui/MonthPicker.vue').default);
Vue.component('nazrol-checkbox', require('./ui/ButtonCheckBox.vue').default);
Vue.component('nazrol-mapview', require('./components/MapViewComponent.vue').default);

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('user-list-component', require('./components/UserListComponent.vue').default);
Vue.component('view-user', require('./components/ModalViewUser.vue').default);
Vue.component('audit-list-component', require('./components/AuditListComponent.vue').default);

// LeafLet
import { LMap, LTileLayer, LMarker, LPopup, LIcon, LTooltip } from 'vue2-leaflet';
Vue.component('l-map', LMap);
Vue.component('l-tile-layer', LTileLayer);
Vue.component('l-marker', LMarker);
Vue.component('l-popup', LPopup);
Vue.component('l-icon', LIcon);
Vue.component('l-tooltip', LTooltip);

import { Icon } from 'leaflet';
import 'leaflet/dist/leaflet.css';

// Issue with shadow, need to remove it
delete Icon.Default.prototype._getIconUrl;

Icon.Default.mergeOptions({
  iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
  iconUrl: require('leaflet/dist/images/marker-icon.png'),
  shadowUrl: require('leaflet/dist/images/marker-shadow.png')
});

//import axios from 'axios';

window.geolib = require('geolib');
window.validators = require('vuelidate/lib/validators');