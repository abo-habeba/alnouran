<template>
 <!-- <speedDialComponent/> -->
 <v-card class="mx-auto" width="100%" elevation="2">
  <routerLink to="/user/edit">
   <v-card-title>
    {{ store.user.name }}
   </v-card-title>
   <v-card-subtitle>
    {{ store.user.email }}
   </v-card-subtitle>
  </routerLink>
  <v-card-item>
   <router-link
    class="mx-2"
    :to="`/station/${station.id}`"
    v-for="(station, i) in store.user.stations"
    :key="i"
    >{{ station.name }}
   </router-link>
  </v-card-item>
 </v-card>
 <v-row class="div-balance text-center">
  <v-col cols="12">
   <router-link to="/vacations">
    <div class="box-balance">
     <div class="item-balance">الاعتيادية</div>
     <div class="item-balance">{{ store.regular }}</div>
    </div>
   </router-link>
  </v-col>
 </v-row>
 <v-row>
  <v-col cols="5">
   <addPreparationComponent />
  </v-col>
 </v-row>
 <v-row>
  <v-col cols="12" xs="6" sm="8" md="4" lg="4" v-for="typePrep in typePreparationData" :key="typePrep.id">
   <v-card class="p-3" v-if="typePreparationData">
    <div class="preparation_box">
     <div class="percentage">
      <p>{{ typePrep.name }}</p>
      <p>% {{ percentageResalt(100 - typePrep.percentage) }}</p>
     </div>
     <div
      :style="{
       height: 100 - typePrep.percentage + '%',
       'background-color': 100 - typePrep.percentage <= 20 ? 'red' : '#14cc14',
      }"
      class="preparation_fill"
     ></div>
    </div>
    <div class="mt-3">
     <v-chip class="ma-2" color="primary" label>
      <span class="p-1">{{ typePrep.cont_hours }}</span> <span class="p-1"> ساعة </span>
     </v-chip>
     <v-chip class="ma-2" color="primary" label>
      <span class="p-1"> منذ </span>
      <span class="p-1">{{ timeSinceReport(typePrep.actual_time) }}</span>
      <span class="p-1"> ساعة </span>
     </v-chip>
     <v-chip class="ma-2" color="primary" label>
      <span class="p-1"> وقت التحضير </span>
      <span class="p-1">{{ date(typePrep.actual_time) }}</span>
     </v-chip>
    </div>
   </v-card>
  </v-col>
 </v-row>
</template>
<script setup>
import moment from 'moment';
import { onMounted, ref, computed } from 'vue';
import { usemainStore } from '@/store/mainStore';
import addPreparationComponent from '../components/preparation/addPreparationComponent.vue';
const store = usemainStore();
const typePreparationData = ref([]);

const dateN = new Date(); // الحصول على التاريخ والوقت الحاليين
const hoursToAdd = 1; // عدد الساعات المراد إضافتها
const oneHourInMilliseconds = 3600000; // تحويل ساعة إلى مللي ثانية
dateN.setTime(dateN.getTime() + hoursToAdd * oneHourInMilliseconds);
const now = moment();
console.log(dateN);
const diff = moment(dateN).diff(now, 'hours');
console.log(diff);
function percentageResalt(percentage) {
 return percentage.toFixed();
}
function timeSinceReport(time) {
 //  return moment(time).fromNow();
 const now = moment();
 return -moment(time).diff(now, 'hours');
}
function date(d) {
 return moment(d).format('dddd :- h:mm A - MM/DD ');
}
onMounted(() => {
 store.getAbsences();
 store.getUser();
 store.getReports();
 typePreparFunc();
});

function typePreparFunc() {
 store.getTypePre().then(() => {
  for (let i = 0; i < store.typePreparation.length; i++) {
   const newPreparationData = {
    id: store.typePreparation[i].id,
    name: store.typePreparation[i].name,
    actual_time: store.typePreparation[i].latest_preparation_actual.actual_time,
    cont_hours: store.typePreparation[i].latest_preparation_actual.cont_hours,
    hoursDifference: calculateHoursDifference(
     store.typePreparation[i].latest_preparation_actual.actual_time
    ).toFixed(2),
    percentage: (
     (calculateHoursDifference(store.typePreparation[i].latest_preparation_actual.actual_time) /
      store.typePreparation[i].latest_preparation_actual.cont_hours) *
     100
    ).toFixed(2),
   };
   typePreparationData.value.push(newPreparationData);
  }
  function calculateHoursDifference(actualTime) {
   const dataTime = new Date(actualTime);
   const currentTime = new Date();
   const timeDifference = currentTime - dataTime;
   return timeDifference / (1000 * 60 * 60);
  }
 });
}
</script>
<style scoped>
a {
 text-decoration: none;
}
.preparation_box {
 position: relative;
 width: 100%;
 height: 20vh;
 border-bottom: 5px solid blue;
 border-left: 5px solid blue;
 border-right: 5px solid blue;
 border-radius: 0 0 20px 20px;
 overflow: hidden;
}
.preparation_fill {
 position: absolute;
 bottom: 0px;
 width: 100%;
 box-sizing: unset;
}
.pre_fill_red {
 background-color: red;
}
.pre_fill_green {
 background-color: #14cc14;
}
.percentage {
 position: absolute;
 width: 100%;
 font-weight: bold;
 top: 50px;
 text-align: center;
 position: absolute;
 display: block;
 z-index: 1;
}
</style>
