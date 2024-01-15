<template>
    <h2>componet home</h2>
</template>

<script setup>
console.log('componet home');

</script>
///////////////////////////////////
<!-- <script>
import { useRouter, useLocalStorage } from 'vue';

export default {
  setup() {
    const router = useRouter();
    const localStorage = useLocalStorage();

    // Get the token from localStorage
    const token = localStorage.getItem('token');

    // If the token is not present, redirect the user to the login page
    if (!token) {
      router.push({ name: 'login' });
    }

    // Listen for changes to the token in localStorage
    localStorage.watch('token', (newToken) => {
      // If the token changes, redirect the user to the home page
      if (newToken) {
        router.push({ name: 'home' });
      }
    });
  }
}; -->


// import { useRouter, useRoute } from 'vue-router';
// export default {
//   setup() {
//     const router = useRouter();
//     const route = useRoute();

//     // التحقق من وجود الرمز المميز في localStorage
//     const tokenExists = () => {
//       const token = localStorage.getItem('token');
//       return !!token; // يعيد true إذا تم العثور على الرمز المميز
//     };

//     // التحقق من وجود الرمز المميز قبل دخول الصفحة
//     route.beforeEnter((to, from, next) => {
//       if (to.name === 'login') {
//         // إذا كان المستخدم يحاول الوصول إلى صفحة تسجيل الدخول، فسمح له بذلك
//         next();
//       } else {
//         // إذا كان المستخدم يحاول الوصول إلى صفحة أخرى، قم بالتحقق من وجود الرمز المميز
//         if (tokenExists()) {
//           // إذا تم العثور على الرمز المميز، فاسمح للمستخدم بالوصول إلى الصفحة
//           next();
//         } else {
//           // إذا لم يتم العثور على الرمز المميز، قم بتوجيه المستخدم إلى صفحة تسجيل الدخول
//           next('/login');
//         }
//       }
//     });

//     return {};
//   }
// }

</script>
