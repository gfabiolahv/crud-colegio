<template>
    <div id="grupos-list">
      <b-breadcrumb :items="breadcrumbItems"></b-breadcrumb>
      <div class="navigation">
        <h5>Grupos</h5>
        <div>
            <b-button to="/grupo/new">Añadir nuevo grupo</b-button>
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
        <span>¿Desea eliminar el grupo {{this.deleteName}}?</span>
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
                {key:'grado', label:'Grado'},
                {key:'grupo', label:'Grupo'},
                {key:'profesor', label:'Profesor'},
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
                    text: 'Grupos',
                    active: true
                }
            ]
        }
    },
    methods: {
        editItem(item) {
            this.$router.push(`/grupo/edit/${item.id}`).catch(() => {})
        },
        showDeleteModal(item) {
            this.itemToDelete = item.id
            this.deleteName = item.name
            this.modalShow = true
        },
        handleOk(bvModalEvent) {
            bvModalEvent.preventDefault()
            this.deleteItem()
        },
        deleteItem()
        {
            axios
                .post(`http://localhost:8000/delete_grupo/${this.itemToDelete}`)
                .then(response => {
                    window.location.reload()
                })
        }
    },
    created() {
        axios
            .get(`http://localhost:8000/gradogrupo`)
            .then(response => {
                if(response.status === 200) {
                    this.items = response.data
                }
            })
    }
}
</script>