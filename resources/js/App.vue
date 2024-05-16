<template>
 <v-app>
  <v-layout class="rounded rounded-md">
   <SnackbarComponent />
   <NavbarComponent v-if="store.auth" />
   <NavigationComponent v-if="store.auth" />
   <v-main>
    <v-container>
     <router-view />
    </v-container>
   </v-main>
  </v-layout>
 </v-app>
</template>
<script setup>
let language = navigator.language.slice(0, 2);
localStorage.setItem('lang', language);
window.onlanguagechange = () => {
 language = navigator.language.slice(0, 2);
 localStorage.setItem('lang', navigator.language.slice(0, 2));
};
import 'moment/dist/locale/ar-dz';
import moment from 'moment';
moment.locale(`${localStorage.lang}-dz`);
import axios from 'axios';
import { useRouter } from 'vue-router';
const router = useRouter();
import { onMounted } from 'vue';
import NavbarComponent from './components/NavbarComponent.vue';
import NavigationComponent from './components/NavigationComponent.vue';
import SnackbarComponent from './components/SnackbarComponent.vue';
// import FooterComponent from "./components/FooterComponent.vue";
import { usemainStore } from './store/mainStore';
const store = usemainStore();
const BASE_URL = window.location.origin + '/api/';
// console.log(window.location.origin, '&&', BASE_URL);
axios.defaults.baseURL = BASE_URL;
axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.token}`;
onMounted(() => {
 axios.defaults.baseURL = BASE_URL;
 axios.defaults.withCredentials = true;
 store.setAuthHeaderNew(localStorage.token);
 // router.push({ path: "vacations" });
 store.getUser();
});
function eventOffset() {
 store.getUser();
 store.getReports();
}
window.addEventListener('beforeunload', function (event) {
 if (event.pageYOffset === 0) {
  event.preventDefault();
  event.returnValue = '';
  eventOffset();
 }
});
</script>
<style lang="scss">
html {
 overflow-y: auto !important;
}

body * {
 font-family: 'Noto Kufi Arabic', sans-serif !important;
 font-optical-sizing: auto;
}

.clickd {
 cursor: pointer !important;
}

td {
 user-select: none;
}

.active {
 td,
 th {
  background-color: #0d6dfd52 !important;
  color: #4fce06 !important;
 }
}

.box-fixed {
 position: sticky !important;
 top: 73px;
 z-index: 9999;
 width: 100%;
 height: 100%;

 * {
  position: unset !important;
 }
}

.div-balance {
 padding: 10px 3px;
 margin-top: 10px;
 display: flex;
 justify-content: space-around;
 align-items: center;
 letter-spacing: 2px;

 a {
  text-decoration: none;
  color: white;
 }
}

.box-balance-active {
 border-radius: 8px;
 background-color: white;
 color: #0d6efd;
}

.box-balance {
 border-radius: 8px;
 background-color: #0d6efd;
 color: white;
}

.box-absence {
 td {
  .dots-active {
   text-align: center;
   display: block;
   font-weight: bold !important;
   background-color: transparent;
  }
 }

 .bg-suc {
  background-color: darkgreen !important;
 }

 .bg-dang {
  background-color: #dc3545 !important;
 }

 .bg-warn {
  background-color: #5d5e55 !important;
 }
}

#optionsMenu {
 position: fixed;
 width: fit-content;
 border-radius: 6px;
 text-align: center;
 z-index: 9999;
 top: 0px;
 border: 1px solid gray;
 overflow: hidden;
 display: none;

 .v-list-item {
  border-bottom: 1px solid gray;

  &:last-child {
   width: 100%;
   border-bottom: none;
  }

  * {
   padding: 0px;
   margin: 0px;
   padding-inline-start: 6px;
   padding-inline-end: 6px;
  }
 }
}

.div-balance {
 padding: 10px 3px;
 margin-top: 10px;
 display: flex;
 justify-content: space-around;
 align-items: center;
 letter-spacing: 2px;

 a {
  text-decoration: none;
  color: white;
 }
}

%test {
 position: absolute;
 content: '';
 transition: all 0.5s;
}

.box-balance-white {
 border-radius: 8px;
 background-color: white;
 color: #0d6efd;

 * {
  color: #0d6efd;
 }
}

.box-balance-blue {
 border-radius: 8px;
 background-color: #0d6efd;
 color: white;
}

.popup {
 display: flex;
 background-color: rgba(122, 168, 30, 0.103);
 position: fixed;
 top: 0;
 left: 0;
 width: 100%;
 height: 100%;
 z-index: 9999;
 flex-direction: column;
 align-content: center;
 justify-content: center;
 align-items: center;
 flex-wrap: nowrap;
}
</style>
