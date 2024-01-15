<template>
    <v-dialog min-width="70%" v-model="dialog" width="auto">
        <template v-slot:activator="{ props }">
            <v-btn class="fixed-b-r" color="green" icon="mdi-plus" v-bind="props">
            </v-btn>
        </template>
        <v-card>
            <v-card-text>
                <h1 class="h1 my-4 text-center">
                    {{ $t("addReport") }}
                </h1>
                <v-form class="my-3">
                    <v-textarea variant="outlined" :rows="1" auto-grow v-model="newReport.body" :label="$t('addReport')"
                        :rules="[(v) => !!v || 'This field is required']"></v-textarea>
                    <v-select :label="$t('SelectPartment')" :rules="[(v) => !!v || 'This field is required']"
                        :items="stations" item-title="name" item-value="id" v-model="newReport.station_id"></v-select>
                </v-form>
                <v-btn color="green" location="center" @click="addReport" class="mt-2">{{ $t("addReport") }}</v-btn>
            </v-card-text>
            <v-btn color="primary" block @click="dialog = false">{{
                $t("close")
            }}</v-btn>
        </v-card>
    </v-dialog>
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
