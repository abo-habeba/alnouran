<template>
    <v-row justify="center">
        <v-dialog v-model="dialog">
            <template v-slot:activator="{ props }">
                <v-btn v-bind="props"> Add Regular </v-btn>
            </template>
            <v-card>
                <v-card-title>
                    <span class="text-h5">Add Regular</span>
                </v-card-title>
                <v-card-text>
                    <v-form class="my-3">
                        <v-text-field type="number" label="balance" v-model="addRegular.balance" variant="outlined"
                            :rows="1" auto-grow :rules="[(v) => !!v || 'This field is required']">
                        </v-text-field>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue-darken-1" variant="text" @click="dialog = false">
                        Close
                    </v-btn>
                    <v-btn color="blue-darken-1" variant="text" @click="saveRegular">
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
import { ref } from "vue";
const dialog = ref(false);
const addRegular = ref({});

function saveRegular() {
    axios
        .post(`regular`, addRegular.value)
        .then((res) => {
            store.getAbsences();
            addRegular.value = ref({});
            dialog.value = false;
        })
        .catch((e) => {
            console.log(e);
        });
}

</script>
