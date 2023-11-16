<template>
  <v-card>
    <v-card-title>
      <span>{{ isNew == true ? 'タスクメモ新規登録画面' : 'タスクメモ編集画面' }}</span>
      <v-spacer></v-spacer>
    </v-card-title>
    <v-card-text>
      <v-container>
        <v-row>
          <v-col cols="12">
            <v-text-field
              label="コンテンツ"
              persistent-hint
              :rules="[rules.required]"
              v-model="currentTaskMemo.content"
            ></v-text-field>
          </v-col>
        </v-row>
      </v-container>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn class="bg-grey-lighten-3" variant="text" @click="$emit('cancel')"> CANCEL </v-btn>
      <v-btn class="bg-light-blue" variant="text" v-prevent-double-click @click="confirmTaskMemo"> OK </v-btn>
    </v-card-actions>
  </v-card>
</template>
<script>
  export default {
    emits: ['cancel', 'create', 'update'],
    props: {
      isNew: {
        type: Boolean,
      },
      taskMemo: {
        type: Object,
      },
    },

    data() {
      return {
        currentTaskMemo: {},
        content: '',
        rules: {
          required: value => !!value || 'Field is required',
        },
      };
    },
    created() {
      this.currentTaskMemo = _.clone(this.taskMemo);
    },
    methods: {
      confirmTaskMemo() {
        if (this.isNew) {
          this.$emit('create', this.currentTaskMemo);
        } else {
          this.$emit('update', this.currentTaskMemo);
        }
      },
    },
  };
</script>
