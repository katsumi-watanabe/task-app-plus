<template>
    <div class="text-right mb-3">
        <v-btn
            class="bg-success white"
            @click="createTaskMemo"
        >
            <v-icon>
                mdi-plus-circle-outline
            </v-icon>
            新規作成
        </v-btn>
    </div>
    <v-table fixed-header height="400px">
        <thead flex>
            <tr>
                <th class="text-left">
                    日付
                </th>
                <th class="text-left">
                    内容
                </th>
                <th class="text-left">
                    編集
                </th>
                <th class="text-left">
                    削除
                </th>
            </tr>
        </thead>
        <tbody>
            <tr
                v-for="memo in memos"
                :key="memo.id"
            >
                <td>{{ dateFormat(memo.created_at) }}</td>
                <td>{{ memo.content }}</td>
                <td>
                    <v-btn
                        class="bg-success white ml-8"
                        @click="updateTaskMemo(memo)"
                    >
                        <v-icon>mdi-table-edit</v-icon>
                    </v-btn></td>
                <td>
                    <v-btn
                        color="red"
                    >
                    <v-icon>mdi-delete</v-icon>
                    </v-btn>
                </td>
            </tr>
        </tbody>
    </v-table>

    <v-dialog
        v-model="taskMemoListChildDlg"
        max-width="500px"
    >
        <TaskMemoForm
            @cancel="confirmCancelTaskMemo"
            :isNew="isNewTaskMemo"
            :taskMemo="selectedTaskMemo"
            :task="task"
            @create="confirmNewTaskMemo"
            @update="confirmUpdateTaskMemo"
        >
        </TaskMemoForm>

    </v-dialog>

</template>
<script>
import TaskMemoForm from '../forms/TaskMemoForm.vue'
import { dateFormat } from '../dateFormat'

export default {
    components: {
        TaskMemoForm,
    },
    data() {
        return {
            memos: '',
            content: '',
            taskMemoListChildDlg: false,
            selectedTaskMemo: '',
            isNewTaskMemo: false,
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
            axios.get(`/api/v1/tasks/${this.task.id}/memos`).then(res => {
                this.memos = res.data.memos;
            }).catch(error => {
                console.error('Error fetching memos:', error);
            });
        },
        createTaskMemo() {
            this.selectedTaskMemo = { content: '' };
            this.taskMemoListChildDlg = true;
            this.isNewTaskMemo = true;
        },
        updateTaskMemo(memo) {
            this.selectedTaskMemo = memo;
            this.taskMemoListChildDlg = true;
            this.isNewTaskMemo = false;
        },

        confirmNewTaskMemo(task_id, memo) {
            axios.post(`/api/v1/tasks/${task_id}/memos`, memo).then(() => {
                this.fetchTaskMemos();
                this.taskMemoListChildDlg = false;
            }).catch(error => {
                console.error('Error creating new task-memo:', error);
            })
        },
        confirmUpdateTaskMemo(task_id, memo) {
            axios.put(`/api/v1/tasks/${task_id}/memos/${memo.id}`, memo).then(() => {
                this.taskMemoListChildDlg = false;
            }).catch(error => {
                console.error('Error creating new task-memo:', error);
            })
        },
        confirmCancelTaskMemo() {
            this.fetchTaskMemos();
            this.taskMemoListChildDlg = false;
        },
    }
}
  </script>
