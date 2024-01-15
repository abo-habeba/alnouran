<template>
    <v-app-bar :elevation="2">
        <template v-slot:append>
            <v-btn icon="mdi-home" to="/"></v-btn>
        </template>
    </v-app-bar>
</template>
<style>
.v-app-bar {
    position: fixed !important;
    top: 0;
    z-index: 999;
}
</style>
