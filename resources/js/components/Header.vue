<template>
  <v-app-bar color="indigo">
    <template v-slot:prepend>
      <v-btn class="bg-info" @click="$emit('changeDisplay')">
        <v-icon>mdi-filter</v-icon>
        フィルター
      </v-btn>
    </template>

    <v-app-bar-title> タスク管理一覧 </v-app-bar-title>
    <button @click="$emit('searchPage')">検索ページに飛びます</button>

    <template v-slot:append>
      <CategoryList :categories="categories" @click="$emit('fetchCategories')"></CategoryList>
      <v-btn class="bg-success white ml-8" @click="$emit('createTask')">
        <v-icon> mdi-plus-circle-outline </v-icon>
        新規登録
      </v-btn>
    </template>
  </v-app-bar>
</template>

<script>
  import CategoryList from '../lists/CategoryList.vue';
  export default {
    components: {
      CategoryList,
    },
    props: {
      categories: {
        type: Object,
      },
    },
    methods: {
      // 検索ボックス表示切替
      changeDisplay() {
        this.displaySearchBox = !this.displaySearchBox;
      },
    },
  };
</script>
