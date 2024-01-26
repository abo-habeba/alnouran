<template>
    <!-- <v-btn @click="store.getAbsences()">getAbsences</v-btn> -->
    <div>
        <AddVRestallowanceComponent />
        <div class="div-balance text-center">
            <div class="box-balance">
                <router-link to="/rest">
                    <div class="item-balance"> البدلات </div>
                    <div class="item-balance">{{ store.rest }}</div>
                </router-link>
            </div>
        </div>
        <hr style="margin: auto" />
        <router-view></router-view>
    </div>
    <div style="white-space: nowrap; overflow: auto; width: 100%">
        <table v-if="store.restallowance" class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th> الوصف </th>
                    <th> التاريخ </th>
                    <th> الحالة </th>
                    <th> تاريخ الانشاء </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(absence, index) in store.restallowance">
                    <th>{{ index + 1 }}</th>
                    <td>{{ absence.description }}</td>
                    <td>{{ absence.date }}</td>
                    <td :class="absence.state == 1 ? 'bg-success' : 'bg-danger'">
                        {{ absence.state == 1 ? 'متاح' : 'تم التبديل' }}
                    </td>
                    <td>{{ store.formatDate(absence.created_at) }}</td>
                </tr>
            </tbody>
        </table>
        <div v-else class="text-center">{{ $t("noData") }}</div>
    </div>
</template>
<script setup>
import { usemainStore } from "../../store/mainStore";
const store = usemainStore();
import AddVRestallowanceComponent from "../../components/Vacations/AddRestallowanceComponent.vue";
import { onMounted, ref } from "vue";

onMounted(() => {
    store.getAbsences();
});
</script>
<style lang="scss">
.box-fixed {
    position: sticky !important;
    top: 73px;
    z-index: 9999;
    width: 100%;
    height: 100%;
}

.bg-success {
    background-color: darkgreen !important;
}

.box-fixed * {
    position: unset !important;
}

html {
    overflow-y: auto !important;
}
</style>
