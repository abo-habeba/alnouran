<template>
  <v-app-bar :elevation="2">
    <template v-slot:append>
      <v-btn prepend-icon="mdi-home" to="/">الرئيسية</v-btn>
    </template>
    <v-switch
      @change="toggleTheme"
      v-model="switchd"
      color="info"
      label="الوضع المظلم"
      hide-details
    ></v-switch>
  </v-app-bar>
</template>
<script setup>
// import { usemainStore } from '@/store/mainStore';
// const store = usemainStore();
import { useTheme } from 'vuetify';
const theme = useTheme();
import { ref } from 'vue';
console.log(localStorage.defaultTheme);
// const defaultThemeLocal = ref(
//   localStorage.defaultTheme ? JSON.parse(localStorage.defaultTheme) : false
// );
// console.log(defaultThemeLocal.value);
const switchd = ref(JSON.parse(localStorage.defaultTheme));
console.log(switchd.value);
function toggleTheme(){
  if (switchd.value) {
    theme.global.name.value = 'dark';
    localStorage.setItem('defaultTheme', true);
  } else {
    theme.global.name.value = 'light';
    localStorage.setItem('defaultTheme', false);
  }
}
</script>
<style>
.v-app-bar {
  position: fixed !important;
  top: 0;
  z-index: 999;
}
</style>
