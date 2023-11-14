<template>
    <div>
      <v-row
        justify="center"
      >
        <v-btn
          class="bg-grey white"
          @click="CatListParDlg = true"
        >
            カテゴリ一覧
        </v-btn>
        <!-- メソッド名修正 -->
        <v-dialog
          v-model="CatListParDlg"
          width="800px"
        >
          <v-card>
            <v-card-title>
                カテゴリ一覧
            </v-card-title>

            <v-card-text class="text-right">

                <v-btn
                    class="bg-grey white mb-3 mr-3"
                    @click="CatListParDlg = false"
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

                <v-table height="200px" class="mt-3">
                    <thead>
                        <tr>
                            <th class="text-left">カテゴリ名</th>
                            <th class="text-left">編集</th>
                            <th class="text-left">削除</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="category in categories" :key="category.id">
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
                                    @cancel="CategoryDeleteButton = false"
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
          v-model="catListChildDlg"
          max-width="500px"
        >

        <CategoryForm
            @cancel="confirmCancel"
            :isNew="isNewCategory"
            :category="selectedCategory"
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
        categories: '',
        name: '',
        CatListParDlg: false,
        catListChildDlg: false,
        catDeleteDlg: false,


        selectedCategory: '',
        isNewCategory: true,
    }
    },
    mounted() {
        // 読み込み時にマウントされる,認識しておく！
        // ２回呼び出されている
        this.fetchCategories();
    },
    methods: {
        fetchCategories() {
            axios.get('/api/v1/categories').then(res => {
                this.categories = res.data.categories;
            }).catch(error => {
                console.error('Error fetching categories:', error);
            });
        },


        // 子コンポーネントへのデータ引数
        createCategory() {
            this.selectedCategory = { name: '' };
            this.isNewCategory = true;
            this.catListChildDlg = true;
        },

        updateCategory(category) {
            this.selectedCategory = category;
            this.isNewCategory = false;
            this.catListChildDlg = true;
        },

        // 親コンポーネントで実行
        confirmNewCategory(category) {
            axios.post('/api/v1/categories', category).then(() => {
                this.fetchCategories();
                this.catListChildDlg = false;
            }).catch(error => {
                console.error('Error creating new category:', error);
            })
        },

        confirmUpdateCategory(category) {
            axios.put(`/api/v1/categories/${category.id}`, category).then(() => {
                this.fetchCategories();
                this.catListChildDlg = false;
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

        confirmCancel() {
            this.fetchCategories();
            this.catListChildDlg = false;
        },
    }
}
</script>
