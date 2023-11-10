<template>
    <v-app class="d-block">

        <v-app-bar color="primary">
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
                <CategoryList></CategoryList>

                <v-btn
                    class="bg-success white ml-8"
                    @click="createTask"
                >
                <v-icon>
                    mdi-plus-circle-outline
                </v-icon>
                    新規登録
                </v-btn>
                <!-- <NewButton :categories="categories" @newOnClick="handleNew" class="mx-3"></NewButton> -->
            </template>
        </v-app-bar>

        <v-main>
            <v-container>
                <div v-if="displaySearchBox == true">
                    <v-row>
                        <v-col cols="6">
                        <v-select
                            label="完了ステータス"
                            :items="statusItems"
                            v-model="selectedStatus"
                        >
                        </v-select>
                        </v-col>

                        <v-col cols="6">
                            <v-select
                                label="カテゴリ"
                                :items="categories"
                                item-title="name"
                                item-value="id"
                                name="category"
                                chips
                                multiple
                                v-model="selectedCategory"
                            >
                            </v-select>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12">
                            <v-text-field
                                label="フリーワード検索"
                                name="keyword_search"
                                v-model="keyword_search"
                            ></v-text-field>
                    </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12">
                            <v-btn
                                prepend-icon="mdi-magnify"
                                @click="search"
                                class="mr-5"
                                color="info"
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
                <v-table height="450px">
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
                        <tr v-for="task in displayTasks" :key="task.id" :class="{'bg-lightgray': task.completed_at}">
                            <td>
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
                            <td>{{ task.title }}</td>
                            <td>{{ task.category.name }}</td>
                            <td>{{ task.description }}</td>
                            <td v-if="!!task.due_date">{{ dateFormat(task.due_date) }}</td>
                            <td v-else>-</td>
                            <td v-if="!!task.completed_at">{{ dateFormat(task.completed_at) }}</td>
                            <td v-else>-</td>
                            <td>{{ task.memos_count }}</td>
                            <td>
                                <v-btn
                                    class="bg-success white ml-8"
                                    @click="updateTask(task)"
                                >
                                    <v-icon>mdi-table-edit</v-icon>
                                </v-btn>
                                <!-- <UpdateButton :task="task" :categories="categories" @updateOnClick="handleUpdate"></UpdateButton> -->
                            </td>
                            <td><DeleteButton :task="task" @cancel="deleteDialog = false" @delete="confirmDelete"></DeleteButton></td>
                        </tr>
                    </tbody>
                </v-table>
                <v-content>
                    <div class="d-flex justify-end mt-2 mb-6">
                        <v-pagination
                            v-model="page"
                            :length="length"
                            @click ="pageChange(page)"
                        >
                        </v-pagination>
                    </div>
                </v-content>

                <v-dialog
                    v-model="taskFormDialog"
                    width="1024"
                >
                    <TaskForm
                        :isNew="isNewTask"
                        :task="selectedTask"
                        :categories="categories"
                        @cancel="confirmCancel"
                        @create="confirmNewTask"
                        @update="confirmUpdateTask"
                    ></TaskForm>
                </v-dialog>
            </v-container>
        </v-main>

        <v-bottom-navigation>
            Button Navigation
        </v-bottom-navigation>

        <v-footer color="primary" app>
            Footer
        </v-footer>

    </v-app>
</template>

<style>
.bg-lightgray {
    background-color: #F5F5F5;
}
</style>

<script>
import DeleteButton from './buttons/DeleteButton.vue';
import UpdateButton from './buttons/UpdateButton.vue';
import NewButton from './buttons/NewButton.vue';
import TaskForm from './forms/TaskForm.vue';
import CategoryList from './lists/CategoryList.vue';

export default {
    components: {
        DeleteButton,
        UpdateButton,
        NewButton,
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
            displayTasks: [],

            deleteDialog: false,
            selectedTask: '',
            isNewTask: true,
            taskFormDialog: false,

            // 検索関連
            selectedStatus: [],
            keyword_search: '',
            selectedCategory: [],
            statusItems: ['完了', '未完了'],
            displaySearchBox: false,
        };
    },
    mounted() {
        axios.get('/api/v1/categories').then(res => {
            this.categories = res.data.categories;
        })
        .catch(error => {
            console.error('Error fetching categories:', error);
        });
        this.fetchTasks();

    },
    methods: {
        dateFormat(date) {
            const dt = new Date(date);
            const y = dt.getFullYear();
            const m = (dt.getMonth() + 1).toString().padStart(2, '0');
            const d = dt.getDate().toString().padStart(2, '0');
            return `${y}/${m}/${d}`;
        },

        // タスク一覧取得
        fetchTasks() {
            axios.get('/api/v1/tasks').then(res => {
                this.tasks = res.data.tasks;
                this.pageCalculation(this.tasks);
            })
            .catch(error => {
                console.error('Error fetching tasks:', error);
            });
        },

        // 子コンポーネントへのデータ引数
        createTask() {
            this.selectedTask = { title: '', description: '', due_date: '', category_id: '' };
            this.isNewTask = true;
            this.taskFormDialog = true;
        },

        updateTask(task) {
            console.log(task);
            this.selectedTask = task;
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
        confirmCancel() {
            this.fetchTasks();
            this.taskFormDialog = false;
        },

        confirmDelete(task) {
            const params = {
                title: this.title,
                description: this.description,
                due_date: this.due_date,
                category_id: this.category_id,
            }
            axios.delete(`/api/v1/tasks/${task.id}`, params).then(() => {
                this.fetchTasks();
                this.deleteDialog = false;
            }).catch(error => {
                console.error('Error updating task:', error);
            })
        },

        // 検索ボックス表示切替
        changeDisplay() {
            this.displaySearchBox == true ? this.displaySearchBox = false : this.displaySearchBox = true;
        },

        // 検索関連
        search() {
            const selectedStatus = this.selectedStatus;
            const keyword = this.keyword_search;
            const category = this.selectedCategory;
            axios.get('/api/v1/tasks', {
                params: {
                status: selectedStatus,
                keyword: keyword,
                category: category,
                }
            })
            .then(res => {
                this.displayTasks = res.data.tasks;
                this.pageCalculation(this.displayTasks);
            })
            .catch(error => {
                console.error(error);
            });
        },
        reset() {
            this.selectedStatus = [];
            this.keyword_search = "";
            this.selectedCategory = [];
            this.search();
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
            (Math.ceil(tasks.length/this.pageSize));
            this.length = Math.ceil(tasks.length/this.pageSize);
            this.displayTasks = tasks.slice(this.pageSize*(this.page -1), this.pageSize*(this.page));
        },
        pageChange(pageNumber) {
            this.page = pageNumber;
            this.search();
        }

    },
};
</script>
