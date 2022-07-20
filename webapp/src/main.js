import Vue from 'vue'
import App from './App.vue'
import router from './router'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faBars, faUsers, faChalkboardTeacher, faLayerGroup, faEdit, faTrash} from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import axios from 'axios'
import qs from 'qs'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'vue-select/dist/vue-select.css'
import './assets/scss/main.scss'

library.add(faBars, faUsers, faChalkboardTeacher, faLayerGroup, faEdit, faTrash)

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)

Vue.component('font-awesome-icon', FontAwesomeIcon)

Vue.config.productionTip = false

axios.interceptors.request.use(function(config) {
    if (config.data) {
        config.data = qs.stringify(config.data)
    }
    return config
}, function(error) {
    return Promise.reject(error)
})

axios.interceptors.response.use(function(response) {
    return response;
}, function(error) {
    return Promise.reject(error)
})

new Vue({
    router,
    render: h => h(App)
}).$mount('#app')