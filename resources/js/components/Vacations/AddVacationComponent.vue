<template>
    <v-row justify="center">
        <v-dialog v-model="dialog">
            <template v-slot:activator="{ props }">
                <v-btn color="success" @click="setField()" v-bind="props"> Add Request </v-btn>
            </template>
            <v-card>
                <v-card-title>
                    <span class="text-h5">Add Request</span>
                </v-card-title>
                <v-card-text>
                    <v-form class="my-3">
                        <v-select label="type Request" :items="typeRequest" v-model="addRequest.type" variant="outlined"
                            :rules="[(v) => !!v || 'This field is required']">
                        </v-select>
                        <v-textarea label="description" v-model="addRequest.description" variant="outlined" :rows="1"
                            auto-grow :rules="[(v) => !!v || 'This field is required']">
                        </v-textarea>
                        <v-text-field label="Date Start" type="date" v-model="addRequest.start_date"
                            variant="outlined"></v-text-field>
                        <v-text-field label="Date End" type="date" v-model="addRequest.end_date"
                            variant="outlined"></v-text-field>
                        <v-text-field label="location" variant="outlined" v-model="addRequest.location"
                            :rules="[(v) => !!v || 'This field is required']">
                        </v-text-field>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue-darken-1" variant="text" @click="dialog = false">
                        Close
                    </v-btn>
                    <v-btn color="blue-darken-1" variant="text" @click="saveRequest">
                        Save
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>
<script setup>
import axios from "axios";
import { usemainStore } from "../../store/mainStore";
const store = usemainStore();
import { ref, onMounted } from "vue";
const dialog = ref(false);
const addRequest = ref({});

onMounted(() => {
    console.log('tttttttt');
});
function saveRequest() {
    axios
        .post(`absence`, addRequest.value)
        .then((res) => {
            store.getAbsences();
            addRequest.value = ref({});
            dialog.value = false;
        })
        .catch((e) => {
            console.log(e);
        });
}
function setField() {
    addRequest.value.start_date = store.formatDate(new Date());
    addRequest.value.end_date = store.formatDate(new Date());
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            async (position) => {
                const { latitude, longitude } = position.coords;
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latitude}&lon=${longitude}`
                );
                const data = await response.json();
                // console.log(data);
                addRequest.value.location = `${data.address.state}, ${data.address.town}`;
            },
            (error) => {
                console.error("فشل في الحصول على الموقع:", error.message);
            }
        );
    } else {
        console.error("المتصفح الخاص بك لا يدعم Geolocation API");
    }
}
const typeRequest = ref([
    "Regular",
    "Rest allowance",
    "Casual",
    "Umrah leave",
    "Recruitment",
    "Maternity leave",
    "Compensatory leave",
    "Other",
]);
// const typeRequest = ref({
//     "Regular": "Regular",
//     "Rest allowance": "Rest allowance",
//     "Casual": "Casual",
//     "Umrah leave": "Umrah leave",
//     "Recruitment": "Recruitment",
//     "Maternity leave": "Maternity leave",
//     "Compensatory leave": "Compensatory leave",
//     "Other": "Other",
// });

// اعتيادية	Regular
//  بدل راحة	Rest allowance
//  عارضة	Casual
//  اجازة عمرة	Umrah leave
//  تجنيد	Recruitment
//  اجازة وضع	Maternity leave
//  اجازة تعويضية	Compensatory leave
//  اخري	Other
// const location = ref(null);

</script>
