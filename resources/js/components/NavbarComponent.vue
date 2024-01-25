<template>
    <v-app-bar :elevation="2">
        <template v-slot:append>
            <v-btn icon="mdi-home" to="/"></v-btn>
        </template>
        <v-btn color="red" icon="mdi-login" @click="toLogout">
        </v-btn>
    </v-app-bar>
</template>
<script setup>
import { usemainStore } from "@/store/mainStore";
import axios from "axios";
const store = usemainStore(); function toLogout() {
    axios
        .post(`logout`)
        .then(() => {
            localStorage.removeItem("token");
            localStorage.removeItem("user");
            store.setAuthHeaderNew();
            store.startSnack("success", "login", "success", false, 200);
            store.auth = false;
        })
        .catch(() => {
            store.startSnack("error", "no", "danger");
            myButton.disabled = false;
            myButton.style.cursor = "default";
        });
}
</script>
<style>
.v-app-bar {
    position: fixed !important;
    top: 0;
    z-index: 999;
}
</style>
