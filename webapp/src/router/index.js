import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'

Vue.use(VueRouter)

const routes = [{
        path: '/',
        name: 'Home',
        component: Home
    },
    {
        path: '/alumnos',
        name: 'Alumnos',
        component: () => 
            import('../views/alumnos/List.vue')
    },
    {
        path: '/alumno/new',
        name: 'Alumno',
        component: () => 
            import('../views/alumnos/Item.vue')
    },
    {
        path: '/alumno/edit/:id',
        name: 'Alumno',
        component: () => 
            import('../views/alumnos/Item.vue')
    },
    {
        path: '/profesores',
        name: 'Profesores',
        component: () => 
            import('../views/profesores/List.vue')
    },
    {
        path: '/profesor/new',
        name: 'Profesor',
        component: () => 
            import('../views/profesores/Item.vue')
    },
    {
        path: '/profesor/edit/:id',
        name: 'Profesor',
        component: () => 
            import('../views/profesores/Item.vue')
    },
    {
        path: '/grupos',
        name: 'Grupos',
        component: () => 
            import('../views/grupos/List.vue')
    },
    {
        path: '/grupo/new',
        name: 'Grupo',
        component: () => 
            import('../views/grupos/Item.vue')
    },
    {
        path: '/grupo/edit/:id',
        name: 'Profesor',
        component: () => 
            import('../views/grupos/Item.vue')
    }
]

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
})

export default router