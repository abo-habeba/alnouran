<template>
 <v-speed-dial location="top right" transition="slide-y-reverse-transition">
  <template v-slot:activator="{ props: activatorProps }">
   <v-fab v-bind="activatorProps" size="large" icon="$vuetify"></v-fab>
  </template>

  <v-btn key="1" icon="$success"></v-btn>
  <v-btn key="2" icon="$info"></v-btn>
  <v-btn key="3" icon="$warning"></v-btn>
  <v-btn key="4" icon="$error"></v-btn>
 </v-speed-dial>
</template>
