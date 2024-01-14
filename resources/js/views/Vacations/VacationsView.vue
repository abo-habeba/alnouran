<template>
    <AddVacationComponent />
    <v-row class="text-center">
        <v-col>
            <div class="m-1 p-1">Regular</div>
            <div class="m-1 p-1">{{ regular }}</div>
        </v-col>
        <v-col>
            <div class="m-1 p-1">Rest</div>
            <div class="m-1 p-1">{{ rest }}</div>
        </v-col>
    </v-row>
    <hr style="margin: auto; width: 80%;">
    <div style="white-space: nowrap; overflow: auto;  width: 100%;">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>date</th>
                    <th>description</th>
                    <th>location</th>
                    <th>created_at</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(absence, index) in absences">
                    <th>{{ index + 1 }}</th>
                    <td>{{ absence.Type }}</td>
                    <td>{{ absence.date }}</td>
                    <td>{{ absence.description }}</td>
                    <td>{{ absence.location }}</td>
                    <td>{{ store.formatDate(absence.created_at) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script setup>
import { usemainStore } from "../../store/mainStore";
const store = usemainStore();
import AddVacationComponent from "../../components/Vacations/AddVacationComponent.vue";
import { onMounted, ref } from "vue";
import axios from "axios";
const absences = ref("");
const regular = ref("");
const rest = ref("");
onMounted(() => {
    axios.get(`absence`).then((res) => {
        absences.value = res.data;
        console.log(absences.value);
    });
    axios.get(`regular`).then((res) => {
        regular.value = res.data.balance;
    });
    axios.get(`rest`).then((res) => {
        rest.value = res.data.balance;
    });
});
// const formatDate = (date) => {
//   return moment(date).format('YYYY-MM-DD');
// };
</script>
<style>
html {
    overflow-y: auto !important;
}
</style>
