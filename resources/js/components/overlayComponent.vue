<template>
 
</template>
<script setup>
import { usemainStore } from '@/store/mainStore';
const store = usemainStore();
</script>
<style>
/* .overlay-box */
.v-overlay-container {
  position: absolute;
  width: 100%;
  height: 100%;
  display: flex !important;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background-color: white;
}
</style>
