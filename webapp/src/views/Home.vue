<template>
  <div id="home">
    <h2 class="text-center mt-5 mb-3">CRUD Colegio</h2>
    <br>
    <div class="nav-container">
      <router-link to="/alumnos" @click.stop.prevent>
        <font-awesome-icon icon="users" size="3x" />
        <span>Alumnos</span>
      </router-link>
      <router-link to="/profesores" @click.stop.prevent>
        <font-awesome-icon icon="chalkboard-teacher" size="3x" />
        <span>Profesores</span>
      </router-link>
      <router-link to="/grupos" @click.stop.prevent>
        <font-awesome-icon icon="layer-group" size="3x" />
        <span>Grupos</span>
      </router-link>
    </div>
  </div>
</template>

<script>
import '../assets/scss/home.scss'

export default {
  name: 'Home'
}
</script>
