<template>
  <v-main class="bg-indigo-lighten-5">
    <v-container>
      <div>
        <v-row>
          <v-col cols="6">
            <v-select
              class="bg-white"
              label="完了ステータス"
              :items="statusItems"
              v-model="selectedStatus"
              hide-details="auto"
            >
            </v-select>
          </v-col>

          <v-col cols="6">
            <v-select
              class="bg-white"
              label="カテゴリ"
              :items="categories"
              item-title="name"
              item-value="id"
              name="category"
              chips
              multiple
              v-model="selectedCategory"
              hide-details="auto"
            >
            </v-select>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12">
            <v-text-field
              class="bg-white"
              label="フリーワード検索"
              name="keywordSearch"
              v-model="keywordSearch"
              hide-details="auto"
              @keyup.enter="search"
            ></v-text-field>
          </v-col>
        </v-row>

        <v-row class="mb-3">
          <v-col cols="12">
            <v-btn prepend-icon="mdi-magnify" @click="search" class="mr-5 bg-light-blue" type="submit"> 検索 </v-btn>
            <!-- <v-btn prepend-icon="mdi-refresh" @click="reset"> リセット </v-btn> -->
          </v-col>
        </v-row>
      </div>
    </v-container>
  </v-main>
</template>
<script>
  export default {
    data() {
      return {
        categories: [],
        // 検索関連
        selectedStatus: [],
        keywordSearch: '',
        selectedCategory: [],
        statusItems: ['完了', '未完了'],
        displaySearchBox: false,
      };
    },
    mounted() {
      axios
        .get('/api/v1/categories')
        .then(res => {
          this.categories = res.data.categories;
        })
        .catch(error => {
          console.error('Error fetching categories:', error);
        });
    },

    methods: {
      search() {
        this.$store.dispatch('updateStatus', this.selectedStatus);
        this.$store.dispatch('updateCategories', this.selectedCategory);
        this.$store.dispatch('updateKeyword', this.keywordSearch);
        // 検索ボックスの表示状態の決定
        this.displaySearchBox =
          this.selectedStatus.length > 0 || this.selectedCategory.length > 0 || this.keywordSearch.length > 0;
        this.$store.dispatch('updateDisplaySearchBox', this.displaySearchBox);
        this.$router.push({ path: '/' });
      },
    },
  };
</script>
