<template>
    <v-row justify="center">
        <v-card>
          <v-card-title>
            <span class="text-h5">新規登録</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col
                  cols="12"
                  sm="12"
                  md="12"
                >
                <v-text-field
                    label="タイトル"
                    persistent-hint
                    :rules="[rules.required]"
                    v-model="currentTask.title"
                ></v-text-field>
                </v-col>
                <v-col
                  cols="12"
                  sm="12"
                  md="12"
                >
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
                <v-col cols="6">

                  <v-text-field
                    label="期日"
                    type="date"
                    v-model="currentTask.due_date"
                  >
                </v-text-field>
                </v-col>
                <v-col cols="12">
                    <v-textarea
                    label="内容"
                    v-model="currentTask.description"
                    ></v-textarea>
                </v-col>
                <v-col
                  cols="12"
                  sm="12"
                >
                </v-col>

              </v-row>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              @click="$emit('cancel')"
            >
              CANCEL
            </v-btn>
            <v-btn
                @click="confirmTask"
            >
                OK
            </v-btn>
          </v-card-actions>
        </v-card>

    </v-row>
</template>
<script>
export default {
    data() {
        return {
            title: '',
            due_date: '',
            category_id: '',
            description: '',
            newDialog: false,
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
        }
    },
};
</script>
