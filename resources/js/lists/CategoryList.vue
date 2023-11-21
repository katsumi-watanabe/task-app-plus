<template>
  <div>
    <v-row justify="center">
      <v-btn class="bg-grey white" @click="categoryListParentDialog = true"> カテゴリ一覧 </v-btn>
      <!-- メソッド名修正 -->
      <v-dialog v-model="categoryListParentDialog" width="800px">
        <v-card>
          <v-card-title> カテゴリ一覧 </v-card-title>

          <v-card-text class="text-right">
            <v-btn class="bg-grey white mb-3 mr-3" @click="categoryListParentDialog = false"> 閉じる </v-btn>
            <v-btn class="bg-success white mb-3" color="white" @click="createCategory">
              <v-icon> mdi-plus-circle-outline </v-icon>
              新規登録
            </v-btn>

            <v-table fixed-header height="auto" class="mt-3">
              <thead>
                <tr>
                  <th class="text-left">カテゴリ名</th>
                  <th class="text-left">編集</th>
                  <th class="text-left">削除</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="category in categoryList" :key="category.id">
                  <td class="text-left">
                    {{ category.name }}
                  </td>
                  <td class="text-left">
                    <v-btn color="success" @click="updateCategory(category)">
                      <v-icon>mdi-table-edit</v-icon>
                    </v-btn>
                  </td>
                  <td class="text-left">
                    <DeleteButton
                      :id="category.id"
                      @delete="confirmDeleteCategory"
                    ></DeleteButton>
                  </td>
                </tr>
              </tbody>
            </v-table>
          </v-card-text>
          <v-card-actions> </v-card-actions>
        </v-card>
      </v-dialog>
      <v-dialog v-model="categoryListChildDialog" max-width="500px">
        <CategoryForm
          :isNew="isNewCategory"
          :category="selectedCategory"
          @cancel="this.categoryListChildDialog = false"
          @create="confirmNewCategory"
          @update="confirmUpdateCategory"
        >
        </CategoryForm>
      </v-dialog>
    </v-row>
  </div>
</template>
<script>
  import CategoryForm from '../forms/CategoryForm.vue';
  import DeleteButton from '../buttons/DeleteButton.vue';
  import axios from 'axios';

  export default {
    components: {
      CategoryForm,
      DeleteButton,
    },
    data() {
      return {
        categoryListParentDialog: false,
        categoryListChildDialog: false,
        isNewCategory: true,
        selectedCategory: '',
        categoryList: [],
        // 多重送信防止フラグ
        isDisabled: false,
      };
    },
    props: {
      categories: {
        type: Object,
      },
    },
    mounted() {
      this.fetchCategories();
    },
    methods: {
      fetchCategories() {
        axios
          .get('/api/v1/categories')
          .then(res => {
            this.categoryList = res.data.categories;
          })
          .catch(error => {
            console.error('Error fetching categories:', error);
          });
      },

      createCategory() {
        this.selectedCategory = { name: '' };
        this.isNewCategory = true;
        this.categoryListChildDialog = true;
      },

      updateCategory(category) {
        this.selectedCategory = category;
        this.isNewCategory = false;
        this.categoryListChildDialog = true;
      },

      confirmNewCategory(category) {
        this.isDisabled = true;
        axios
          .post('/api/v1/categories', category)
          .then(() => {
            this.fetchCategories();
            this.categoryListChildDialog = false;
          })
          .catch(error => {
            console.error('Error creating new category:', error);
          })
          .finally(() => {
            this.isDisabled = false;
          });
      },

      confirmUpdateCategory(category) {
        axios
          .put(`/api/v1/categories/${category.id}`, category)
          .then(() => {
            this.fetchCategories();
            this.categoryListChildDialog = false;
          })
          .catch(error => {
            console.error('Error updating category:', error);
          });
      },

      confirmDeleteCategory(category_id) {
        this.isDisabled = true;
        axios
          .delete(`/api/v1/categories/${category_id}`)
          .then(() => {
            this.fetchCategories();
          })
          .catch(error => {
            console.error('Error updating category:', error);
          })
          .finally(() => {
            this.isDisabled = false;
          });
      },
    },
  };
</script>
