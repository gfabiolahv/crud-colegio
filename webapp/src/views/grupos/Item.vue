<template>
    <div id="grupos-item">
        <b-breadcrumb :items="breadcrumbItems"></b-breadcrumb>
        <div>
            <b-row>
                <b-col lg="6">
                    <b-form-group
                        id="fieldset-name"
                        label="Grado"
                        label-for="grado"
                        class="mt-3">
                        <b-form-input id="grado" v-model="grado" type="text" placeholder="Introduzca el grado"></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col lg="6">
                    <b-form-group
                        id="fieldset-grupo"
                        label="Grupo"
                        label-for="grupo"
                        class="mt-3">
                        <b-form-input id="grupo" v-model="grupo" type="text" placeholder="Introduzca el grupo"></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col lg="6">
                    <b-form-group
                        id="fieldset-gender"
                        label="Profesor"
                        label-for="gender"
                        class="mt-3">
                        <v-select v-model="local_profesor" :options="profesores" name="profesor" label="fullname" />
                    </b-form-group>
                </b-col>
            </b-row>
        </div>
        <div class="form-actions mt-4">
            <b-button v-if="isNew" @click="formSubmitted">Guardar grupo</b-button>
            <b-button v-if="!isNew" @click="formEdited">Actualizar grupo</b-button>
            <b-button @click="back" variant="outline-secondary">Regresar</b-button>
        </div>
     </div>
</template>

<script>
import vSelect from 'vue-select'
import axios from 'axios'

export default {
    name: 'Grupo',
    components: {
        vSelect
    },
     data() {
        return {
            isNew: true,
            grado: '',
            grupo: '',
            profesor: '',
            profesores: []
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
                        text: 'Grupos',
                        href: '/grupos'
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
                        text: 'Grupos',
                        href: '/grupos'
                    },
                    {
                        text: 'Nuevo'
                    }
                ]
            }
        },
        local_profesor: {
            get() {
                return this.profesor
            },
            set(profesor) {
                this.profesor = profesor
            }
        }
    },
    methods: {
        formSubmitted() {
            const obj = {
                grado: this.grado,
                grupo: this.grupo,
                profesor_id: this.profesor.id
            }
            axios
                .post('http://localhost:8000/gradogrupo', obj)
                .then(response => {
                    this.$router.push('/grupos').catch(() => {})
                })
        },
        formEdited() {
            const obj = {
                grado: this.grado,
                grupo: this.grupo,
                profesor_id: this.profesor.id
            }
            axios
                .post(`http://localhost:8000/update_grupo/${this.$route.params.id}`, obj)
                .then(response => {
                    this.$router.push('/grupos').catch(() => {})
                })
        },
        back() {
            this.$router.push('/grupos').catch(() => {})
        },
        fetchGrupo(id) {
            axios
                .get(`http://localhost:8000/gradogrupo/${id}`)
                .then(response => {
                    if(response.status === 200) {
                        this.grado = response.data.grado
                        this.grupo = response.data.grupo,
                        this.profesor = response.data.profesor
                    }
                })
        }
    },
    created() {
        axios
            .get('http://localhost:8000/profesor')
            .then(response => {
                if(response.status === 200) {
                    this.profesores = response.data
                }
            })
        if(this.$route.params.id) {
            this.isNew = false
            this.fetchGrupo(this.$route.params.id)
        }
    }
}
</script>
