<template>
    <v-card width="90vh" height="90vh" style="overflow: scroll;">
        <v-card-title>
            <span class="text-h5">{{ formTitle }}</span>
            <v-spacer></v-spacer>
            <v-menu
                bottom
                left
            >
            </v-menu>
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
        <!-- 新規登録時、タスクメモは登録できない -->
        <v-card-text v-if="isNew == false">
            <div><h3 class="text-center my-1" style="font-size: 2rem;">タスクメモ一覧</h3></div>
            <v-container>
                <v-row class="bg-grey-lighten-4">
                    <v-col cols="12" md="12" sm="12">
                        <MemoList
                            :task="task"
                        >
                        </MemoList>
                    </v-col>
                </v-row>
            </v-container>
        </v-card-text>
    </v-card>
</template>
<script>
import MemoList from '../lists/MemoList.vue'

export default {
    components: {
        MemoList,
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

        };
    },
    emits: ['cancel', 'create', 'update'],
    props: {
        task: {
            type: Object,
        },
        formTitle: {
            type: String,
        },
        categories: {
            type: Array,
        },
        isNew: {
            type: Boolean,
        }
    },

    methods: {
        confirmTask() {
            if (this.isNew) {
                this.$emit('create', this.currentTask);
            } else {
                this.$emit('update', this.currentTask);
            }
        },
    },
};
</script>
