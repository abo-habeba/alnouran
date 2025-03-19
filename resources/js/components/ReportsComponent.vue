<template>
 <div class="mt-3">
  <h1 class="h1 mt-3 text-center">{{ $t('reports') }}</h1>
  <!-- Delete Report -->
  <v-dialog v-model="dialogDelete">
   <v-card>
    <v-card-title class="text-center m-4">{{ $t('confirm') }}</v-card-title>
    <div class="mx-auto">
     <v-btn class="m-5" color="green" text @click="deleteReport()">{{ $t('yes') }}</v-btn>
     <v-btn class="m-5" color="red" text @click="dialogDelete = false">{{ $t('no') }}</v-btn>
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
  <div class="alert alert-info text-center" v-if="store.reports == 'getData'">
   {{ $t('getData') }}
  </div>
  <div class="alert alert-warning text-center" v-else-if="store.reports == 'noData'">
   {{ $t('noData') }}
  </div>
  <div v-else>
   <v-card class="my-3" v-for="(report, i) in store.reports" :key="i">
    <router-link class="nav-link" :to="`/report/${report.id}`">
     <div id="textShare">
      <v-card-title>
       <v-chip class="float-start">{{ report.user.name }}</v-chip>
       <v-chip class="float-end">{{ report.station.name }}</v-chip>
      </v-card-title>
      <pre class="my-2 text-content">{{ report.body }}</pre>
      <v-chip class="m-1">{{ date(report.created_at) }}</v-chip>
      <v-chip class="m-1">{{ timeSinceReport(report.created_at) }}</v-chip>
     </div>
    </router-link>
    <div class="report-actions">
     <span class="mdi mdi-comment-outline"></span>
     <span class="m-1">{{ report.comments.length }}</span>
     <div v-if="report.user.id == store.user.id" class="float-right mr-4">
      <span class="clickd mdi mdi-file-edit-outline" @click="update(report)"></span>
     </div>
     <div
      v-if="report.user.id == store.user.id || store.user.roles == 'admin'"
      class="float-right mx-4"
     >
      <span class="clickd mdi mdi-delete-outline" @click="deleted(report.id)"></span>
     </div>
     <span class="mx-3 clickd mdi mdi-share-outline" @click="share(report)"></span>
    </div>
    <v-card-actions class="mt-2">
     <v-textarea
      color="grey-lighten-5"
      variant="outlined"
      :rows="1"
      auto-grow
      :label="$t('comment')"
      v-model="report.comment"
     />
     <v-btn icon="mdi-send" @click="addComment(report)"></v-btn>
    </v-card-actions>

    <v-btn class="mb-2" @click="report.showComments = !report.showComments">{{
     report.showComments ? $t('hideComments') : $t('showComments')
    }}</v-btn>
    <v-card
     class="mb-3 p-3"
     v-for="(comment, i) in report.comments.slice(
      0,
      report.showComments ? report.comments.length : 1
     )"
     :key="i"
    >
     <v-chip>{{ comment.user.name }}</v-chip>
     <div class="text-content">{{ comment.body }}</div>
     <span class="float-right">{{ timeSinceReport(comment.created_at) }}</span>
    </v-card>
   </v-card>
  </div>
 </div>
</template>
<script setup>
import { usemainStore } from '@/store/mainStore';
const store = usemainStore();
import { onMounted, ref } from 'vue';
import axios from 'axios';
import moment from 'moment';
const reportId = ref(' ');
const reportVar = ref(' ');
const dialogDelete = ref(false);
const dialogUpdate = ref(false);
onMounted(() => {
 store.getReports();
});
function share(report) {
 const textShare = document.getElementById('textShare').innerText;
 const obComment = {
  text: textShare + '\n',
  url: window.location.origin + '/report/' + report.id,
 };
 navigator.share(obComment);
}
function timeSinceReport(time) {
 return moment(time).fromNow();
}
function date(d) {
 return moment(d).format('dddd :- h:mm A - MM/DD ');
}
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
function deleted(id) {
 reportId.value = id;
 dialogDelete.value = true;
}
function deleteReport() {
 axios.delete(`reports/${reportId.value}`).then(() => {
  dialogDelete.value = false;
  store.startSnack('success', 'no', 'success');
  store.getReports();
 });
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
   report.comment = '';
  })
  .catch(() => {
   store.startSnack('error', 'no', 'danger');
  });
}
</script>
<style>
.report-actions {
 width: 100%;
}

.report-actions span {
 font-size: 25px;
}
#textShare {
 /* background-color: #f5f5f5; */
 border-radius: 6px;
}
</style>
