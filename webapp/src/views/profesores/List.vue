<template>
    <div id="profesores-list">
      <b-breadcrumb :items="breadcrumbItems"></b-breadcrumb>
      <div class="navigation">
        <h5>Profesores</h5>
        <div>
            <b-button to="/profesor/new">Añadir nuevo profesor</b-button>
            <b-button class="ml-15" variant="outline-secondary" to="/">Regresar</b-button>
        </div>
      </div>
      <hr>
      <div>
        <b-table striped hover :fields="fields" :items="items">
            <template v-slot:cell(actions)="{ item }">
                <span><b-btn @click="editItem(item)"><font-awesome-icon icon="edit" /></b-btn></span>
                <span class="ml-10"><b-btn @click="showDeleteModal(item)" variant="danger"><font-awesome-icon icon="trash" /></b-btn></span>
            </template>
        </b-table>
      </div>
      <b-modal
        v-model="modalShow"
        id="modal-delete-item"
        ref="modal"
        title="Eliminar profesor"
        @ok="handleOk">
        <span>¿Desea eliminar el profesor {{this.deleteName}}?</span>
      </b-modal>
    </div>
</template>

<script>
import axios from 'axios'
import { BTable } from 'bootstrap-vue'

export default {
    name: 'Profesores',
    components: {
        BTable
    },
    data() {
        return {
            fields: [
                'id',
                {key:'name', label:'Nombre'},
                {key:'lastname', label:'Apellidos'},
                {key:'gender', label:'Género'},
                //{key:'status', label:'Estatus'},
                {key:'actions', label:'Acciones'}
            ],
            items: [],
            modalShow: false,
            itemToDelete: null,
            deleteName: '',
            breadcrumbItems: [
                {
                    text: 'Inicio',
                    href: '/'
                },
                {
                    text: 'Profesores',
                    active: true
                }
            ]
        }
    },
    methods: {
        editItem(item) {
            this.$router.push(`/profesor/edit/${item.id}`).catch(() => {})
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
        deleteItem()
        {
            axios
                .post(`http://localhost:8000/delete_profesor/${this.itemToDelete}`)
                .then(response => {
                    window.location.reload()
                })
        }
    },
    created() {
        axios
            .get(`http://localhost:8000/profesor`)
            .then(response => {
                if(response.status === 200) {
                    this.items = response.data
                }
            })
    }
}
</script>