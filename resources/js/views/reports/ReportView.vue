<template>
 <div>
  <h1 class="h1 mt-5 text-center">{{ $t('report') }} [ {{ router.params.id }} ]</h1>
  <!-- Delete Report -->
  <v-dialog v-model="dialogDlete">
   <v-card>
    <v-card-title class="text-center m-4">{{ $t('confirm') }}</v-card-title>
    <div class="mx-auto">
     <v-btn class="m-5" color="green" text @click="deleteReport()">{{ $t('yes') }}</v-btn>
     <v-btn class="m-5" color="red" text @click="dialogDlete = false">{{ $t('no') }}</v-btn>
    </div>
   </v-card>
  </v-dialog>
  <!-- Update Report -->
  <v-dialog min-width="70%" v-model="dialogUpdate" width="auto">
   <v-card>
    <v-card-text>
     <h1 class="h1 my-4 text-center">تعديل التقرير</h1>
     <v-form class="my-3">
      <v-textarea
       variant="outlined"
       :rows="1"
       auto-grow
       v-model="reportVar.body"
       @keydown.enter="handleEnterKey"
       @focus="handlefocus"
       :label="$t('addReport')"
       :rules="[v => !!v || 'This field is required']"
      ></v-textarea>
      <v-select
       :label="$t('SelectPartment')"
       :rules="[v => !!v || 'This field is required']"
       :items="store.user.stations"
       item-title="name"
       item-value="id"
       v-model="reportVar.station_id"
      ></v-select>
     </v-form>
     <v-btn color="green" @click="updateReport" class="mt-2"> حفظ </v-btn>
    </v-card-text>
    <v-btn color="primary" block @click="dialogUpdate = false">{{ $t('close') }}</v-btn>
   </v-card>
  </v-dialog>
  <div class="alert alert-info text-center" v-if="report == 'getData'">
   {{ $t('getData') }}
  </div>
  <div class="alert alert-warning text-center" v-else-if="report == 'noreport'">
   {{ $t('noReport') }}
  </div>
  <div v-else>
   <v-card color="grey-lighten-2 p-3">
    <div id="textShare">
     <v-card-title>
      <v-chip class="float-start">{{ report.user.name }}</v-chip>
      <v-chip class="float-end">{{ report.station.name }}</v-chip>
     </v-card-title>
     <v-card-text color="grey-lighten-4" class="my-2 text-content">{{ report.body }}</v-card-text>
     <v-chip class="m-1">{{ date(report.created_at) }}</v-chip>
     <v-chip class="m-1">{{ timeSinceReport(report.created_at) }}</v-chip>
    </div>
    <v-card-actions class="mt-2">
     <v-textarea
      variant="outlined"
      :rows="1"
      auto-grow
      :label="$t('comment')"
      v-model="report.comment"
     />
     <v-btn icon="mdi-send" @click="addComment(report)"></v-btn>
    </v-card-actions>
    <div class="comment-length float-right">
     <span class="mdi mdi-comment-outline"></span>
     <span class="m-1">{{ report.comments.length }}</span>
    </div>
    <div v-if="report.user.id == store.user.id" class="comment-length float-right mr-4">
     <span class="clickd mdi mdi-file-edit-outline" @click="update(report)"></span>
    </div>
    <div
     v-if="report.user.id == store.user.id || store.user.roles == 'admin'"
     class="comment-length float-right mx-4"
    >
     <span class="clickd mdi mdi-delete-outline" @click="deleted(report.id)"></span>
    </div>
    <v-card class="mb-3 p-3 w-100" v-for="(comment, i) in report.comments" :key="i">
     <v-chip>{{ comment.user.name }}</v-chip>
     <div class="text-content">>{{ comment.body }}</div>
     <span class="float-right">{{ timeSinceReport(comment.created_at) }}</span>
    </v-card>
   </v-card>
  </div>
 </div>
</template>
<script setup>
import { useRoute } from 'vue-router';
const router = useRoute();
import { usemainStore } from '../../store/mainStore';
const store = usemainStore();
import { onMounted, ref } from 'vue';
import axios from 'axios';
import moment from 'moment';
moment.locale('ar');
const report = ref('getData');
const reportVar = ref(' ');
const dialogDlete = ref(false);
const dialogUpdate = ref(false);
function getReport() {
 axios
  .get(`reports/${router.params.id}`)
  .then(res => {
   report.value = res.data;
  })
  .catch(() => {
   report.value = 'noreport';
  });
}
function handleEnterKey(event) {
 if (event.key === 'Enter') {
  // تنفيذ السلوك الذي تريده عند الضغط على زر Enter هنا
  console.log('تم الضغط على زر Enter!');
  event.preventDefault();
  reportVar.value.body += '\n=>  ';
 }
}
function handlefocus() {
 reportVar.value.body = '=>  ';
}
onMounted(() => {
 axios
  .get(`reports/${router.params.id}`)
  .then(res => {
   report.value = res.data;
  })
  .catch(() => {
   report.value = 'noreport';
  });
});
function update(report) {
 dialogUpdate.value = true;
 reportVar.value = report;
}
function updateReport() {
 axios
  .patch(`reports/${reportVar.value.id}`, reportVar.value)
  .then(() => {
   store.getReports();
   dialogUpdate.value = false;
   store.startSnack('success', 'no', 'success');
   reportVar.value = '';
  })
  .catch(() => {
   store.startSnack('error', 'no', 'danger');
  });
}
function timeSinceReport(time) {
 return moment(time).fromNow();
}
function date(d) {
 return moment(d).format('dddd :- h:mm A - MM/DD ');
}
function addComment(report) {
 const obComment = {
  body: report.comment,
  report_id: report.id,
  user_id: store.user.id,
 };
 axios
  .post(`Comments`, obComment)
  .then(() => {
   store.getReports();
   getReport();
   report.comment = '';
  })
  .catch(() => {
   store.startSnack('error', 'no', 'danger');
  });
}
function deleted() {
 dialogDlete.value = true;
}
function deleteReport() {
 axios.delete(`reports/${router.params.id}`).then(() => {
  dialogDlete.value = false;
  store.startSnack('success', 'back', 'success');
  store.getReports();
 });
}
window.addEventListener('beforeunload', function (event) {
 if (event.pageYOffset === 0) {
  event.preventDefault();
  event.returnValue = '';
  getReport();
 }
});
</script>
<style scoped>
.comment-length {
 font-size: 25px;
}
</style>
