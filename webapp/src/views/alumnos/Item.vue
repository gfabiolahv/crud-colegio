<template>
    <div id="alumnos-item">
        <b-breadcrumb :items="breadcrumbItems"></b-breadcrumb>
        <div>
            <b-row>
                <b-col lg="6">
                    <b-form-group
                        id="fieldset-name"
                        label="Nombre"
                        label-for="name"
                        class="mt-3">
                        <b-form-input id="name" v-model="name" type="text" placeholder="Introduzca el nombre"></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col lg="6">
                    <b-form-group
                        id="fieldset-lastname"
                        label="Apellidos"
                        label-for="lastname"
                        class="mt-3">
                        <b-form-input id="lastname" v-model="lastname" type="text" placeholder="Introduzca los apellidos"></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col lg="6">
                    <b-form-group
                        id="fieldset-gender"
                        label="Género"
                        label-for="gender"
                        class="mt-3">
                        <v-select v-model="local_gender" :options="genders" name="gender" label="name" />
                    </b-form-group>
                </b-col>
                <b-col lg="6">
                    <b-form-group
                        id="fieldset-birthday"
                        label="Fecha de nacimiento"
                        label-for="birthday"
                        class="mt-3">
                        <form-date v-model="birthday"/>
                        <span v-if="!isNew"> - {{this.birthdayValue}}</span>
                    </b-form-group>
                </b-col>
            </b-row>
        </div>
        <div class="form-actions mt-4">
            <b-button v-if="isNew" @click="formSubmitted">Guardar alumno</b-button>
            <b-button v-if="!isNew" @click="formEdited">Actualizar alumno</b-button>
            <b-button @click="back" variant="outline-secondary">Regresar</b-button>
        </div>
     </div>
</template>

<script>
import vSelect from 'vue-select'
import FormDate from '../../templates/FormDate.vue'
import axios from 'axios'

export default {
    name: 'Alumno',
    components: {
        vSelect,
        FormDate
    },
     data() {
        return {
            isNew: true,
            name: '',
            lastname: '',
            birthday: null,
            birthdayValue: '',
            gender: '',
            genders: [
                {
                    "key": "F",
                    "name": "Femenino"
                },
                {
                    "key": "M",
                    "name": "Masculino"
                }
            ]
        }
    },
    computed: {
        breadcrumbItems() {
            if(this.$route.params.id) {
                return [
                    {
                        text: 'Inicio',
                        href: '/'
                    },
                    {
                        text: 'Alumnos',
                        href: '/alumnos'
                    },
                    {
                        text: 'Modificar'
                    }
                ]
            } else {
                return [
                    {
                        text: 'Inicio',
                        href: '/'
                    },
                    {
                        text: 'Alumnos',
                        href: '/alumnos'
                    },
                    {
                        text: 'Nuevo'
                    }
                ]
            }
        },
        local_gender: {
            get() {
                return this.gender
            },
            set(gender) {
                this.gender = gender.key
            }
        }
    },
    methods: {
        formSubmitted() {
            const obj = {
                name: this.name,
                lastname: this.lastname,
                gender: this.gender,
                birthday: this.birthday
            }
            axios
                .post('http://localhost:8000/alumno', obj)
                .then(response => {
                    this.$router.push('/alumnos').catch(() => {})
                })
        },
        formEdited() {
            const obj = {
                name: this.name,
                lastname: this.lastname,
                gender: this.gender,
                birthday: this.birthday
            }
            axios
                .post(`http://localhost:8000/update_alumno/${this.$route.params.id}`, obj)
                .then(response => {
                    this.$router.push('/alumnos').catch(() => {})
                })
        },
        back() {
            this.$router.push('/alumnos').catch(() => {})
        },
        fetchAlumno(id) {
            axios
                .get(`http://localhost:8000/alumno/${id}`)
                .then(response => {
                    if(response.status === 200) {
                        this.name = response.data.name
                        this.lastname = response.data.lastname,
                        this.birthdayValue = response.data.birthday
                        this.gender = response.data.gender
                    }
                })
        }
    },
    created() {
        if(this.$route.params.id) {
            this.isNew = false
            this.fetchAlumno(this.$route.params.id)
        }
    }
}
</script>
