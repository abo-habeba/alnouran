<template>
    <div>
        <!-- New Preparation -->
        <addPreparationComponent/>
        <!-- New Type Preparation -->
        <v-btn
            class="m-1"
            color="red"
            text
            @click="dialogAddTypePreparation = true" >اضافة نوع تحضيرة
        </v-btn>
        <v-dialog v-model="dialogAddTypePreparation">
            <v-card class="p-3">
                <v-card-title class="text-center m-4"> اضافة نوع تحضيرة </v-card-title>
                <v-text-field type="text" variant="outlined" label=" اسم نوع التحضيرة " v-model="newTypePreparation.name"
                :rules="[(v) => !!v || 'This field is required']">
                </v-text-field>
                <v-text-field type="text" variant="outlined" label="  " v-model="newTypePreparation.description"
                :rules="[(v) => !!v || 'This field is required']">
                </v-text-field>
                <v-select
                            label=" اختر المحطة "
                            :items="stations"
                            item-title="name"
                            item-value="id"
                            auto-select-first
                            v-model="newTypePreparation.station_id"
                        ></v-select>
                <div class="mx-auto">
                    <v-btn
                        class="mx-3"
                        color="green"
                        text
                        @click="addTypePreparation()"
                        >{{ $t("yes") }}</v-btn
                    >
                    <v-btn
                        class="mx-3"
                        color="red"
                        text
                        @click="dialogAddTypePreparation = false"
                        >{{ $t("no") }}</v-btn
                    >
                </div>
            </v-card>
        </v-dialog>
        <v-card v-for="(typePrep) in store.typePreparation" :key="typePrep.id" class="mx-auto m-4" width="100%" elevation="2">
            <v-card-title> {{typePrep.name}}</v-card-title>
            <v-card-subtitle> {{typePrep.description}} </v-card-subtitle>
    </v-card>
    </div>
</template>

<script setup>
import addPreparationComponent from "../../components/preparation/addPreparationComponent.vue"
import { usemainStore } from "../../store/mainStore";
const store = usemainStore();
import { onMounted, ref } from "vue";
import axios from "axios";
import moment from "moment";
moment.locale(localStorage.language + "-dz");
const newTypePreparation = ref({user_id:`${store.user.id}`});
const dialogAddTypePreparation = ref(false);
const stations = ref('');
onMounted(() => {
    console.log(newTypePreparation.value);
    axios
        .get(`Stations?current_user=${store.user.id}`)
        .then((res) => {
            stations.value = res.data;
            newTypePreparation.value.station_id = stations.value[0].id;
        })
        .catch(() => {
            store.startSnack("error", "no", "danger");
        });
        store.getTypePre();
});
function timeSinceReport(time) {
    return moment(time).fromNow();
}
function date(d) {
    return moment(d).format("dddd :- h:mm A - MM/DD ");
}
function addTypePreparation() {
    console.log(newTypePreparation.value);
    axios
        .post(`typePre`, newTypePreparation.value)
        .then(() => {
            newTypePreparation.value = "";
            dialogAddTypePreparation.value = false;

        })
        .catch(() => {
            store.startSnack("error", "no", "danger");
        });
    }
</script>

<style scoped></style>
