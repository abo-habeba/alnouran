<template>
 <div v-if="user">
  <v-card class="my-2 p-2">
   <div class="">
    <h1 class="h1 my-1 text-center">{{ user.name }}</h1>
   </div>
   <v-chip class="m-1">{{ user.Job_title }}</v-chip>
   <v-chip v-if="user.rest_balance" class="m-1"
    ><span class="p-1">بدل راحة</span> {{ user.rest_balance.balance }}</v-chip
   >
   <v-chip v-if="user.regular_balance" class="m-1"
    ><span class="p-1"> الرصيد الاعتيادي </span> {{ user.regular_balance.balance }}</v-chip
   >
  </v-card>
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
  <!-- rest allowance User -->
  <v-card class="my-2 p-2">
   <h6 style="text-align: center">البدلات</h6>
   <div style="white-space: nowrap; overflow: auto; width: 100%">
    <div v-if="user.restallowance == null" class="text-center m-1">لا يوجد بدل راحة</div>
    <table v-else class="table table-striped">
     <thead>
      <tr>
       <th>#</th>
       <th>الحالة</th>
       <th>تاريخ الاجازة</th>
       <th>الوصف</th>
       <th>تاريخ الانشاء</th>
      </tr>
     </thead>
     <tbody>
      <tr
       class="box-absence"
       v-for="(restallo, index) in [user.restallowance]"
       :id="'id-' + restallo.id"
       :key="restallo.id"
      >
       <td>{{ index + 1 }}</td>
       <td>{{ restallo.state }}</td>
       <td>{{ restallo.date }}</td>
       <td>{{ restallo.description }}</td>
       <td>{{ store.formatDate(restallo.created_at) }}</td>
      </tr>
     </tbody>
    </table>
   </div>
  </v-card>
  <!-- absences User -->
  <v-card class="my-2 p-2">
   <h6 style="text-align: center">الاجازات</h6>
   <div style="white-space: nowrap; overflow: auto; width: 100%">
    <div v-if="chickObject(user.absences)" class="text-center m-1">لا توجد اجازات</div>
    <table v-else class="table table-striped">
     <thead>
      <tr>
       <th>#</th>
       <th>نوع الاجازة</th>
       <th>تاريخ الاجازة</th>
       <th>الوصف</th>
       <th>تاريخ الانشاء</th>
      </tr>
     </thead>
     <tbody>
      <tr
       class="box-absence"
       v-for="(absence, index) in user.absences"
       :id="'id-' + absence.id"
       :key="absence.id"
      >
       <td>{{ index + 1 }}</td>
       <td>{{ $t(absence.Type) }}</td>
       <td>{{ absence.date }}</td>
       <td>{{ absence.description }}</td>
       <td>{{ store.formatDate(absence.created_at) }}</td>
      </tr>
     </tbody>
    </table>
   </div>
  </v-card>
  <!-- stations user -->
  <v-card>
   <v-tabs
    slider-color="teal-lighten-3"
    show-arrows
    v-if="user != undefined"
    v-model="tab"
    align-tabs="center"
    color="deep-purple-accent-4"
   >
    <v-tab v-for="(station, i) in user.stations" :key="i" :value="station.name">
     {{ station.name }}
    </v-tab>
   </v-tabs>
   <v-window v-model="tab">
    <v-window-item v-for="(station, i) in user.stations" :key="i" :value="station.name">
     <h5 v-if="station.reports?.length == 0" class="my-3 text-center">لا يوجد تقارير</h5>
     <v-card class="my-3" v-for="(report, i) in station.reports" :key="i" color="grey-lighten-2 p-3">
      <router-link class="nav-link" :to="`/report/${report.id}`">
       <div id="textShare">
        <v-card-title>
         <v-chip class="float-start">{{ report.user.name }}</v-chip>
         <!-- <v-chip class="float-end">{{ report.station.name }}</v-chip> -->
        </v-card-title>
        <v-card-text color="grey-lighten-4" class="my-2 text-content">{{
         report.body
        }}</v-card-text>
        <v-chip class="m-1">{{ date(report.created_at) }}</v-chip>
        <v-chip class="m-1">{{ timeSinceReport(report.created_at) }}</v-chip>
       </div>
      </router-link>
      <!-- report-action -->
      <div>
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
      </div>

      <!-- comments -->
      <div v-if="report.comments.length != 0">
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
      </div>
     </v-card>
    </v-window-item>
   </v-window>
  </v-card>
 </div>
</template>

<script setup>
import { usemainStore } from '@/store/mainStore';
import moment from 'moment';
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
const store = usemainStore();
const router = useRouter();
const route = useRoute();
const dialog = ref(false);
const reports = ref(null);
const reportId = ref(' ');
const reportVar = ref(' ');
const dialogDelete = ref(false);
const dialogUpdate = ref(false);
const user = ref(store.user);
const tab = ref(null);
function getReports(reports) {
 console.log(reports);
 reports.value = reports;
}
function chickObject(object) {
 if (Array.isArray(object) && object.length > 0) {
  return false;
 } else {
  return true;
 }
}
onMounted(() => {
 console.log(typeof user.absences);
 axios
  .get(`users/${route.params.id}`)
  .then(res => {
   user.value = res.data;
   console.log(user.value);
  })
  .catch(() => {
   user.value = 'noUser';
  });
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
   getReport();
   report.comment = '';
  })
  .catch(() => {
   store.startSnack('error', 'no', 'danger');
  });
}

// function editProfil() {
//     axios
//         .put(`users/${store.user.id}`, user.value)
//         .then(() => {
//             store.startSnack("success", "no", "success");
//             if (user.value.password) {
//                 store.logout();
//             }
//         })
//         .catch(() => {
//             store.startSnack("error", "no", "danger");
//         });
// }
// function funDelete() {
//     axios.delete(`users/${store.user.id}`).then(() => {
//         dialog.value = false;
//         localStorage.removeItem("token");
//         localStorage.removeItem("user");
//         store.setAuthHeaderNew();
//         store.startSnack("success", "login", "success", false, 200);
//         store.auth = false;
//     }).catch(() => {
//         store.startSnack("error", "no", "danger");
//     })
// }
</script>
<style scoped>
.btn-password {
 color: white !important;
 background-color: #ff5722 !important;
 /* position: sticky !important;
    right: 20%;
    bottom: 50%; */
}
</style>
