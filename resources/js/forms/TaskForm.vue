<template>
  <v-card width="800px" style="overflow: scroll">
    <v-card-title>
      <span class="text-h5">{{ getFormTitle }}</span>
      <v-spacer></v-spacer>
    </v-card-title>
    <v-card-text>
      <v-container>
        <v-row>
          <v-col cols="12">
            <v-text-field label="タイトル" :rules="[rules.required]" v-model="currentTask.title"></v-text-field>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12">
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
        </v-row>
        <v-row>
          <v-col cols="6">
            <v-text-field label="期日" type="date" v-model="currentTask.due_date"> </v-text-field>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12">
            <v-textarea label="内容" v-model="currentTask.description"></v-textarea>
          </v-col>
        </v-row>

        <v-card-text v-if="isNew == false">
          <div>
            <h3 class="text-center my-5" style="font-size: 2rem">タスクメモ一覧</h3>
          </div>
          <v-row class="bg-grey-lighten-4">
            <v-col cols="12">
              <MemoList :task="task"> </MemoList>
            </v-col>
          </v-row>
        </v-card-text>
      </v-container>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn class="bg-grey-lighten-3" @click="$emit('cancel')"> CANCEL </v-btn>
      <v-btn class="bg-light-blue" v-prevent-double-click @click="confirmTask"> OK </v-btn>
    </v-card-actions>
    <!-- 新規登録時、タスクメモは登録できない -->
  </v-card>
</template>
<script>
  import MemoList from '../lists/MemoList.vue';

  export default {
    components: {
      MemoList,
    },
    data() {
      return {
        title: '',
        due_date: '',
        category_id: '',
        description: '',
        currentTask: {},
        rules: {
          required: value => !!value || 'Field is required',
        },
      };
    },
    created() {
      this.currentTask = _.clone(this.task);
    },
    emits: ['cancel', 'create', 'update'],
    props: {
      isNew: {
        type: Boolean,
      },
      task: {
        type: Object,
      },
      categories: {
        type: Array,
      },
    },
    computed: {
      getFormTitle() {
        return this.isNew ? 'タスク新規登録画面' : 'タスク編集画面';
      },
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
