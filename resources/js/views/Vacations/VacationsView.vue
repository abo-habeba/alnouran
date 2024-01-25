<template>
    <div>
        <AddVacationComponent />
        <div class="div-balance text-center">
            <div class="d-f-r box-balance">
                <div>
                    <div class="item-balance"> الاعتيادية </div>
                    <div class="item-balance">{{ store.regular }}</div>
                </div>
                <addRegularComponent />
            </div>
            <div class="box-balance">
                <router-link to="/rest">
                    <div class="item-balance"> بدل راحة </div>
                    <div class="item-balance">{{ store.rest }}</div>
                </router-link>
            </div>
        </div>
        <hr style="margin: auto" />
        <router-view></router-view>
    </div>
    <div style="white-space: nowrap; overflow: auto; width: 100%">
        <table v-if="store.absences" class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th> نوع الاجازة </th>
                    <th>تاريخ الاجازة</th>
                    <th>الوصف</th>
                    <th>تاريخ الانشاء</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(absence, index) in store.absences">
                    <th>{{ index + 1 }}</th>
                    <td>{{ $t(absence.Type) }}</td>
                    <td>{{ absence.date }}</td>
                    <td>{{ absence.description }}</td>
                    <td>{{ store.formatDate(absence.created_at) }}</td>
                </tr>
            </tbody>
        </table>
        <div v-else class="text-center">{{ $t("noData") }}</div>
    </div>
</template>
<script setup>
import addRegularComponent from "../../components/Vacations/addRegularComponent.vue";
import { usemainStore } from "../../store/mainStore";
const store = usemainStore();
import AddVacationComponent from "../../components/Vacations/AddVacationComponent.vue";
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

.box-fixed * {
    position: unset !important;
}

.div-balance {
    padding: 10px 3px;
    margin-top: 10px;
    letter-spacing: 2px;

    a {
        text-decoration: none;
        color: white;
    }
}

.box-balance {
    border-radius: 8px;
    background-color: #0d6efd;
    width: auto;
    color: white;
    padding: 2px 10px;
}

// @media (max-width: 700px) {
//     .box-balance {
//         width: 40%;
//     }
// }

html {
    overflow-y: auto !important;
}
</style>
