<template style="background-color: #e8eaf6">
  <v-app class="d-block">
      <router-view></router-view>
    <Footer
    @homePage="homePage"
    @categoryPage="categoryPage"
    ></Footer>
  </v-app>
</template>

<script>
  import Footer from './components/Footer.vue';
  import { useRouter } from 'vue-router';

  export default {
    setup() {
      const router = useRouter();

      function homePage() {
        router.push('/');
      }
      function categoryPage() {
        router.push('/category');
      }
      return {
        homePage,
        categoryPage,
      };
    },
    components: {
      Footer,
    },
  };
</script>
