<template>
    <v-row justify="center">
        <v-dialog v-model="dialog">
            <template v-slot:activator="{ props }">
                <v-btn color="success" @click="setField()" v-bind="props"> طلب اجازة جديد </v-btn>
            </template>
            <v-card>
                <v-card-title>
                    <span class="text-h5"> طلب اجازة جديد </span>
                </v-card-title>
                <v-card-text>
                    <v-form class="my-3">
                        <v-select label="نوع الاجازة" :items="typeRequest" item-title="ar" item-value="en"
                            v-model="addRequest.Type" variant="outlined" :rules="[(v) => !!v || 'هذا الحقل مطلوب ']">
                        </v-select>
                        <v-textarea label="الوصف" v-model="addRequest.description" variant="outlined" :rows="1" auto-grow
                            :rules="[(v) => !!v || 'هذا الحقل مطلوب ']">
                        </v-textarea>
                        <div v-if="addRequest.Type == 'Rest allowance'">
                            <v-select label="البدلات" :items="store.restallowance" item-title="description" item-value="id"
                                v-model="addRequest.rest_id" variant="outlined" :rules="[(v) => !!v || 'هذا الحقل مطلوب ']">
                            </v-select>
                        </div>
                        <div v-else>
                            <v-text-field label="بداية الاجازه" type="date" v-model="addRequest.start_date"
                                variant="outlined"></v-text-field>
                            <v-text-field label="نهاية الاجازة" type="date" v-model="addRequest.end_date"
                                variant="outlined"></v-text-field>
                        </div>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue-darken-1" variant="text" @click="dialog = false">
                        اغلاق
                    </v-btn>
                    <v-btn color="blue-darken-1" variant="text" @click="saveRequest">
                        حفظ
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
import { ref } from "vue";
const dialog = ref(false);
const addRequest = ref({});
function saveRequest() {
    console.log(addRequest.value);
    axios
        .post(`absence`, addRequest.value)
        .then((res) => {
            console.log(res);
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
}

const typeRequest = ref([
    { "en": "Regular", "ar": "اعتيادية" },
    { "en": "Rest allowance", "ar": " بدل راحة " },
    { "en": "Casual", "ar": "عارضة" },
    { "en": "Umrah leave", "ar": "إجازة عمرة" },
    { "en": "Recruitment", "ar": "تجنيد" },
    { "en": "Maternity leave", "ar": "إجازة وضع" },
    { "en": "Compensatory leave", "ar": "إجازة تعويضية" },
    { "en": "Other", "ar": "أخرى" }
]
);

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
<style lang="scss">
.v-messages__message {
    padding: 13px;
    padding-top: 3px;
    color: red;
}

.non-clickable {
    pointer-events: none !important;
    cursor: not-allowed !important;
}
</style>
