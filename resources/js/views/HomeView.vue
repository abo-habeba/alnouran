<template>
    <v-card class="mx-auto" width="100%" elevation="2">
        <routerLink to="/user/edit">
            <v-card-title>
                {{ store.user.name }}
            </v-card-title>
            <v-card-subtitle>
                {{ store.user.email }}
            </v-card-subtitle>
        </routerLink>
        <v-card-item>
            <router-link class="mx-2" :to="`/Stations/${station.id}`" v-for="(station, i) in store.user.stations"
                :key="i">{{ station.name }}
            </router-link>
        </v-card-item>
    </v-card>
    <v-row class="div-balance text-center">
        <v-col cols="12" sm="4">
            <router-link to="/vacations">
                <div class=" box-balance">
                    <div class="item-balance"> الاعتيادية </div>
                    <div class="item-balance">{{ store.regular }}</div>
                </div>
            </router-link>
        </v-col>
        <v-col cols="12" sm="4">
            <router-link to="/rest">
                <div class=" box-balance">
                    <div class="item-balance"> بدل راحة </div>
                    <div class="item-balance">{{ store.rest }}</div>
                </div>
            </router-link>
        </v-col>
        <v-col cols="12" sm="4">
            <router-link to="/reports">
                <div class=" box-balance">
                    <div class="item-balance"> التقارير </div>
                    <div class="item-balance">
                        {{ store.reports == 'getData' || store.reports == 'noData' ? 0 : store.reports.length }}
                    </div>
                </div>
            </router-link>
        </v-col>
    </v-row>
</template>
<script setup>
import { usemainStore } from "@/store/mainStore";
const store = usemainStore();
import { onMounted } from "vue";
onMounted(() => {
    store.getAbsences();
    store.getUser();
    store.getReports();
});
console.log(store.user);
</script>
<style scoped>
a {
    text-decoration: none;
}
</style>
