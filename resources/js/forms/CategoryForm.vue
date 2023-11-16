<template>
  <v-card>
    <v-card-title>
      <span>{{ isNew ? 'カテゴリ新規登録画面' : 'カテゴリ編集画面' }}</span>
      <v-spacer></v-spacer>
    </v-card-title>
    <v-card-text>
      <v-container>
        <v-row>
          <v-col cols="12" sm="12" md="12">
            <v-text-field
              label="カテゴリ名"
              persistent-hint
              :rules="[rules.required]"
              v-model="currentCategory.name"
            ></v-text-field>
          </v-col>
        </v-row>
      </v-container>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn class="bg-grey-lighten-3" variant="text" @click="$emit('cancel')"> CANCEL </v-btn>
      <v-btn class="bg-light-blue" variant="text" v-prevent-double-click @click="confirmCategory"> OK </v-btn>
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
      category: {
        type: Object,
      },
    },

    data() {
      return {
        name: '',
        currentCategory: {},
        rules: {
          required: value => !!value || 'Field is required',
        },
      };
    },
    created() {
      this.currentCategory = _.clone(this.category);
    },
    methods: {
      confirmCategory() {
        if (this.isNew) {
          this.$emit('create', this.currentCategory);
        } else {
          this.$emit('update', this.currentCategory);
        }
      },
    },
  };
</script>
