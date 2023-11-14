<template>
    <v-row justify="center">
        <v-card width="90vh" height="90vh" style="overflow: scroll;">
          <v-card-title class="mt-5">
            <span class="text-h5">新規登録</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12" md="12" sm="12">
                <v-text-field
                    label="タイトル"
                    :rules="[rules.required]"
                    v-model="currentTask.title"
                ></v-text-field>
                </v-col>
                <v-col cols="12" md="12" sm="12">
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
                <v-col cols="6" md="6" sm="6">
                  <v-text-field
                    label="期日"
                    type="date"
                    v-model="currentTask.due_date"
                  >
                </v-text-field>
                </v-col>
                <v-col cols="12" md="12" sm="12">
                    <v-textarea
                    label="内容"
                    v-model="currentTask.description"
                    ></v-textarea>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                class="bg-grey-lighten-3"
                @click="$emit('cancel')"
            >
              CANCEL
            </v-btn>
            <v-btn
                class="bg-light-blue"
                @click="confirmTask"
            >
                OK
            </v-btn>
          </v-card-actions>

          <v-card-text v-if="memos != null">
            <div><h3 class="text-center my-10" style="font-size: 2rem;">タスクメモ一覧</h3></div>
            <v-container>
                <v-row class="bg-grey-lighten-4">
                    <v-col cols="12" md="12" sm="12">
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
                        <MemoList
                            :memos="memos"
                        >
                    </MemoList>
                    </v-col>
                </v-row>
            </v-container>
        </v-card-text>
        </v-card>
    </v-row>

    <v-dialog
        v-model="taskMemoListChildDlg"
        max-width="500px"
    >
        <TaskMemoForm
            @cancel="taskMemoListChildDlg = false"
            :isNewMemo="isNewTaskMemo"
            :taskMemo="selectedTaskMemo"
            :task="task"
            @create="confirmNewTaskMemo"
            @update="confirmUpdateTaskMemo"
        >

        </TaskMemoForm>

    </v-dialog>
</template>
<script>
import MemoList from '../lists/MemoList.vue'
import TaskMemoForm from '../forms/TaskMemoForm.vue'

export default {
    components: {
        MemoList,
        TaskMemoForm,
    },
    data() {
        return {
            memos: '',
            title: '',
            due_date: '',
            category_id: '',
            description: '',
            currentTask: this.task,
            rules: {
                required: value => !!value || 'Field is required',
            },

            // タスクメモ
            selectedTaskMemo: '',
            content: '',
            isNewMemo: this.isNewTaskMemo,
            taskMemoListChildDlg: false,
        };
    },
    mounted() {
        this.fetchTaskMemos();
    },
    emits: ['cancel', 'create', 'update'],
    props: {
        task: {
            type: Object,
        },
        categories: {
            type: Array,
        },
        isNew: {
            type: Boolean,
        }
    },

    methods: {
        fetchTaskMemos() {
            console.log(this.task);
            axios.get(`/api/v1/tasks/${this.task.id}/memos`).then(res => {
                this.memos = res.data.memos;
            }).catch(error => {
                console.error('Error fetching memos:', error);
            });
        },

        confirmTask() {
            if (this.isNew) {
                this.$emit('create', this.currentTask);
            } else {
                this.$emit('update', this.currentTask);
            }
        },

        createTaskMemo() {
            this.selectedTaskMemo = { content: '' };
            this.taskMemoListChildDlg = true;
            this.isNewTaskMemo = true;
        },

        confirmNewTaskMemo(task_id, memo) {
            const params = {
                content: memo,
            };
            axios.post(`/api/v1/tasks/${task_id}/memos`, params).then(() => {
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
    },
};
</script>
