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
            <div class="d-f-r box-balance">
                <div>
                    <div class="item-balance"> بدل راحة </div>
                    <div class="item-balance">{{ store.rest }}</div>
                </div>
                <router-link to="/rest">
                    <v-btn color="green" variant="text" icon="mdi-plus"></v-btn>
                </router-link>
            </div>
        </div>
        <hr style="margin: auto" />
        <router-view></router-view>
    </div>
    <div style="white-space: nowrap; overflow: auto; width: 100%">
        <table v-if="store.absences" class="table ">
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
                <tr class="alert alert-info" v-for="(absence, index) in store.absences" @contextmenu="optionsMenu(absence)"
                    :id="'id-' + absence.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ $t(absence.Type) }}</td>
                    <td>{{ absence.date }}</td>
                    <td>{{ absence.description }}</td>
                    <td>{{ store.formatDate(absence.created_at) }}</td>
                </tr>
            </tbody>
        </table>

        <div v-else class="text-center">{{ $t("noData") }}</div>
        <div id="optionsMenu" class="d-f-c">
            <v-list>
                <v-list-item prepend-icon="mdi-square-edit-outline">تعديل</v-list-item>
                <v-list-item prepend-icon="mdi-delete">تعديل</v-list-item>
            </v-list>

        </div>

    </div>
</template>
<script setup>
import addRegularComponent from "../../components/Vacations/addRegularComponent.vue";
import { usemainStore } from "../../store/mainStore";
const store = usemainStore();
import AddVacationComponent from "../../components/Vacations/AddVacationComponent.vue";
import { onMounted, ref } from "vue";
const optionsMenuTop = ref(0);
const optionsMenuRight = ref(0);
onMounted(() => {
    store.getAbsences();
});
function optionsMenu(absence) {
    event.preventDefault();
    const styleEl = ref({
        display: 'block',
        top: `${event.pageY}px`,
        left: `${event.pageX}px`,
    })
    Object.assign(document.getElementById('optionsMenu').style, styleEl.value);
    document.addEventListener('mousedown', () => {
        document.getElementById('optionsMenu').style.display = 'none';
    });

}
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

td {
    user-select: none;
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
    width: auto;
    color: white;
    padding: 2px 10px;

    .mdi-plus {
        font-size: 50px;
        font-weight: bold;
    }
}

#optionsMenu {
    position: fixed;
    width: fit-content;
    border-radius: 6px;
    text-align: center;
    z-index: 9999;
    top: 0px;
    border: 1px solid gray;
    overflow: hidden;
    display: none;

    .v-list-item {
        border-bottom: 1px solid gray;

        &:last-child {
            width: 100%;
            border-bottom: none;
        }

        * {
            padding: 0px;
            margin: 0px;
            padding-inline-start: 6px;
            padding-inline-end: 6px;
        }
    }
}


html {
    overflow-y: auto !important;
}
</style>
