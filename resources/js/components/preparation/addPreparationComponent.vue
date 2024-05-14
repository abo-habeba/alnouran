<template>
 <div>
  <!-- New Preparation -->
  <v-btn class="m-1" color="green" text @click="dialogAddPreparation = true">اضافة تحضيرة </v-btn>
  <v-dialog v-model="dialogAddPreparation">
   <v-card class="p-3">
    <v-container>
     <v-card-title class="text-center mb-3"> اضافة تحضيرة </v-card-title>
     <v-row>
      <v-col style="padding: 0px" class="castm_col" cols="12">
       <v-select
        label=" نوع التحضيرة  "
        :items="store.typePreparation"
        item-title="name"
        item-value="id"
        return-object
        @update:model-value="shangePrep"
       >
       </v-select>
      </v-col>
      <v-col v-if="stations.length > 1" style="padding: 0px" class="castm_col" cols="12">
       <v-select
        label=" اختر المحطة "
        :items="stations"
        item-title="name"
        item-value="id"
        auto-select-first
        v-model="newPreparation.station_id"
       >
       </v-select>
      </v-col>
      <v-col style="padding: 0px 0px 0 3px" class="castm_col" cols="6">
       <v-text-field
        type="datetime-local"
        variant="outlined"
        @update:model-value="quantityFin"
        label=" وقت التحضيرة "
        v-model="newPreparation.actual_time"
       >
       </v-text-field>
      </v-col>
      <v-col style="padding: 0 3px 0 0" class="castm_col" cols="6">
       <v-text-field
        type="number"
        variant="outlined"
        @update:model-value="quantityFin"
        label=" ppm "
        v-model="newPreparation.ppm"
       >
       </v-text-field>
      </v-col>
      <v-col style="padding: 0 0 0 3px" class="castm_col" cols="6">
       <v-text-field
        type="number"
        variant="outlined"
        @update:model-value="quantityFin"
        label=" عدد الساعات "
        v-model="newPreparation.cont_hours"
       >
       </v-text-field>
      </v-col>
      <v-col style="padding: 0 3px 0 0" class="castm_col" cols="6">
       <v-text-field
        type="number"
        variant="outlined"
        @update:model-value="quantityFin"
        label=" طن الشرائح "
        v-model="newPreparation.slices_ton"
       >
       </v-text-field>
      </v-col>
      <v-col style="padding: 0px" class="castm_col" cols="6">
       <div class="label_quantity">
        <div>
         <h3>كيلو</h3>
        </div>
        <div>
         <h3>{{ newPreparation.quantity }}</h3>
        </div>
       </div>
       <!-- <v-text-field
                                type="number"
                                variant="outlined"
                                label=" كيلو "
                                v-model="newPreparation.quantity"
                            >
                            </v-text-field> -->
      </v-col>
      <v-col style="padding: 0px" class="castm_col" cols="6">
       <div class="label_quantity">
        <div>
         <h3>سم</h3>
        </div>
        <div>
         <h3>{{ newPreparation.quantityGallon }}</h3>
        </div>
       </div>
       <!-- <v-text-field
                                type="number"
                                variant="outlined"
                                label=" سم "
                                v-model="newPreparation.quantityGallon"
                            >
                            </v-text-field> -->
      </v-col>
      <v-col class="castm_col" cols="12">
       <v-text-field type="text" variant="outlined" label=" ملاحظة " v-model="newPreparation.note">
       </v-text-field>
      </v-col>
      <v-col style="padding: 0px" class="castm_col" cols="12">
       <p style="padding: 0; margin: 0">الوردية</p>
       <v-radio-group inline v-model="timeOfDay">
        <v-radio label=" الاولي " value="الاولي"></v-radio>
        <v-radio label=" التانية " value="الثانية"></v-radio>
       </v-radio-group>
      </v-col>
      <div class="mx-auto">
       <v-btn class="mx-3" color="green" text @click="addPreparation()">{{ $t('yes') }}</v-btn>
       <v-btn class="mx-3" color="red" text @click="dialogAddPreparation = false">{{
        $t('no')
       }}</v-btn>
      </div>
     </v-row>
    </v-container>
   </v-card>
  </v-dialog>
  <v-dialog v-model="dialogError">
   <v-card>
    <v-card-title v-if="messageError.pp"> حقل ال ppm مطلوب </v-card-title>
    <v-card-title v-if="messageError.actual_time"> وقت التحضير مطلوب </v-card-title>
    <v-card-title v-if="messageError.slices_ton"> طن الشرائح مطلوب </v-card-title>
   </v-card>
  </v-dialog>
 </div>
</template>

<script setup>
import { onMounted, ref, watchEffect, defineProps } from 'vue';
import { usemainStore } from '../../store/mainStore';
import axios from 'axios';
import moment from 'moment';
// const now = moment();
//  return  moment("2024-05-15T08:12:39.000000Z").diff(now, 'hour');
const store = usemainStore();
const newPreparation = ref({});
const dialogAddPreparation = ref(false);
const dialogError = ref(false);
const messageError = ref('');
const stations = ref('');
const typePreparation = ref('');
const timeOfDay = ref('');
const quantityFin = ref('');

watchEffect(() => {
 if (
  !isNaN(parseFloat(newPreparation.value.ppm)) &&
  !isNaN(parseFloat(newPreparation.value.cont_hours)) &&
  !isNaN(parseFloat(newPreparation.value.slices_ton))
 ) {
  newPreparation.value.quantity = (
   (newPreparation.value.slices_ton * newPreparation.value.cont_hours * newPreparation.value.ppm) /
   1000
  ).toFixed(2);
  newPreparation.value.quantityGallon = (newPreparation.value.quantity / 14.8).toFixed(2);
 }
});

onMounted(() => {
 axios
  .get(`Stations?current_user=${store.user.id}`)
  .then(res => {
   stations.value = res.data;
   newPreparation.value.station_id = stations.value[0].id;
  })
  .catch(() => {
   store.startSnack('error', 'no', 'danger');
  });
 store.getTypePre();
 // إنشاء كائن Date للحصول على الوقت الحالي
 const currentTime = new Date();
 // الحصول على ساعة اليوم من الوقت الحالي (بتنسيق 24 ساعة)
 const currentHour = currentTime.getHours();
 // تحديد ما إذا كان الوقت صباحًا أم مساءً
 if (currentHour >= 8 && currentHour < 20) {
  timeOfDay.value = 'الاولي';
 } else {
  timeOfDay.value = 'الثانية';
 }
});

function shangePrep(x) {
 typePreparation.value = x;
 if (x.latest_preparation_actual != null) {
  newPreparation.value = x.latest_preparation_actual;
 }
}
function addPreparation() {
 newPreparation.value.user_id = store.user.id;
 newPreparation.value.typePreparation_id = typePreparation.value.id;
 newPreparation.value.shift = timeOfDay.value;

 axios
  .post(`Pre`, newPreparation.value)
  .then(() => {
    dialogAddPreparation.value = false;
   getTypePreFun();
   newPreparation.value = '';
   typePreparation.value = '';
   store.startSnack('success', 'no', 'success');
  })
  .catch(e => {
   console.log(e.response.data);
   if (e.response.data) {
    messageError.value = e.response.data;
    dialogError.value = true;
   }
   store.startSnack('error', 'no', 'danger');
  });
}
</script>

<style lang="scss">
.v_row {
 * {
  padding: 0 !important;
 }
 .castm_col {
  padding: 0 !important;
 }
}

.label_quantity {
 display: flex;
 flex-direction: row-reverse;
 justify-content: space-evenly;
 align-items: center;
 h3 {
  width: 100%;
  text-align: center;
  display: inline-block;
  font-weight: bold;
  font-size: 23px;
 }
}
</style>
