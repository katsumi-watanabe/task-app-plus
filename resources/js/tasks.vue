<template>
    <h2 class="text-center">タスク管理アプリ一覧</h2>
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

</template>
<script>
import DeleteButton from './buttons/DeleteButton.vue';
import UpdateButton from './buttons/UpdateButton.vue';

export default {
    components: {
        DeleteButton,
        UpdateButton,
    },
    data() {
        return {
            tasks: [],
        };
    },
    mounted() {
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
        fetchTasks() {
            axios.get('/api/v1/tasks').then(res => {
                this.tasks = res.data.tasks;
            })
            .catch(error => {
                console.error('Error fetching tasks:', error);
            });
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
