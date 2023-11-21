<template>
  <v-app class="d-block">
    <v-app-bar color="indigo">
      <template v-slot:prepend>
        <v-btn class="bg-info" @click="changeDisplay">
          <v-icon>mdi-filter</v-icon>
          フィルター
        </v-btn>
      </template>

      <v-app-bar-title> タスク管理一覧 </v-app-bar-title>

      <template v-slot:append>
        <CategoryList :categories="categories" @click="fetchCategories"></CategoryList>

        <v-btn class="bg-success white ml-8" @click="createTask">
          <v-icon> mdi-plus-circle-outline </v-icon>
          新規登録
        </v-btn>
      </template>
    </v-app-bar>
    <v-main class="bg-indigo-lighten-5">
      <v-container>
        <div v-if="displaySearchBox == true">
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
                name="keyword_search"
                v-model="keyword_search"
                hide-details="auto"
                @keyup.enter="fetchTasks"
              ></v-text-field>
            </v-col>
          </v-row>

          <v-row class="mb-3">
            <v-col cols="12">
              <v-btn prepend-icon="mdi-magnify" @click="fetchTasks" class="mr-5 bg-light-blue" type="submit"> 検索 </v-btn>
              <v-btn prepend-icon="mdi-refresh" @click="reset"> リセット </v-btn>
            </v-col>
          </v-row>
        </div>

        <v-table fixed-header height="auto" style="position: relative">
          <thead>
            <tr>
              <th class="text-left th_hover">ステータス<v-icon class="ml-1 mb-1"></v-icon></th>
              <th class="text-left th_hover" @click="sortble('title')">
                タイトル<v-icon class="ml-1 mb-1" :class="getIconStyle('title')">{{ getSortIcon('title') }}</v-icon>
              </th>
              <th class="text-left th_hover" @click="sortble('category_id')">
                カテゴリ<v-icon class="ml-1 mb-1" :class="getIconStyle('category_id')">{{
                  getSortIcon('category_id')
                }}</v-icon>
              </th>
              <th class="text-left">内容</th>
              <th class="text-left th_hover" @click="sortble('due_date')">
                期日<v-icon class="ml-1 mb-1" :class="getIconStyle('due_date')">{{ getSortIcon('due_date') }}</v-icon>
              </th>
              <th class="text-left th_hover" @click="sortble('completed_at')">
                完了日<v-icon class="ml-1 mb-1" :class="getIconStyle('completed_at')">{{
                  getSortIcon('completed_at')
                }}</v-icon>
              </th>
              <th class="text-left th_hover" @click="sortble('memos_count')">
                メモ件数<v-icon class="ml-1 mb-1" :class="getIconStyle('memos_count')">{{
                  getSortIcon('memos_count')
                }}</v-icon>
              </th>
              <th class="text-left">編集</th>
              <th class="text-left">削除</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="task in tasks" :key="task.id" :class="{ 'bg-lightgray': task.completed_at }">
              <td>
                <v-checkbox
                  :modelValue="!!task.completed_at"
                  @update:modelValue="
                    e => {
                      changeTaskCompletionState(e, task);
                    }
                  "
                  hide-details
                >
                </v-checkbox>
              </td>
              <td>{{ !!task.title ? task.title : '' }}</td>
              <td>{{ !!task.category ? task.category.name : '' }}</td>
              <td>{{ !!task.description ? task.description : '' }}</td>
              <td>{{ !!task.due_date ? dateFormat(task.due_date) : '-' }}</td>
              <td>{{ !!task.completed_at ? dateFormat(task.completed_at) : '-' }}</td>
              <td>{{ task.memos_count }}</td>
              <td>
                <v-btn class="bg-success white" @click="updateTask(task)">
                  <v-icon>mdi-table-edit</v-icon>
                </v-btn>
              </td>
              <td>
                <DeleteButton :task_id="task.id" @delete="confirmDelete"></DeleteButton>
              </td>
            </tr>
          </tbody>
          <div class="success_alert_message">
            <v-alert v-model="alert" color="success" icon="$success" :text="taskMessage">
              <div class="ml-3 px-3" role="button" @click="cancel(messageTaskId)">
                <v-icon color="#fff"> mdi mdi-keyboard-return </v-icon>
              </div>
            </v-alert>
          </div>
        </v-table>
        <div class="d-flex justify-end mt-2 mb-6">
          <v-pagination v-model="page" :length="length" :total-visible="2" @click="pageChange(page)"></v-pagination>
        </div>
        <div>
          <v-row justify="center">
            <v-dialog v-model="taskFormDialog" width="auto">
              <TaskForm
                :isNew="isNewTask"
                :task="selectedTask"
                :categories="categories"
                @cancel="this.taskFormDialog = false"
                @create="confirmNewTask"
                @update="confirmUpdateTask"
              ></TaskForm>
            </v-dialog>
          </v-row>
        </div>
      </v-container>
    </v-main>

    <v-footer color="indigo" app> Footer </v-footer>
  </v-app>
</template>

<style>
  .bg-lightgray {
    background-color: #c5cae9;
  }
  .success_alert_message {
    width: '300px';
    height: '75px';
    position: absolute;
    top: 80%;
    left: 50%;
    transform: translate(-50%, -50%);
    -webkit-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
  }
  .success_alert_message div {
    display: flex;
  }
  .th_hover:hover {
    cursor: pointer;
  }
  .th_hover:hover i {
    opacity: 1;
    cursor: pointer;
  }
</style>

<script>
  import DeleteButton from './buttons/DeleteButton.vue';
  import TaskForm from './forms/TaskForm.vue';
  import CategoryList from './lists/CategoryList.vue';
  import { dateFormat } from './dateFormat.js';

  export default {
    components: {
      DeleteButton,
      CategoryList,
      TaskForm,
    },
    data() {
      return {
        tasks: [],
        categories: [],

        // ページネーション
        page: 1,
        pageSize: 10,
        length: 0,

        taskFormDialog: false,
        isNewTask: true,
        selectedTask: '',

        // 検索関連
        selectedStatus: [],
        keyword_search: '',
        selectedCategory: [],
        statusItems: ['完了', '未完了'],
        displaySearchBox: false,

        // 並び替え関連
        sortColumnStatus: false,
        sortColumnName: '',

        // 多重送信防止フラグ
        isDisabled: false,
        // alert関連
        alert: false,
        alertTimer: null,
        taskMessage: '',
        messageTaskId: {},
      };
    },
    mounted() {
      this.fetchCategories();
      this.fetchTasks();
    },

    methods: {
      dateFormat,
      // 並び替え
      sortble(columnName) {
        // ソート中のデータとソートしたいデータが同じ
        // ソート中のソートステータスがtrueの時反転する
        if (columnName == this.sortColumnName || this.sortColumnStatus) {
          this.sortColumnStatus = !this.sortColumnStatus;
        }
        this.sortColumnName = columnName;
        this.fetchTasks();
      },

      getSortIcon(columnName) {
        return this.sortColumnName == columnName && this.sortColumnStatus == false ? 'mdi-arrow-down' : 'mdi-arrow-up';
      },

      getIconStyle(columnName) {
        return this.sortColumnName == columnName ? 'text-black' : 'text-grey-lighten-1 opacity-0';
      },

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

      fetchCategories() {
        axios
          .get('/api/v1/categories')
          .then(res => {
            this.categories = res.data.categories;
          })
          .catch(error => {
            console.error('Error fetching categories:', error);
          });
      },

      createTask() {
        this.selectedTask = { title: '', description: '', due_date: '', category_id: '' };
        this.isNewTask = true;
        this.taskFormDialog = true;
        this.fetchCategories();
      },

      updateTask(task) {
        this.selectedTask = task;
        this.isNewTask = false;
        this.taskFormDialog = true;
        this.fetchCategories();
      },

      confirmNewTask(task) {
        this.isDisabled = true;
        axios
          .post('/api/v1/tasks', task)
          .then(() => {
            this.fetchTasks();
            this.taskFormDialog = false;
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
            this.fetchTasks();
            this.taskFormDialog = false;
          })
          .catch(error => {
            console.error('Error updating task:', error);
          });
      },

      confirmDelete(task_id) {
        this.isDisabled = true;
        axios
          .delete(`/api/v1/tasks/${task_id}`)
          .then(() => {
            this.fetchTasks();
          })
          .catch(error => {
            console.error('Error updating task:', error);
          })
          .finally(() => {
            this.isDisabled = false;
          });
      },

      // 検索ボックス表示切替
      changeDisplay() {
        this.displaySearchBox = !this.displaySearchBox;
      },

      reset() {
        this.selectedStatus = [];
        this.keyword_search = '';
        this.selectedCategory = [];
        this.fetchTasks();
      },

      // タスク完了ステータス
      complete(id) {
        axios.put(`/api/v1/tasks/${id}/complete`).then(res => {
          this.fetchTasks();
          this.alert = true;
          if (res.data.task) {
            this.taskMessage = res.data.task.title + '　完了';
            this.messageTaskId = res.data.task.id;
          } else {
            this.taskMessage = res.data.message;
          }
          this.alertMessageTime();
        });
      },
      cancel(id) {
        axios.put(`/api/v1/tasks/${id}/cancel`).then(res => {
          this.fetchTasks();
          this.alert = false;
        });
      },

      // アラートメッセージ表示時間設定
      alertMessageTime() {
        clearTimeout(this.alertTimer);

        // 新しいタイマーを設定
        this.alertTimer = setTimeout(() => {
          this.alert = false;
        }, 3000);
      },

      // ページネーション
      pageCalculation(tasks) {
        this.length = Math.ceil(tasks.length / this.pageSize);
        this.tasks = tasks.slice(this.pageSize * (this.page - 1), this.pageSize * this.page);
      },
      pageChange(pageNumber) {
        this.page = pageNumber;
        this.fetchTasks();
      },

      // 完了・未完了切り替え
      changeTaskCompletionState(complete_status, task) {
        complete_status ? this.complete(task.id) : this.cancel(task.id);
      },
    },
  };
</script>
