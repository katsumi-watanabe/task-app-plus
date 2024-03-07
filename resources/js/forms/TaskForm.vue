<template>
  <v-main class="bg-indigo-lighten-5">
    <v-container>
      <v-card width="800px" style="overflow: scroll; margin: 0 auto">
        <v-card-title>
          <span class="text-h5">{{ getFormTitle }}</span>
          <v-spacer></v-spacer>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field label="タイトル" :rules="[rules.required]" v-model="currentTask.title"></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12">
                <v-select
                  label="カテゴリ"
                  :items="categories"
                  item-title="name"
                  item-value="id"
                  :rules="[rules.required]"
                  v-model="currentTask.category_id"
                >
                </v-select>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="6">
                <v-text-field label="期日" type="date" v-model="currentTask.due_date"> </v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12">
                <v-textarea label="内容" v-model="currentTask.description"></v-textarea>
              </v-col>
            </v-row>

            <v-card-text v-if="isNew == false">
              <div>
                <h3 class="text-center my-5" style="font-size: 2rem">タスクメモ一覧</h3>
              </div>
              <v-row class="bg-grey-lighten-4">
                <v-col cols="12">
                  <MemoList :task="task"> </MemoList>
                </v-col>
              </v-row>
            </v-card-text>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <router-link :to="{ name: 'Task' }" class="mr-3">
            <v-btn class="bg-grey-lighten-3"> CANCEL </v-btn>
          </router-link>
          <v-btn class="bg-light-blue" v-prevent-double-click @click="confirmTask"> OK </v-btn>
        </v-card-actions>
        <!-- 新規登録時、タスクメモは登録できない -->
      </v-card>
    </v-container>
  </v-main>
</template>
<script>
  import MemoList from '../lists/MemoList.vue';

  export default {
    components: {
      MemoList,
    },
    data() {
      return {
        title: '',
        due_date: '',
        category_id: '',
        description: '',
        currentTask: {},
        categories: [],
        rules: {
          required: value => !!value || 'Field is required',
        },
        isNew: true,
        task: {},
      };
    },
    created() {
      this.currentTask = _.clone(this.task);

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
      // URLのクエリパラメータからisNewを取得
      const isNew = this.$route.query.isNew;
    },
    emits: ['cancel', 'create', 'update'],
    computed: {
      getFormTitle() {
        return this.isNew ? 'タスク新規登録画面' : 'タスク編集画面';
      },
    },
    methods: {
      confirmTask() {
        if (this.isNew) {
            console.log(this.currentTask);
          this.confirmNewTask(this.currentTask);
        } else {
          this.confirmUpdateTask(this.currentTask);
        }
      },
      confirmNewTask(task) {
        this.isDisabled = true;
        axios
          .post('/api/v1/tasks', task)
          .then(() => {
            this.goToHomePage();
          })
          .catch(error => {
            console.error('Error creating new task:', error);
          })
          .finally(() => {
            this.isDisabled = false;
          });
      },

      confirmUpdateTask(task) {
        axios
          .put(`/api/v1/tasks/${task.id}`, task)
          .then(() => {
            this.goToHomePage();
          })
          .catch(error => {
            console.error('Error updating task:', error);
          });
      },
      goToHomePage() {
        this.$router.push({ path: '/' });
      },

    },
  };
</script>
