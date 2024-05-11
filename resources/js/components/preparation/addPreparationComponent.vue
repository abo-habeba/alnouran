<template>
    <div>
        <!-- New Preparation -->
        <v-btn
            class="m-1"
            color="green"
            text
            @click="dialogAddPreparation = true"
            >اضافة تحضيرة
        </v-btn>
        <v-dialog v-model="dialogAddPreparation">
            <v-card class="p-3">
                <v-container>
                    <v-card-title class="text-center m-4">
                        اضافة تحضيرة
                    </v-card-title>
                    <v-row>
                        <v-col cols="12">
                            <v-select
                                label=" نوع التحضيرة  "
                                :items="store.typePreparation"
                                item-title="name"
                                item-value="id"
                                return-object
                                @update:model-value="shangePrep"
                            >
                            </v-select>
                        </v-col>
                        <v-col cols="12">
                            <v-select
                                label=" اختر المحطة "
                                :items="stations"
                                item-title="name"
                                item-value="id"
                                auto-select-first
                                v-model="newPreparation.station_id"
                            >
                            </v-select>
                        </v-col>
                        <v-col cols="6">
                            <v-text-field
                                type="datetime-local"
                                variant="outlined"
                                @update:model-value="quantityFin"
                                label=" وقت التحضيرة "
                                v-model="newPreparation.actual_time"
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-text-field
                                type="number"
                                variant="outlined"
                                @update:model-value="quantityFin"
                                label=" ppm "
                                v-model="newPreparation.ppm"
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-text-field
                                type="number"
                                variant="outlined"
                                @update:model-value="quantityFin"
                                label=" عدد الساعات "
                                v-model="newPreparation.cont_hours"
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-text-field
                                type="number"
                                variant="outlined"
                                @update:model-value="quantityFin"
                                label=" طن الشرائح "
                                v-model="newPreparation.slices_ton"
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <div class="label_quantity">
                                <div>
                                    <h3>كيلو</h3>
                                </div>
                                <div>
                                    <h3>{{ newPreparation.quantity }}</h3>
                                </div>
                            </div>
                            <!-- <v-text-field
                                type="number"
                                variant="outlined"
                                label=" كيلو "
                                v-model="newPreparation.quantity"
                            >
                            </v-text-field> -->
                        </v-col>
                        <v-col cols="6">
                            <div class="label_quantity">
                                <div>
                                    <h3>سم</h3>
                                </div>
                                <div>
                                    <h3>{{ newPreparation.quantityGallon }}</h3>
                                </div>
                            </div>
                            <!-- <v-text-field
                                type="number"
                                variant="outlined"
                                label=" سم "
                                v-model="newPreparation.quantityGallon"
                            >
                            </v-text-field> -->
                        </v-col>
                        <v-col cols="12">
                            <v-text-field
                                type="text"
                                variant="outlined"
                                label=" ملاحظة "
                                v-model="newPreparation.note"
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <p>الوردية</p>
                            <v-radio-group inline v-model="timeOfDay">
                                <v-radio
                                    label=" الاولي "
                                    value="الاولي"
                                ></v-radio>
                                <v-radio
                                    label=" التانية "
                                    value="الثانية"
                                ></v-radio>
                            </v-radio-group>
                        </v-col>
                        <div class="mx-auto">
                            <v-btn
                                class="m-3"
                                color="green"
                                text
                                @click="addPreparation()"
                                >{{ $t("yes") }}</v-btn
                            >
                            <v-btn
                                class="m-3"
                                color="red"
                                text
                                @click="dialogAddPreparation = false"
                                >{{ $t("no") }}</v-btn
                            >
                        </div>
                    </v-row>
                </v-container>
            </v-card>
        </v-dialog>
    </div>
</template>

<script setup>
import { onMounted, ref, watchEffect } from "vue";
import { usemainStore } from "../../store/mainStore";
const store = usemainStore();
import axios from "axios";
import moment from "moment";
moment.locale(localStorage.language + "-dz");
const newPreparation = ref({});
const dialogAddPreparation = ref(false);
const stations = ref("");
const typePreparation = ref("");
const timeOfDay = ref("");
const quantityFin = ref("");

watchEffect(() => {
    if (
        !isNaN(parseFloat(newPreparation.value.ppm)) &&
        !isNaN(parseFloat(newPreparation.value.cont_hours)) &&
        !isNaN(parseFloat(newPreparation.value.slices_ton))
    ) {
        newPreparation.value.quantity = (
            (newPreparation.value.slices_ton *
                newPreparation.value.cont_hours *
                newPreparation.value.ppm) /
            1000
        ).toFixed(2);
        newPreparation.value.quantityGallon = (
            newPreparation.value.quantity / 14.8
        ).toFixed(2);
    }
});

onMounted(() => {
    console.log(newPreparation.value);
    axios
        .get(`Stations?current_user=${store.user.id}`)
        .then((res) => {
            stations.value = res.data;
            newPreparation.value.station_id = stations.value[0].id;
        })
        .catch(() => {
            store.startSnack("error", "no", "danger");
        });
    store.getTypePre();
    // إنشاء كائن Date للحصول على الوقت الحالي
    const currentTime = new Date();
    // الحصول على ساعة اليوم من الوقت الحالي (بتنسيق 24 ساعة)
    const currentHour = currentTime.getHours();
    // تحديد ما إذا كان الوقت صباحًا أم مساءً
    if (currentHour >= 8 && currentHour < 20) {
        timeOfDay.value = "الاولي";
    } else {
        timeOfDay.value = "الثانية";
    }
});
function timeSinceReport(time) {
    return moment(time).fromNow();
}
function date(d) {
    return moment(d).format("dddd :- h:mm A - MM/DD ");
}
function shangePrep(x) {
    typePreparation.value = x;
    if (x.latest_preparation_actual != null) {
        newPreparation.value = x.latest_preparation_actual;
    }
}
function addPreparation() {
    newPreparation.value.user_id = store.user.id;
    newPreparation.value.typePreparation_id = typePreparation.value.id;
    newPreparation.value.shift = timeOfDay.value;

    console.log(newPreparation.value);
    axios
        .post(`Pre`, newPreparation.value)
        .then(() => {
            newPreparation.value = "";
            dialogAddPreparation.value = false;
        })
        .catch(() => {
            store.startSnack("error", "no", "danger");
        });
}
</script>

<style lang="scss" scoped>
.label_quantity {
    display: flex;
    flex-direction: row-reverse;
    justify-content: space-evenly;
    align-items: center;
    h3 {
        width: 100%;
        text-align: center;
        display: inline-block;
        font-weight: bold;
        font-size: 23px;
    }
}
</style>
