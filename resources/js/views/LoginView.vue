<template>
  <div>
    <div class="login">
      <img class="img-log" src="../assets/Report.png" />
      <h1 class="h1 my-3 text-center">{{ $t('LogIn') }}</h1>
      <v-form class="my-3" lazy-validation>
        <v-text-field
          type="email"
          variant="outlined"
          v-model="userLog.email"
          label=" الايميل او رقم الهاتف  "
          :rules="[v => !!v || 'This field is required']"
        ></v-text-field>
        <v-text-field
          :type="store.passToggle == true ? 'password' : 'text'"
          :append-inner-icon="store.passToggle ? 'mdi-eye-off-outline' : 'mdi-eye-outline'"
          @click:appendInner="store.passToggle = !store.passToggle"
          variant="outlined"
          autocomplete="no"
          v-model="userLog.password"
          :label="$t('enterPassword')"
          :rules="[v => !!v || 'This field is required']"
        >
        </v-text-field>
      </v-form>
      <v-btn @click="toLogIn" class="mt-2">
        {{ $t('LogIn') }}
      </v-btn>
      <!-- <v-btn @click="authCheck2" class="mt-2"> authCheck2 </v-btn> -->
    </div>
    <div>
      <span>{{ $t('dontAccount') }}</span>
      <router-link to="/auth/register">{{ $t('register') }}</router-link>
    </div>
  </div>
</template>
<script setup>
import { usemainStore } from '@/store/mainStore';
const store = usemainStore();
import { ref } from 'vue';
import axios from 'axios';
const userLog = ref({});
function authCheck2() {
  axios
    .get('check2')
    .then(res => {
      console.log(res);
    })
    .catch(e => {
      console.log(e);
    });
}
function toLogIn(e) {
  axios.get('csrf-cookie').then(() => {
    axios
      .post(`login`, userLog.value)
      .then(res => {
        localStorage.setItem('token', res.data.token);
        localStorage.setItem('user', JSON.stringify(res.data.user));
        store.user = res.data.user;
        store.setAuthHeaderNew(res.data.token);
        store.getUser();
        store.auth = true;
        store.startSnack('success', 'home', 'success', false, 200);
      })
      .catch(e => {
        if (e.response.data.error == 'Error in email or password.') {
          store.startSnack('emailOrPassword', 'no', 'danger');
        } else {
          store.startSnack('error', 'no', 'danger');
        }
      });
  });
}
</script>
<style scoped>
.login {
  max-width: 500px;
  margin: 5% auto;
}

.img-log {
  display: block;
  width: 10%;
  height: 10%;
  margin: auto;
}
</style>
