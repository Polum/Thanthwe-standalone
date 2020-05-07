import Vue from 'vue';
import VueRouter from 'vue-router';
import store from './store';
import Vue2Filters from 'vue2-filters';
import Notifications from 'vue-notification';
import App from './components/App';
import LineChart from './components/charts/LineChart';
import BarChart from './components/charts/BarChart';
import routes from './routes';
import FullCalendar from "vue-full-calendar";
import "fullcalendar/dist/fullcalendar.min.css";
import DataTable from 'vue2-datatable-component';
import NoSidebar from './components/Layout/NoSidebar';
import JustNavBar from './components/Layout/JustNavBar';
import Default from './components/Layout/Default';
import AccessError from './components/partials/AccessError';
import JsonExcel from 'vue-json-excel';

import vueFullCalendar from 'vue-full-calendar';

require('./bootstrap');
var numeral = require("numeral");

window.Vue = Vue;

Vue.use(VueRouter);
Vue.use(Notifications);
Vue.use(FullCalendar);
Vue.use(DataTable);
Vue.use(Vue2Filters);

Vue.filter("formatNumber", function (value) {
    return numeral(value).format("MWK0,0.00");
  });

Vue.component('default-layout', Default);
Vue.component('just-nav-bar-layout', JustNavBar);
Vue.component('no-sidebar-layout', NoSidebar);
Vue.component('line-chart', LineChart);
Vue.component('bar-chart', BarChart);
Vue.component('access-error',AccessError);
Vue.component('downloadExcel', JsonExcel);

const router = new VueRouter({mode:'history', routes});

router.beforeEach((to, from, next) => {
    const requireAuth = to.matched.some(record => record.meta.requireAuth);
    if(requireAuth) {
        if(checkToken()){
            next();
        }else{
            next('login');
        }
     } else {
       next();
     }
})

function checkToken(){
    if(localStorage.getItem('user-token')){
        return true;
    }
    return false;
}

Vue.filter('pluck', function(objects, key){
    return objects.map(function(object) {
        return object[key];
    })
});

const app = new Vue({
    router,
    store,
    el: '#app',
    render: f => f(App)
});
