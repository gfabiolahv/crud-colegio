<template>
    <div id="alumnos-list">
      <b-breadcrumb :items="breadcrumbItems"></b-breadcrumb>
      <div class="navigation">
        <h5>Alumnos</h5>
        <div>
            <b-button to="/alumno/new">Añadir nuevo alumno</b-button>
            <b-button class="ml-15" variant="outline-secondary" to="/">Regresar</b-button>
        </div>
      </div>
      <hr>
      <div>
        <b-table striped hover :fields="fields" :items="items">
            <template v-slot:cell(actions)="{ item }">
                <span><b-btn @click="editItem(item)" title="Editar alumno"><font-awesome-icon icon="edit" /></b-btn></span>
                <span class="ml-10"><b-btn @click="showDeleteModal(item)" variant="danger" title="Eliminar alumno"><font-awesome-icon icon="trash" /></b-btn></span>
                <span class="ml-10"><b-btn @click="showAssignmentModal(item)" variant="primary" title="Asignar grupo"><font-awesome-icon icon="layer-group" /></b-btn></span>
            </template>
        </b-table>
      </div>
      <b-modal
        v-model="modalShow"
        id="modal-delete-item"
        ref="modal"
        title="Eliminar alumno"
        @ok="handleOk">
        <span>¿Desea eliminar a el alumno {{this.deleteName}}?</span>
      </b-modal>
      <b-modal
        v-model="modalAssignmentShow"
        id="modal-assignment-item"
        ref="modal"
        title="Asignar grupo"
        @ok="handleAssignmentOk">
        <p>Asignar el alumno {{this.selected.name}} {{this.selected.lastname}} al grupo:</p>
        <v-select v-model="local_grupo" :options="grupos" name="grupo" label="name" />
      </b-modal>
    </div>
</template>

<script>
import axios from 'axios'
import { BTable } from 'bootstrap-vue'
import vSelect from 'vue-select'

export default {
    name: 'Alumnos',
    components: {
        BTable,
        vSelect
    },
    data() {
        return {
            fields: [
                'id',
                {key:'name', label:'Nombre'},
                {key:'lastname', label:'Apellidos'},
                {key:'gender', label:'Género'},
                {key:'birthday', label:'Fecha de nacimiento'},
                {key:'grupo', label:'Grupo'},
                {key:'actions', label:'Acciones'}
            ],
            items: [],
            modalShow: false,
            modalAssignmentShow: false,
            itemToDelete: null,
            deleteName: '',
            selected: {
                id: null,
                name: '',
                lastname: ''
            },
            grupos: [],
            grupo: null,
            breadcrumbItems: [
                {
                    text: 'Inicio',
                    href: '/'
                },
                {
                    text: 'Alumnos',
                    active: true
                }
            ]
        }
    },
    computed: {
        local_grupo: {
            get() {

            },
            set(grupo) {
                this.grupo = grupo
            }
        }
    },
    methods: {
        editItem(item) {
            this.$router.push(`/alumno/edit/${item.id}`).catch(() => {})
        },
        assignmentItem() {
            const obj = {
                alumno_id: this.selected.id,
                grado_grupo_id: this.grupo.id
            }
            axios
                .post('http://localhost:8000/alumnogrado', obj)
                .then(response => {
                    if(response.status === 201) {
                        window.location.reload()
                    }
                })
        },
        showDeleteModal(item) {
            this.itemToDelete = item.id
            this.deleteName = item.name + ' ' + item.lastname
            this.modalShow = true
        },
        handleOk(bvModalEvent) {
            bvModalEvent.preventDefault()
            this.deleteItem()
        },
        handleAssignmentOk(bvModalEvent) {
            bvModalEvent.preventDefault()
            this.assignmentItem()
        },
        showAssignmentModal(item) {
            this.selected = item
            this.modalAssignmentShow = true
        },
        deleteItem()
        {
            axios
                .post(`http://localhost:8000/delete_alumno/${this.itemToDelete}`)
                .then(response => {
                    this.$router.push(`/alumnos`).catch(() => {})
                })
        }
    },
    created() {
        axios
            .get(`http://localhost:8000/alumno`)
            .then(response => {
                if(response.status === 200) {
                    this.items = response.data
                }
            })
        axios
            .get(`http://localhost:8000/gradogrupo`)
            .then(response => {
                if(response.status === 200) {
                    this.grupos = response.data
                }
            })
    }
}
</script>