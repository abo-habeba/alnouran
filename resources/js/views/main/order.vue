<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <h1>طلب مشتريات</h1>
      </v-col>
    </v-row>

    <!-- إضافة بند جديد -->
    <v-row>
      <v-col cols="12">
        <v-card class="pa-3 d-f-row">
          <v-text-field
            type="text"
            variant="outlined"
            label="اسم البند"
            v-model="subItemKey"
          ></v-text-field>
          <v-btn class="ma-3" @click="addItem(subItemKey)" color="success"> إضافة بند </v-btn>
        </v-card>
      </v-col>
    </v-row>

    <v-row v-for="(mainItem, mainIndex) in mainItems" :key="mainIndex">
      <v-col cols="12">
        <v-card class="ma-3">
          <v-card-title>
            <h3>{{ Object.keys(mainItem)[0] }}</h3>
          </v-card-title>
          <v-card-text>
            <v-row>
              <v-col cols="8">
                <v-text-field
                  type="text"
                  variant="outlined"
                  :label="` إضافة ${Object.keys(mainItem)[0]}`"
                  v-model="newSubItem"
                ></v-text-field>
              </v-col>
              <v-col cols="4">
                <v-btn @click="addSubItem(mainIndex, Object.keys(mainItem)[0])" color="success">
                  {{ `إضافة ${Object.keys(mainItem)[0]}` }}
                </v-btn>
              </v-col>
            </v-row>
            <v-list>
              <ul v-for="(it, index) in mainItem[Object.keys(mainItem)[0]]" :key="index">
                <li class="d-f-row">
                  <span
                    style="
                      display: inline-block;
                      font-size: 25px;
                      font-weight: bold;
                      padding: 5px 10px;
                      border: blue solid 1px;
                      border-radius: 7px;
                    "
                    >{{ it }}</span
                  >
                  <v-btn
                    color="red"
                    @click="removeSubItem(mainIndex, Object.keys(mainItem)[0], index)"
                  >
                    <v-icon>mdi-delete</v-icon>
                  </v-btn>
                </li>
              </ul>
            </v-list>
            <v-btn @click="removeMainItem(mainIndex)" color="red">
              {{ `حذف ${Object.keys(mainItem)[0]}` }}
            </v-btn>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <!-- عرض الإحصائيات -->
    <v-card class="ma-3 pa-2" id="textShare">
      <v-row v-for="(mainItem, index) in mainItems" :key="index">
        <v-col cols="12">
          <div v-for="(item, key) in mainItem" :key="key">
            <h2>{{ key }}</h2>
            <ul v-for="(it, index) in item" :key="index">
              <li class="d-f-row">
                <span class="box-item">{{ it }}</span>
              </li>
            </ul>
            <br />
            <h4 v-if="item.length > 0">إجمالي الفلوس {{ sumSubItems(index, key) }}</h4>
          </div>
        </v-col>
      </v-row>
    </v-card>
  </v-container>
</template>

<script setup>
import { ref } from 'vue';

// تعريف حالة المكونات
const mainItems = ref(JSON.parse(localStorage.getItem('mainItems')) || []);
const subItemKey = ref('');
const newSubItem = ref('');

function share(report) {
  const textShare = document.getElementById('textShare').innerText;
  const obComment = {
    text: textShare + '\n',
  };
  navigator.share(obComment);
}
// دالة لإضافة بند جديد
function addItem(keyName) {
  if (keyName.trim() !== '') {
    const newItem = {
      [keyName]: [],
    };
    mainItems.value.push(newItem);
    subItemKey.value = '';
    updateLocalStorage();
  }
}

// دالة لإضافة عنصر فرعي
function addSubItem(mainIndex, key) {
  if (newSubItem.value.trim() !== '') {
    mainItems.value[mainIndex][key].push(Number(newSubItem.value));
    newSubItem.value = '';
    updateLocalStorage();
  }
}

// دالة لحذف عنصر فرعي
function removeSubItem(mainIndex, key, subIndex) {
  mainItems.value[mainIndex][key].splice(subIndex, 1);
  updateLocalStorage();
}

// دالة لحذف بند رئيسي
function removeMainItem(mainIndex) {
  mainItems.value.splice(mainIndex, 1);
  updateLocalStorage();
}

// دالة لحساب مجموع الأرقام داخل العناصر الفرعية
function sumSubItems(mainIndex, key) {
  return mainItems.value[mainIndex][key].reduce((acc, cur) => acc + cur, 0);
}

// دالة لتحديث localStorage
function updateLocalStorage() {
  localStorage.setItem('mainItems', JSON.stringify(mainItems.value));
}
</script>

<style scoped>
.box-item {
  display: inline-block;
  font-size: 25px;
  font-weight: bold;
  padding: 5px 10px;
  border: blue solid 1px;
  border-radius: 7px;
}
.d-f-row {
  display: flex;
}
.app {
  max-width: 800px;
  margin: auto;
  padding: 10px;
}

.main-item {
  margin-bottom: 10px;
}

ul {
  list-style-type: none;
  padding: 0;
}

li {
  background: #f4f4f4;
  margin: 3px 0;
  padding: 8px;
  border-radius: 5px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
</style>
