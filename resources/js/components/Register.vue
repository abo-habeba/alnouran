<template>
    <v-app>
        <v-main>
            <v-container>
                <v-snackbar location="top" v-model="snackbar" multi-line>
                    {{ text }}
                    <template v-slot:actions>
                        <v-btn
                            color="red"
                            variant="text"
                            @click="snackbar = false"
                        >
                            Close
                        </v-btn>
                    </template>
                </v-snackbar>
                <v-form lazy-validation>
                    <v-text-field
                        variant="outlined"
                        label="name"
                        v-model="user.name"
                        :rules="[(v) => !!v || 'يجب تسجيل هذا الحقل']"
                    ></v-text-field>
                    <v-text-field
                        variant="outlined"
                        label="email"
                        v-model="user.email"
                    ></v-text-field>
                    <v-text-field
                        variant="outlined"
                        label="telephone"
                        v-model="user.phone"
                    ></v-text-field>
                    <v-text-field
                        type="password"
                        variant="outlined"
                        label="password"
                        v-model="user.password"
                    ></v-text-field>
                    <v-select
                        label="اختر المحطة"
                        :items="stations"
                        item-title="name"
                        item-value="id"
                        v-model="user.station_id"
                    ></v-select>
                    <v-select
                        label="اختر الوظيفة"
                        :items="Job_title"
                        item-title="name"
                        item-value="id"
                        v-model="user.Job_title"
                    ></v-select>
                </v-form>
                <v-btn @click="submit">تسجيل الان</v-btn>
            </v-container>
        </v-main>
    </v-app>
</template>

<script setup>
import { onMounted, ref } from "vue";
const stations = ref([]);
const snackbar = ref(false);
const text = "تم انشاء الحساب بنجاح";
 
const Job_title = ref([
    { id: "engeneer", name: "مهندس" },
    { id: "technician", name: "فني" },
]);
const user = ref({});
onMounted(() => {
    axios.get("/api/Stations").then((res) => {
        stations.value = res.data.stations;
    });
});
function submit() {
    axios.post("/api/register", user.value).then((res) => {
        snackbar.value = true;
        console.log(res.data);
        router.push({ path: '/                  
        success' })
    });
}
</script>