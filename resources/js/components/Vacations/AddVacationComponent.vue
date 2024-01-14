<template>
    <v-row justify="center">
        <v-dialog v-model="dialog" persistent width="1024">
            <template v-slot:activator="{ props }">
                <v-btn color="primary" v-bind="props"> Add Request </v-btn>
            </template>
            <v-card>
                <v-card-title>
                    <span class="text-h5">Add Request</span>
                </v-card-title>
                <v-card-text>
                    <v-form class="my-3">
                        <v-textarea variant="outlined" :rows="1" auto-grow
                            :rules="[(v) => !!v || 'This field is required']"></v-textarea>
                        <v-select :label="$t('SelectStation')" :items="['stations', 'stations2']"
                            :rules="[(v) => !!v || 'This field is required']"></v-select>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue-darken-1" variant="text" @click="dialog = false">
                        Close
                    </v-btn>
                    <v-btn color="blue-darken-1" variant="text" @click="dialog = false">
                        Save
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>
<script setup>
import { ref } from "vue";
const dialog = ref(false);
</script>
