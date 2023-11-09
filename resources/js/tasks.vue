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
                <v-btn class="bg-grey white">
                    カテゴリ一覧
                </v-btn>

                <NewButton @newOnClick="handleNew" class="mx-3"></NewButton>
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
                <v-table fixed-header height="1000px" class="mt-5">
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
                        <tr v-for="task in tasks" :key="task.id">
                            <td>
                                <v-checkbox
                                    v-if="!task.completed_at"
                                    @click="complete(task.id)"
                                ></v-checkbox>
                                <v-checkbox
                                    v-else
                                    :model-value="true"
                                    @click="cancel(task.id)"
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
                            <td><UpdateButton :task="task" @updateOnClick="handleUpdate"></UpdateButton></td>
                            <td><DeleteButton :id="task.id" @deleteOnClick="handleDelete"></DeleteButton></td>
                        </tr>
                    </tbody>
                </v-table>
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
<script>
import DeleteButton from './buttons/DeleteButton.vue';
import UpdateButton from './buttons/UpdateButton.vue';
import NewButton from './buttons/NewButton.vue';

export default {
    components: {
        DeleteButton,
        UpdateButton,
        NewButton,
    },
    data() {
        return {
            tasks: [],
            categories: [],

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
            })
            .catch(error => {
                console.error('Error fetching tasks:', error);
            });
        },
        handleNew() {
            this.fetchTasks();
        },
        handleDelete() {
            this.fetchTasks();
        },
        handleUpdate() {
            this.fetchTasks();
        },
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
                this.tasks = res.data.tasks;
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

    },
};
</script>
