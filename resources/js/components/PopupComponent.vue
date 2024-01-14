<template>
    <div class="popup">
        <div></div>
        <slot />
    </div>
</template>
<script setup>
function plog() {
    console.log("plog");
}
// import { usemainStore } from "./store/mainStore";
// const store = usemainStore();
</script>
