<template>
    <v-app class="d-block">

        <v-app-bar color="primary">
            <template v-slot:prepend>
                <v-btn class="bg-info">
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

            <v-container fluid>
                <p>{{ selected }}</p>
                <v-row>
                    <v-col cols="3">
                        <v-checkbox
                        v-model="selected"
                        label="未了"
                        value="unfinished"
                        ></v-checkbox>
                    </v-col>
                    <v-col cols="3">
                        <v-checkbox
                        v-model="selected"
                        label="完了"
                        value="completed"
                        ></v-checkbox>
                    </v-col>
                </v-row>
            </v-container>
            <v-select
                label="カテゴリ"
                :items="categories"
                item-title="name"
                item-value="id"
            >
            </v-select>
            <v-card
                class="mx-auto"
                color="grey-lighten-3"
            >
            <v-text-field
                density="compact"
                variant="solo"
                label="フリーワード検索"
                name="keyword_search"
                single-line
                hide-details
            ></v-text-field>
            </v-card>

            <v-btn type="submit" prepend-icon="$vuetify">
                検索
            </v-btn>
            <v-btn prepend-icon="$vuetify">
                リセット
            </v-btn>

                <v-table fixed-header height="1000px">
                    <thead>
                        <tr>
                            <th class="text-left">ID</th>
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
                            <td>{{ task.id }}</td>
                            <td>{{ task.title }}</td>
                            <td>{{ task.category.name }}</td>
                            <td>{{ task.description }}</td>
                            <td v-if="!!task.due_date">{{ dateFormat(task.due_date) }}</td>
                            <td v-else>-</td>
                            <td v-if="!!task.completed_at">{{ dateFormat(task.completed_at) }}</td>
                            <td v-else>-</td>
                            <td>{{ task.memos_count }}</td>
                            <td><UpdateButton :task="task" @editOnClick="handleEdit"></UpdateButton></td>
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
            selected: [],
            categories: [],
            keyword_search: [],
        };
    },
    mounted() {
        this.fetchTasks();

        axios.get('/api/v1/categories').then(res => {
            this.categories = res.data.categories;
        })
        .catch(error => {
            console.error('Error fetching categories:', error);
        });
    },
    methods: {
        dateFormat(date) {
            const dt = new Date(date);
            const y = dt.getFullYear();
            const m = (dt.getMonth() + 1).toString().padStart(2, '0');
            const d = dt.getDate().toString().padStart(2, '0');
            return `${y}/${m}/${d}`;
        },
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
        handleEdit() {
            this.fetchTasks();
        },
    },
};
</script>
