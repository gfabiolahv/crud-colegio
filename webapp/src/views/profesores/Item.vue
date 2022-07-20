<template>
    <div id="profesores-item">
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
            </b-row>
        </div>
        <div class="form-actions mt-4">
            <b-button v-if="isNew" @click="formSubmitted">Guardar profesor</b-button>
            <b-button v-if="!isNew" @click="formEdited">Actualizar profesor</b-button>
            <b-button @click="back" variant="outline-secondary">Regresar</b-button>
        </div>
     </div>
</template>

<script>
import vSelect from 'vue-select'
import axios from 'axios'

export default {
    name: 'Profesor',
    components: {
        vSelect
    },
     data() {
        return {
            isNew: true,
            name: '',
            lastname: '',
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
                        text: 'Profesores',
                        href: '/profesores'
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
                        text: 'Profesores',
                        href: '/profesores'
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
                gender: this.gender
            }
            axios
                .post('http://localhost:8000/profesor', obj)
                .then(response => {
                    this.$router.push('/profesores').catch(() => {})
                })
        },
        formEdited() {
            const obj = {
                name: this.name,
                lastname: this.lastname,
                gender: this.gender
            }
            axios
                .post(`http://localhost:8000/update_profesor/${this.$route.params.id}`, obj)
                .then(response => {
                    this.$router.push('/profesores').catch(() => {})
                })
        },
        back() {
            this.$router.push('/profesores').catch(() => {})
        },
        fetchProfesor(id) {
            axios
                .get(`http://localhost:8000/profesor/${id}`)
                .then(response => {
                    if(response.status === 200) {
                        this.name = response.data.name
                        this.lastname = response.data.lastname,
                        this.gender = response.data.gender
                    }
                })
        }
    },
    created() {
        if(this.$route.params.id) {
            this.isNew = false
            this.fetchProfesor(this.$route.params.id)
        }
    }
}
</script>
