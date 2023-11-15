<template>
    <v-app class="d-block">

        <v-app-bar color="indigo">
            <template v-slot:prepend>
                <v-btn class="bg-info" @click="changeDisplay">
                    <v-icon>mdi-filter</v-icon>
                    フィルター
                </v-btn>
            </template>

            <v-app-bar-title>
                タスク管理一覧
            </v-app-bar-title>

            <template v-slot:append>
                <CategoryList
                    :categories=categories
                    @click="fetchCategories"
                ></CategoryList>

                <v-btn
                    class="bg-success white ml-8"
                    @click="createTask"
                >
                <v-icon>
                    mdi-plus-circle-outline
                </v-icon>
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
                            ></v-text-field>
                    </v-col>
                    </v-row>

                    <v-row class="mb-3">
                        <v-col cols="12">
                            <v-btn
                                prepend-icon="mdi-magnify"
                                @click="fetchTasks"
                                class="mr-5 bg-light-blue"
                            >
                                検索
                            </v-btn>
                            <v-btn
                                prepend-icon="mdi-refresh"
                                @click="reset"
                            >
                                リセット
                            </v-btn>
                        </v-col>
                    </v-row>
                </div>
                <v-table fixed-header height="450px">
                    <thead>
                        <tr>
                            <th class="text-left">ステータス</th>
                            <th class="text-left">タイトル</th>
                            <th class="text-left">カテゴリ</th>
                            <th class="text-left">内容</th>
                            <th class="text-left">期日</th>
                            <th class="text-left">完了日</th>
                            <th class="text-left">メモ件数</th>
                            <th class="text-left">編集</th>
                            <th class="text-left">削除</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="task in tasks" :key="task.id" :class="{'bg-lightgray': task.completed_at}">
                            <td>
                                <!-- ここ確認 -->
                                <v-checkbox
                                    v-if="!task.completed_at"
                                    @click="complete(task.id)"
                                    hide-details
                                ></v-checkbox>
                                <v-checkbox
                                    v-else
                                    :model-value="true"
                                    class="success"
                                    @click="cancel(task.id)"
                                    hide-details
                                ></v-checkbox>
                            </td>
                            <td>{{ !!task.title ? task.title : '' }}</td>
                            <td>{{ !!task.category ? task.category.name : '' }}</td>
                            <td>{{ !!task.description ? task.description : '' }}</td>
                            <td>{{ !!task.due_date ? dateFormat(task.due_date) : '-' }}</td>
                            <td>{{ !!task.completed_at ? dateFormat(task.completed_at) : '-' }}</td>
                            <td>{{ task.memos_count }}</td>
                            <td>
                                <v-btn
                                    class="bg-success white"
                                    @click="updateTask(task)"
                                >
                                    <v-icon>mdi-table-edit</v-icon>
                                </v-btn>
                            </td>
                            <!-- キャンセル不要？ -->
                            <!-- 1回以上削除がクリックできてしまう→一回しか押せないような処理設定が必要？ -->
                            <td>
                                <DeleteButton
                                    :task="task"
                                    @delete="confirmDelete"
                                ></DeleteButton>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
                <div class="d-flex justify-end mt-2 mb-6">
                    <v-pagination
                        v-model="page"
                        :length="length"
                        @click ="pageChange(page)"
                    >
                    </v-pagination>
                </div>
                <div>
                <v-row
                    justify="center"
                >
                <v-dialog
                    v-model="taskFormDialog"
                    width="auto"
                >
                    <TaskForm
                        :isNew="isNewTask"
                        :task="selectedTask"
                        :categories="categories"
                        :formTitle="formTitle"
                        @cancel="confirmCancel"
                        @create="confirmNewTask"
                        @update="confirmUpdateTask"
                    ></TaskForm>
                </v-dialog>
                </v-row>
            </div>
            </v-container>
        </v-main>

        <v-bottom-navigation>
            Button Navigation
        </v-bottom-navigation>

        <v-footer color="indigo" app>
            Footer
        </v-footer>

    </v-app>
</template>

<style>
.bg-lightgray {
    background-color: #C5CAE9;
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
            length:0,

            taskFormDialog: false,
            formTitle: '',
            isNewTask: true,
            selectedTask: '',

            // 検索関連
            selectedStatus: [],
            keyword_search: '',
            selectedCategory: [],
            statusItems: ['完了', '未完了'],
            displaySearchBox: false,
        };
    },
    mounted() {
        this.fetchCategories();
        this.fetchTasks();
    },
    methods: {
        dateFormat,
        fetchTasks() {
            const selectedStatus = this.selectedStatus;
            const keyword = this.keyword_search;
            const category = this.selectedCategory;
            axios.get('/api/v1/tasks', {
                params: {
                status: selectedStatus,
                keyword: keyword,
                category: category,
                }
            }).then(res => {
                this.tasks = res.data.tasks;
                this.pageCalculation(this.tasks);
            }).catch(error => {
                console.error('Error fetching tasks:', error);
            });
        },

        fetchCategories() {
            axios.get('/api/v1/categories').then(res => {
                this.categories = res.data.categories;
            }).catch(error => {
                console.error('Error fetching categories:', error);
            });
        },

        createTask() {
            this.selectedTask = { title: '', description: '', due_date: '', category_id: '' };
            this.formTitle = 'タスク新規登録画面';
            this.isNewTask = true;
            this.taskFormDialog = true;
        },

        updateTask(task) {
            this.selectedTask = task;
            this.formTitle = 'タスク編集画面';
            this.isNewTask = false;
            this.taskFormDialog = true;
        },

        confirmNewTask(task) {
            axios.post('/api/v1/tasks', task).then(() => {
                this.fetchTasks();
                this.taskFormDialog = false;
            }).catch(error => {
                console.error('Error creating new task:', error);
            })
        },

        confirmUpdateTask(task) {
            axios.put(`/api/v1/tasks/${task.id}`, task).then(() => {
                this.fetchTasks();
                this.taskFormDialog = false;
            }).catch(error => {
                console.error('Error updating task:', error);
            })
        },
        // コピーしたものを編集する→OKで反映
        confirmCancel() {
            this.fetchTasks();
            this.taskFormDialog = false;
        },

        confirmDelete(task) {
            axios.delete(`/api/v1/tasks/${task.id}`).then(() => {
                this.fetchTasks();
            }).catch(error => {
                console.error('Error updating task:', error);
            })
        },

        // 検索ボックス表示切替
        changeDisplay() {
            this.displaySearchBox = !this.displaySearchBox;
        },

        // まとめれる！！！
        reset() {
            this.selectedStatus = [];
            this.keyword_search = "";
            this.selectedCategory = [];
            this.fetchTasks();
        },

        // タスク完了ステータス
        complete(id) {
            axios.put(`/api/v1/tasks/${id}/complete`).then(res => {
                this.fetchTasks();
            })
        },
        cancel(id) {
            axios.put(`/api/v1/tasks/${id}/cancel`).then(res => {
                this.fetchTasks();
            })
        },

        // ページネーション
        pageCalculation(tasks) {
            this.length = Math.ceil(tasks.length/this.pageSize);
            this.tasks = tasks.slice(this.pageSize*(this.page -1), this.pageSize*(this.page));
        },
        pageChange(pageNumber) {
            this.page = pageNumber;
            this.fetchTasks();
        }

    },
};
</script>
