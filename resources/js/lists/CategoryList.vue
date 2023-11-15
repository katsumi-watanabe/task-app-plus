<template>
    <div>
      <v-row
        justify="center"
      >
        <v-btn
          class="bg-grey white"
          @click="categoryListParentDialog = true"
        >
            カテゴリ一覧
        </v-btn>
        <!-- メソッド名修正 -->
        <v-dialog
          v-model="categoryListParentDialog"
          width="800px"
        >
          <v-card>
            <v-card-title>
                カテゴリ一覧
            </v-card-title>

            <v-card-text class="text-right">

                <v-btn
                    class="bg-grey white mb-3 mr-3"
                    @click="categoryListParentDialog = false"
                >
                    タスク一覧
                </v-btn>
                <v-btn
                    class="bg-success white mb-3"
                    color="white"
                    @click="createCategory"
                >
                <v-icon>
                    mdi-plus-circle-outline
                </v-icon>
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
                                <v-btn
                                    color="success"
                                    @click="updateCategory(category)"
                                >
                                <v-icon>mdi-table-edit</v-icon>
                                </v-btn>
                            </td>
                            <td class="text-left">
                                <CategoryDeleteButton
                                    :category="category"
                                    @delete="confirmDeleteCategory"
                                ></CategoryDeleteButton>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
            </v-card-text>
            <v-card-actions>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog
          v-model="categoryListChildDialog"
          max-width="500px"
        >

        <CategoryForm
            @cancel="this.categoryListChildDialog = false"
            :isNew="isNewCategory"
            :category="selectedCategory"
            :formTitle="formTitle"
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
import CategoryDeleteButton from '../buttons/CategoryDeleteButton.vue'
import axios from 'axios';

export default {
    components: {
        CategoryForm,
        CategoryDeleteButton,
    },
    data () {
        return {
            name: '',
            categoryListParentDialog: false,
            categoryListChildDialog: false,
            isNewCategory: true,
            formTitle: '',
            selectedCategory: '',
            categoryList: [],
        }
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
            axios.get('/api/v1/categories').then(res => {
                this.categoryList = res.data.categories;
            }).catch(error => {
                console.error('Error fetching categories:', error);
            });
        },

        createCategory() {
            this.selectedCategory = { name: '' };
            this.formTitle = 'カテゴリ新規登録画面';
            this.isNewCategory = true;
            this.categoryListChildDialog = true;
        },

        updateCategory(category) {
            this.selectedCategory = category;
            this.formTitle = 'カテゴリ編集画面';
            this.isNewCategory = false;
            this.categoryListChildDialog = true;
        },

        confirmNewCategory(category) {
            axios.post('/api/v1/categories', category).then(() => {
                this.fetchCategories();
                this.categoryListChildDialog = false;
            }).catch(error => {
                console.error('Error creating new category:', error);
            })
        },

        confirmUpdateCategory(category) {
            axios.put(`/api/v1/categories/${category.id}`, category).then(() => {
                this.fetchCategories();
                this.categoryListChildDialog = false;
            }).catch(error => {
                console.error('Error updating category:', error);
            })
        },

        confirmDeleteCategory(category) {
            axios.delete(`/api/v1/categories/${category.id}`).then(() => {
                this.fetchCategories();
            }).catch(error => {
                console.error('Error updating category:', error);
            })
        },
    }
}
</script>
