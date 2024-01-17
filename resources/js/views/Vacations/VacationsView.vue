<template>
    <div>
        <AddVacationComponent />
        <div class="div-balance text-center">
            <div class="box-balance">
                <div class="item-balance">Regular</div>
                <div class="item-balance">{{ store.regular }}</div>
            </div>
            <div class="box-balance">
                <router-link to="/rest">
                    <div class="item-balance">Rest</div>
                    <div class="item-balance">{{ store.rest }}</div>
                </router-link>
            </div>
        </div>
        <hr style="margin: auto" />
        <router-view></router-view>
    </div>
    <div style="white-space: nowrap; overflow: auto; width: 100%">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>date</th>
                    <th>description</th>
                    <th>created_at</th>
                </tr>
            </thead>
            <tbody v-if="store.absences">
                <tr v-for="(absence, index) in store.absences">
                    <th>{{ index + 1 }}</th>
                    <td>{{ absence.Type }}</td>
                    <td>{{ absence.date }}</td>
                    <td>{{ absence.description }}</td>
                    <td>{{ store.formatDate(absence.created_at) }}</td>
                </tr>
            </tbody>
        </table>
        <div class="text-center">{{ $t("noData") }}</div>
    </div>
</template>
<script setup>
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
    display: flex;
    justify-content: space-around;
    align-items: center;
    letter-spacing: 2px;

    a {
        text-decoration: none;
        color: white;
    }
}

.box-balance {
    border-radius: 8px;
    background-color: #0d6efd;
    width: 23%;
    color: white;
}

@media (max-width: 700px) {
    .box-balance {
        width: 40%;
    }
}

html {
    overflow-y: auto !important;
}
</style>
