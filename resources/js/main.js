import '../css/app.css';
import { createPinia } from 'pinia';
import { createApp } from 'vue';
import App from './App.vue';
import PopupComponent from './components/PopupComponent.vue';
import router from './router';
import 'vuetify/styles';
import 'animate.css';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import '@mdi/font/css/materialdesignicons.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
import { createI18n } from 'vue-i18n';
import enuc from './locales/en.json';
import areg from './locales/ar.json';
import { ar } from 'vuetify/locale';
if (!localStorage.defaultTheme) {
  localStorage.setItem('defaultTheme', false);
}
const vuetify = createVuetify({
  theme: {
    defaultTheme: localStorage.defaultTheme == 'true' ? 'dark' : 'light',
  },
  locale: {
    locale: 'ar',
    fallback: 'en',
    messages: {
      ar,
    },
  },
  components,
  directives,
});
const i18n = createI18n({
  locale: 'ar',
  messages: {
    en: enuc,
    ar: areg,
  },
});
createApp(App)
  .component('popup', PopupComponent)
  .use(i18n)
  .use(router)
  .use(vuetify)
  .use(createPinia())
  .mount('#app');
