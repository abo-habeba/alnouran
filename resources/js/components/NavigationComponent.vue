<template>
  <v-navigation-drawer
    :class="rail == false ? 'navbar-width' : ' '"
    v-model="drawer"
    :rail="rail"
    permanent
  >
    <v-list>
      <v-list-item nav>
        <template v-slot:append>
          <v-btn
            variant="text"
            :icon="!rail ? 'mdi-close' : 'mdi-menu'"
            @click.stop="rail = !rail"
          ></v-btn>
        </template>
        <v-list-item
          prepend-icon="mdi-account"
          :title="store.user.name"
          nav
          to="/user/edit"
        ></v-list-item>
      </v-list-item>
    </v-list>
    <v-list v-model:opened="open">
      <v-list-item
        prepend-icon=" mdi-account-details"
        :title="$t('Vacations')"
        to="/vacations"
      ></v-list-item>
      <v-list-item prepend-icon="mdi-bug" :title="$t('reports')" to="/reports"></v-list-item>
      <v-list-item
        v-if="store.user.roles == 'admin'"
        prepend-icon="mdi-target-variant"
        title=" المحطات "
        to="/stations"
      ></v-list-item>
      <v-list-item
        v-if="store.user.roles == 'admin'"
        prepend-icon="mdi-account-group"
        title="المستخدمين"
        to="/users"
      ></v-list-item>
      <v-list-item
        prepend-icon="mdi-bowl-mix-outline"
        title=" التحضيرات "
        to="/preparation"
      ></v-list-item>
    </v-list>
    <template v-slot:append>
      <div class="pa-2">
        <v-btn color="red" icon="mdi-login" @click="toLogout"></v-btn>
      </div>
    </template>
  </v-navigation-drawer>
  <div @click="rail = !rail" :class="pageWidth <= 765 && !rail ? 'transBox' : ''"></div>
</template>
<script setup>
import { usemainStore } from '@/store/mainStore';
const store = usemainStore();
import { ref, onMounted } from 'vue';

const drawer = ref(true);
const rail = ref(false);
const pageWidth = ref(0);

const open = ref(['Users']);
onMounted(() => {
  // يتم استدعاء هذه الدالة عندما يتم تحميل المكون
  pageWidth.value = window.innerWidth; // تعيين قيمة عرض الصفحة
  rail.value = pageWidth.value <= 765;
  // يتم إضافة استماع لحدث تغيير حجم النافذة لتحديث عرض الصفحة عند تغيير حجم النافذة
  window.addEventListener('resize', handleResize);
});

// دالة للتعامل مع تغيير حجم النافذة
const handleResize = () => {
  pageWidth.value = window.innerWidth; // تحديث قيمة عرض الصفحة عند تغيير حجم النافذة
  rail.value = pageWidth.value <= 765;
};
function toLogout() {
  axios
    .post(`logout`)
    .then(() => {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      store.setAuthHeaderNew();
      store.startSnack('success', 'login', 'success', false, 200);
      store.auth = false;
    })
    .catch(() => {
      store.startSnack('error', 'no', 'danger');
    });
}
</script>
<style>
/* .navbar-width {
    width: fit-content !important;
} 
*/

.v-navigation-drawer {
  position: fixed !important;
  right: 0px;
}

.transBox {
  width: 100%;
  height: 100%;
  position: fixed;
  background-color: rgba(20, 20, 231, 0.273);
  z-index: 99;
}
</style>
