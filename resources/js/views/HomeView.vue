<template>
    <h1 class="text-center">Welcome</h1>
</template>
<script setup>
import { usemainStore } from "@/store/mainStore";
const store = usemainStore();
import { onMounted, ref } from "vue";
// import axios from "axios";
const dialog = ref();
onMounted(() => {
    store.getUser();
});
</script>
<style>
/* .box-content {
    position: relative;
    min-height: 73vh;
} */
/* .btn-add-fixed {
    position: fixed !important;
    bottom: 2%;
    right: 0.5%;
    z-index: 1000;
} */
</style>
