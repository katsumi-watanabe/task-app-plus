<template>
    <div class="text-right mb-3">
        <v-btn class="bg-success white" @click="createTaskMemo">
            <v-icon> mdi-plus-circle-outline </v-icon>
            新規作成
        </v-btn>
    </div>
    <v-table fixed-header height="auto">
        <thead flex>
            <tr>
                <th class="text-left">日付</th>
                <th class="text-left">内容</th>
                <th class="text-left">編集</th>
                <th class="text-left">削除</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="memo in memos" :key="memo.id">
                <td>{{ dateFormat(memo.created_at) }}</td>
                <td>{{ memo.content }}</td>
                <td>
                    <v-btn
                        class="bg-success white"
                        @click="updateTaskMemo(memo)"
                    >
                        <v-icon>mdi-table-edit</v-icon>
                    </v-btn>
                </td>
                <td>
                    <TaskMemoDeleteButton @delete="confirmDelete" :memo_id="memo.id">
                    </TaskMemoDeleteButton>
                </td>
            </tr>
        </tbody>
    </v-table>

    <v-dialog v-model="taskMemoListChildDlg" max-width="500px">
        <TaskMemoForm
            :isNew="isNewTaskMemo"
            :taskMemo="selectedTaskMemo"
            @cancel="this.taskMemoListChildDlg = false"
            @create="confirmNewTaskMemo"
            @update="confirmUpdateTaskMemo"
        >
        </TaskMemoForm>
    </v-dialog>
</template>
<script>
import TaskMemoForm from "../forms/TaskMemoForm.vue";
import TaskMemoDeleteButton from "../buttons/TaskMemoDeleteButton.vue";
import { dateFormat } from "../dateFormat";

export default {
    components: {
        TaskMemoForm,
        TaskMemoDeleteButton,
    },
    data() {
        return {
            memos: [],
            taskMemoListChildDlg: false,
            isNewTaskMemo: false,
            selectedTaskMemo: {},
            // 多重送信防止フラグ
            isDisabled: false,
        };
    },
    props: {
        task: {
            type: Object,
        },
    },
    mounted() {
        this.fetchTaskMemos();
    },
    methods: {
        dateFormat,

        fetchTaskMemos() {
            axios.get(`/api/v1/tasks/${this.task.id}/memos`).then((res) => {
                this.memos = res.data.memos;
            }).catch((error) => {
                console.error("Error fetching memos:", error);
            });
        },
        createTaskMemo() {
            this.selectedTaskMemo = { content: "" };
            this.taskMemoListChildDlg = true;
            this.isNewTaskMemo = true;
        },
        updateTaskMemo(memo) {
            this.selectedTaskMemo = memo;
            this.taskMemoListChildDlg = true;
            this.isNewTaskMemo = false;
        },

        confirmNewTaskMemo(memo) {
            this.isDisabled = true;
            axios.post(`/api/v1/tasks/${this.task.id}/memos`, memo).then(() => {
                this.fetchTaskMemos();
                this.taskMemoListChildDlg = false;
            }).catch((error) => {
                console.error("Error creating new task-memo:", error);
            }).finally(() => {
                this.isDisabled = false;
            });
        },
        confirmUpdateTaskMemo(memo) {
            axios.put(`/api/v1/tasks/${this.task.id}/memos/${memo.id}`, memo).then(() => {
                this.fetchTaskMemos();
                this.taskMemoListChildDlg = false;
            }).catch((error) => {
                console.error("Error creating new task-memo:", error);
            });
        },

        confirmDelete(memo_id) {
            this.isDisabled = true;
            axios.delete(`/api/v1/tasks/${this.task.id}/memos/${memo_id}`).then(() => {
                this.fetchTaskMemos();
            }).catch((error) => {
                console.error("Error updating task-memo:", error);
            }).finally(() => {
                this.isDisabled = false;
            });
        },
    },
};
</script>
