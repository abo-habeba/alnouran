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
        <table class="table table-striped" v-if="store.absences">
            <thead>
                <tr>
                    <th>#</th>
                    <th> نوع الاجازة </th>
                    <th>تاريخ الاجازة</th>
                    <th>الوصف</th>
                    <th>تاريخ الانشاء</th>
                    <th> اجراءات</th>
                </tr>
            </thead>
            <tbody>
                <tr class="box-absence" v-for="(absence, index) in store.absences" @click="ActiveClass(absence)"
                    @contextmenu="optionsMenu(absence)" :id="'id-' + absence.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ $t(absence.Type) }}</td>
                    <td>{{ absence.date }}</td>
                    <td>{{ absence.description }}</td>
                    <td>{{ store.formatDate(absence.created_at) }}</td>
                    <td><i @click="optionsMenu(absence)" class="mdi mdi-dots-vertical dots-active"></i></td>
                </tr>
            </tbody>
        </table>

        <div v-else class="text-center">{{ $t("noData") }}</div>
        <div id="optionsMenu" class="d-f-c">
            <v-list>
                <v-list-item @click="editfun" prepend-icon="mdi-square-edit-outline">تعديل</v-list-item>
                <v-list-item @click="dialog = !dialog" prepend-icon="mdi-delete">حذف</v-list-item>
            </v-list>
        </div>

        <div>
            <v-row justify="center">
                <v-dialog v-model="dialog" width="auto">
                    <v-card>
                        <v-card-title>
                            هل تريد حذف {{ absenceDescription }}
                        </v-card-title>
                        <v-card-text>
                            <v-btn color="primary" class="ma-2" @click="dialog2 = true">
                                Open Dialog 2
                            </v-btn>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="primary" variant="text" @click="dialog = false">
                                Close
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                <v-dialog v-model="dialog2" width="auto">
                    <v-card>
                        <v-card-title>
                            Dialog 2
                        </v-card-title>
                        <v-card-text>
                            <v-btn color="primary">
                                Open Dialog 3
                            </v-btn>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="primary" variant="text" @click="dialog2 = false">
                                Close
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-row>
        </div>

    </div>
</template>
<script setup>
import addRegularComponent from "../../components/Vacations/addRegularComponent.vue";
import { usemainStore } from "../../store/mainStore";
const store = usemainStore();
import AddVacationComponent from "../../components/Vacations/AddVacationComponent.vue";
import { onMounted, ref } from "vue";
const absenceId = ref(0);
const absenceDescription = ref(0);
const dialog = ref(false)
const dialog2 = ref(false)
onMounted(() => {
    store.getAbsences();
});
function optionsMenu(absence) {
    event.preventDefault();
    const optionsMenuEl = document.getElementById('optionsMenu');
    const styleEl = ref({
        display: 'block',
        top: `${event.pageY + 10}px`,
        left: `${event.pageX >= 150 ? event.pageX - 100 : event.pageX + 30}px`,
    });

    absenceId.value = absence.id;
    absenceDescription.value = absence.description;

    Object.assign(optionsMenuEl.style, styleEl.value);

    document.addEventListener('click', function (event) {
        var optionsMenu = document.getElementById('optionsMenu');
        var target = event.target;

        if (!optionsMenu.contains(target) && optionsMenu.style.display === 'block') {
            optionsMenu.style.display = 'none';
        }
    });
    ActiveClass(absence);
}


// ActiveClass(absence);


function ActiveClass(absence) {

    absenceId.value = absence.id;
    absenceDescription.value = absence.description;
    // إضافة كلاس active إلى العنصر الهدف
    document.getElementById(`id-${absence.id}`).classList.add('active');

    // إزالة كلاس active من باقي العناصر
    Array.from(document.querySelectorAll(`.box-absence`)).forEach(element => {
        if (element !== document.getElementById(`id-${absence.id}`)) {
            element.classList.remove('active');
        }
    });
}
function deletefun() {
    console.log('deletefun');
    document.getElementById('optionsMenu').style.display = 'none';
}
function editfun() {
    console.log('editfun');
    document.getElementById('optionsMenu').style.display = 'none';
}
</script>
<style lang="scss">
.active {
    td {
        background-color: #0d6dfd52 !important;
        color: #06ce21 !important;
    }
}

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

.box-absence {
    td {
        font-weight: bold;
        font-size: 20px;

        .dots-active {
            text-align: center;
            display: block;
            font-weight: bold !important;
            font-size: 20px;
            background-color: transparent;
        }
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
