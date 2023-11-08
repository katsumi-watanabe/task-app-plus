<template>
    <v-row justify="center">
      <v-dialog
        v-model="new_dialog"
        persistent
        width="1024"
      >
        <template v-slot:activator="{ props }">
            <v-btn
                class="bg-success white ml-3"
                color="white"
                v-bind="props"
                @click="getCategories"
            >
            <v-icon>
                mdi-plus-circle-outline
            </v-icon>
                新規登録
            </v-btn>

        </template>
        <v-card>
          <v-card-title>
            <span class="text-h5">新規登録</span>
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
                    v-model="title"
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
                    v-model="category_id"
                >
                </v-select>

                </v-col>
                <v-col cols="6">

                  <v-text-field
                    label="期日"
                    type="date"
                    v-model="due_date"
                  >
                </v-text-field>
                </v-col>
                <v-col cols="12">
                    <v-textarea
                    label="内容"
                    v-model="description"
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
              @click="new_dialog = false"
            >
              Close
            </v-btn>
            <v-btn
              variant="text"
              @click="confirmNew()"
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
            new_dialog: false,
            categories: [],
            title: '',
            due_date: '',
            category_id: '',
            description: '',
        };
    },
    mounted() {
        this.getCategories();
    },
    emits: ['newOnClick'],

    methods: {
        getCategories() {
            axios.get('/api/v1/categories').then(res => {
                this.categories = res.data.categories;
            }).catch(error => {
                console.error('Error fetching categories:', error);
            });
        },
        confirmNew() {
            const newTask = {
                title: this.title,
                description: this.description,
                due_date: this.due_date,
                category_id: this.category_id,
            };
            console.log(newTask);
            axios.post(`/api/v1/tasks/`, newTask).then(res => {
                this.$emit('newOnClick', res.data.tasks);
                this.new_dialog = false;
            })
        },
    },
};
</script>
