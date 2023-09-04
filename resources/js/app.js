

import './bootstrap';
import vuetify from './vuetify';
import { createApp } from 'vue';



const app = createApp({});
app.use(vuetify)

import Register from './components/Register.vue';
import Myheader from './components/Myheader.vue';
app.component('Register', Register);
app.component('Myheader', Myheader);



app.mount('#app');
