<template>
 <div class="box-content">
  <v-dialog min-width="70%" v-model="dialog" width="auto">
   <template v-slot:activator="{ props }">
    <v-btn class="btn-add-fixed" color="green" icon="mdi-plus" v-bind="props"> </v-btn>
   </template>
   <v-card>
    <v-card-text>
     <h1 class="h1 my-4 text-center">
      {{ $t('addReport') }}
     </h1>
     <v-form class="my-3">
      <v-textarea
       variant="outlined"
       :rows="1"
       auto-grow
       v-model="newReport.body"
       :label="$t('addReport')"
       :rules="[v => !!v || 'This field is required']"
      ></v-textarea>
      <v-select
       label=" اختر المحطة "
       :items="stations"
       item-title="name"
       item-value="id"
       auto-select-first
       v-model="newReport.station_id"
      ></v-select>
     </v-form>
     <v-btn color="green" @click="addReport" class="mt-2">{{ $t('addReport') }}</v-btn>
    </v-card-text>
    <v-btn color="primary" block @click="dialog = false">{{ $t('close') }}</v-btn>
   </v-card>
  </v-dialog>
  <ReportsComponent />
 </div>
</template>
<script setup>
import ReportsComponent from '@/components/ReportsComponent.vue';
import { usemainStore } from '@/store/mainStore';
const store = usemainStore();
import { onMounted, ref } from 'vue';
import axios from 'axios';
import moment from 'moment';
const stations = ref(['getData']);
// function handleEnterKey(event) {
//  if (event.key === 'Enter') {
//   // تنفيذ السلوك الذي تريده عند الضغط على زر Enter هنا
//   console.log('تم الضغط على زر Enter!');
//   event.preventDefault();
//   newReport.value.body += '\n=>  ';
//  }
// }
// function handlefocus() {
//  newReport.value.body = '=>  ';
// }
const newReport = ref({});
const dialog = ref();
function addReport() {
 const obReport = {
  body: newReport.value.body,
  station_id: newReport.value.station_id,
  user_id: store.user.id,
 };
 axios
  .post(`reports`, obReport)
  .then(() => {
   store.getReports();
   dialog.value = false;
   store.startSnack('success', 'no', 'success');
   newReport.value.body = '';
   newReport.value.station_id = '';
  })
  .catch(() => {
   store.startSnack('error', 'no', 'danger');
  });
}
onMounted(() => {
 store.getUser();
 axios
  .get(`Stations?current_user=${store.user.id}`)
  .then(res => {
   stations.value = res.data;
   newReport.value.station_id = stations.value[0].id;
  })
  .catch(() => {
   store.startSnack('error', 'no', 'danger');
  });
});
</script>
