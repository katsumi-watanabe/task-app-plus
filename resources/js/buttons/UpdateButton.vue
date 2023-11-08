<template>
    <v-row justify="center">
      <v-dialog
        v-model="edit_dialog"
        persistent
        width="1024"
      >
        <template v-slot:activator="{ props }">
          <v-btn
            color="success"
            v-bind="props"
            @click="getCategories"
          >
          <v-icon>mdi-table-edit</v-icon>
          </v-btn>

        </template>
        <v-card>
          <v-card-title>
            <span class="text-h5">編集</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col
                  cols="12"
                  sm="12"
                  md="12"
                >
                <v-text-field
                    label="タイトル"
                    persistent-hint
                    required
                    v-model="task.title"
                ></v-text-field>
                </v-col>
                <v-col
                  cols="12"
                  sm="12"
                  md="12"
                >
                <v-select
                    label="カテゴリ"
                    :items="categories"
                    item-title="name"
                    item-value="id"
                    required
                    v-model="task.category_id"
                >
                </v-select>

                </v-col>
                <v-col cols="6">

                  <v-text-field
                    label="期日"
                    type="date"
                    v-model="task.due_date"
                  >
                </v-text-field>
                </v-col>
                <v-col cols="12">
                    <v-textarea
                    label="内容"
                    v-model="task.description"
                    ></v-textarea>
                </v-col>
                <v-col
                  cols="12"
                  sm="12"
                >
                </v-col>

              </v-row>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              variant="text"
              @click="cancelEdit()"
            >
              Close
            </v-btn>
            <v-btn
              variant="text"
              @click="confirmEdit()"
            >
              Save
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>
</template>
<script>
export default {
    data() {
        return {
            edit_dialog: false,
            categories: [],
        };
    },
    mounted() {
        this.getCategories();
    },
    emits: ['editOnClick'],
    props: {
        task: {
            type: Object,
            required: true
        }
    },
    methods: {
        getCategories() {
            axios.get('/api/v1/categories').then(res => {
                this.categories = res.data.categories;
            }).catch(error => {
                console.error('Error fetching categories:', error);
            });
        },
        confirmEdit() {
            const updatedTask = {
                title: this.task.title,
                description: this.task.description,
                due_date: this.task.due_date,
                category_id: this.task.category_id,
            };
            axios.put(`/api/v1/tasks/${this.task.id}`, updatedTask).then(res => {
                this.$emit('editOnClick', this.task.id);
                this.edit_dialog = false;
            })
        },
        // リアクティブのためキャンセル時に再度データを取得
        cancelEdit() {
            this.$emit('editOnClick', this.task.id);
            this.edit_dialog = false;
        }
    },
};
</script>
