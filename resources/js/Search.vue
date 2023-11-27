<template>
  <v-main class="bg-indigo-lighten-5">
    <v-container>
      <div>
        <!-- <v-row>
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
          </v-row> -->

        <v-row>
          <v-col cols="12">
            <v-text-field
              class="bg-white"
              label="フリーワード検索"
              name="keyword_search"
              v-model="keyword_search"
              hide-details="auto"
              @keyup.enter="fetchTasks"
            ></v-text-field>
          </v-col>
        </v-row>

        <v-row class="mb-3">
          <v-col cols="12">
            <v-btn prepend-icon="mdi-magnify" @click="fetchTasks" class="mr-5 bg-light-blue" type="submit">
              検索
            </v-btn>
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
        // 検索関連
        selectedStatus: [],
        keyword_search: '',
        selectedCategory: [],
        statusItems: ['完了', '未完了'],
        displaySearchBox: false,
      };
    },

    methods: {
      fetchTasks() {
        const selectedStatus = this.selectedStatus;
        const keyword = this.keyword_search;
        const category = this.selectedCategory;
        const sortColumnData = [this.sortColumnName, this.sortColumnStatus];
        axios
          .get('/api/v1/tasks', {
            params: {
              status: selectedStatus,
              keyword: keyword,
              category: category,
              column: sortColumnData,
            },
          })
          .then(res => {
            this.tasks = res.data.tasks;
            this.pageCalculation(this.tasks);
          })
          .catch(error => {
            console.error('Error fetching tasks:', error);
          });
      },

      // 検索ボックス表示切替
      changeDisplay() {
        this.displaySearchBox = !this.displaySearchBox;
      },

      //   reset() {
      //     this.selectedStatus = [];
      //     this.keyword_search = '';
      //     this.selectedCategory = [];
      //     this.fetchTasks();
      //   },
    },
  };
</script>
