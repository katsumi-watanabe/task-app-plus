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
        <v-menu
          bottom
          offset-y
        >
          <v-list>
            <v-list-item
              v-for="(item, i) in items"
              :key="i"
              @click="() => {}"
            >
              <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
        <v-dialog
          v-model="CatListDlg"
          fullscreen
          hide-overlay
          transition="dialog-bottom-transition"
          scrollable
        >
          <v-card tile>
            <v-toolbar
              flat
              dark
              color="primary"
            >
              <v-btn
                icon
                dark
                @click="dialog = false"
              >
                <v-icon>mdi-close</v-icon>
              </v-btn>
            </v-toolbar>
            <div style="flex: 1 1 auto;"></div>
          </v-card>
        </v-dialog>

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
                                <v-btn
                                color="red"
                                >
                                <v-icon>mdi-delete</v-icon>
                                </v-btn>
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
          v-model="CatListChildDlg"
          max-width="500px"
        >

        <CategoryForm
            @cancel="CatListChildDlg = false"
            @ok="confirmNewCategory"
            :isNew="isNewCategory"
            :category="selectedCategory"
        >
        </CategoryForm>
        </v-dialog>

      </v-row>
    </div>
</template>
<script>
import CategoryForm from '../forms/CategoryForm.vue';
export default {
    components: {
        CategoryForm,
    },
    data () {
    return {
        name: '',
        CatListDlg: false,
        CatListParDlg: false,
        CatListChildDlg: false,
        rules: {
            required: value => !!value || 'Field is required',
        },

        selectedCategory: null,
        isNewCategory: true,
    }
    },
    mounted() {
        axios.get('/api/v1/categories').then(res => {
            this.categories = res.data.categories;
        })
        .catch(error => {
            console.error('Error fetching categories:', error);
        });
    },
    methods: {
        fetchCategories() {
            axios.get('/api/v1/categories').then(res => {
                this.categories = res.data.categories;
            }).catch(error => {
                console.error('Error fetching categories:', error);
            });
        },

        confirmNewCategory(e) {
            const params = {
                name: e.name,
            };
            axios.post(`/api/v1/categories`, params).then(res => {
                this.categories = res.data.categories;
                this.CatListChildDlg = false;
            })
        },
        createCategory() {
            this.selectedCategory = '';
            this.CatListChildDlg = true;
            this.isNewCategory = true;
        },

        updateCategory(category) {
            this.selectedCategory = category;
            this.CatListChildDlg = true;
            this.isNewCategory = false;
        }
    }
}
</script>
