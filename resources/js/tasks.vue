<template>
    <v-table fixed-header height="1800px">
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
                <td>{{ task.due_date }}</td>
                <td>{{ task.completed_at }}</td>
                <td>{{ task.memo_count }}</td>
                <td><v-icon>mdi-table-edit</v-icon></td>
                <td><v-icon>mdi-delete</v-icon></td>

            </tr>
        </tbody>
    </v-table>
</template>
<script>
export default {
    data() {
        return {
            tasks: [],
        };
    },
    mounted() {
        axios.get('/api/v1/tasks').then(res => {
        console.log(res.data);
        this.tasks = res.data.tasks;
    })},
};
</script>
