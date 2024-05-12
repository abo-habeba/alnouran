<template>
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
  <!-- <v-col cols="12" sm="4">
            <router-link to="/rest">
                <div class="box-balance">
                    <div class="item-balance">بدل راحة</div>
                    <div class="item-balance">{{ store.rest }}</div>
                </div>
            </router-link>
        </v-col> -->
  <!-- <v-col cols="12" sm="4">

            <router-link to="/reports">
                <div class="box-balance">
                    <div class="item-balance">التقارير</div>
                    <div class="item-balance">
                        {{
                            store.reports == "getData" ||
                            store.reports == "noData"
                                ? 0
                                : store.reports.length
                        }}
                    </div>
                </div>
            </router-link>
        </v-col> -->

  <v-col v-for="typePrep in typePreparationData" :key="typePrep.id">
    
   <v-card class="p-3" v-if="typePreparationData">
    <div class="preparation_box">
     <div class="percentage">
      <p>{{ typePrep.name }}</p>
      <p>% {{ percentageResalt(100 - typePrep.percentage) }}</p>
     </div>
     <div :style="{ height: 100 - typePrep.percentage + '%' }" class="preparation_fill"></div>
    </div>
    <div class="my-3">
     <p>عدد الساعات</p>
     <p>{{ typePrep.cont_hours }}</p>
     <p>وقت التحضير</p>
     <p>{{ typePrep.actual_time }}</p>
    </div>
   </v-card>
  </v-col>
 </v-row>
</template>
<script setup>
import { onMounted, ref, computed } from 'vue';
import { usemainStore } from '@/store/mainStore';
const store = usemainStore();
const typePreparationData = ref([]);

function percentageResalt(percentage) {
 return percentage.toFixed(2);
}
onMounted(() => {
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
  console.log(typePreparationData.value);
  function calculateHoursDifference(actualTime) {
   const dataTime = new Date(actualTime);
   const currentTime = new Date();
   const timeDifference = currentTime - dataTime;
   return timeDifference / (1000 * 60 * 60);
  }
 });

 store.getAbsences();
 store.getUser();
 store.getReports();
 // console.log(store.typePreparation.latest_preparation_actual.actual_time);
});
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
 background-color: #14cc14;
 box-sizing: unset;
}
.percentage {
 position: absolute;
 width: 100%;
 font-weight: bold;
 top: 50px;
 text-align: center;
 position: absolute;
 display: block;
}
</style>
