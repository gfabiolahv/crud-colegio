<template>
  <div id="app">
    <header class="header-app">
      <b-button v-b-toggle.sidebar-menu>
        <font-awesome-icon icon="bars" size="2x" class="bars" />
      </b-button>
      <a href="#" title="Viaro Networks" class="logo">
        <img src="./assets/images/viaronet.webp" alt="Viaro Networks">
      </a>
    </header>
    <b-sidebar id="sidebar-menu" title="" shadow>
      <div class="px-3 py-2">
        <ul class="menu">
          <li>
            <router-link to="/" @click.stop.prevent>Inicio</router-link>
          </li>
          <li class="submenu">
            <span>Alumnos</span>
            <ul>
              <li><router-link to="/alumnos" @click.stop.prevent>Lista de alumnos</router-link></li>
              <li><router-link to="/alumno/new" @click.stop.prevent>Nuevo alumno</router-link></li>
            </ul>
          </li>
          <li class="submenu">
            <span>Profesores</span>
            <ul>
              <li><router-link to="/profesores" @click.stop.prevent>Lista de profesores</router-link></li>
              <li><router-link to="/profesor/new" @click.stop.prevent>Nuevo profesor</router-link></li>
            </ul>
          </li>
          <li class="submenu">
            <span>Grupos</span>
            <ul>
              <li><router-link to="/grupos" @click.stop.prevent>Lista de grupos</router-link></li>
              <li><router-link to="/grupo/new" @click.stop.prevent>Nuevo grupo</router-link></li>
            </ul>
          </li>
        </ul>
      </div>
    </b-sidebar>
    <b-container>
      <b-row>
        <b-col>
          <router-view :key="$route.fullPath" />
        </b-col>
      </b-row>
    </b-container>
    <footer class="mt-5">
      <p>© 2022 Viaro Networks Inc. - CRUD Colegio</p>
      <ul>
        <li>
          <a href="https://www.linkedin.com/in/gfabiolahv/" target="_blank">
            <b-icon icon="linkedin"></b-icon>
          </a>
        </li>
        <li>
          <p>Graciela Fabiola Herrera Velázquez</p>
        </li>
      </ul>
    </footer>
  </div>
</template>