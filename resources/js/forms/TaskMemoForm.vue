<template>
    <v-card>
        <v-card-title>
        <span>{{ formTitle }}</span>
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
            <v-col
            cols="12"
            sm="12"
            md="12"
            >
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
        <v-btn
            class="bg-grey-lighten-3"
            variant="text"
            @click="$emit('cancel')"
        >
        CANCEL
        </v-btn>
        <v-btn
            class="bg-light-blue"
            variant="text"
            v-prevent-double-click
            @click="confirmTaskMemo"
        >
        OK
        </v-btn>
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
        task: {
            type: Object,
        },
        taskMemo: {
            type: Object,
        },
        formTitle: {
            type: String,
        },
    },

    data() {
        return {
            currentTaskMemo: this.taskMemo,
            content: '',
            rules: {
                required: value => !!value || 'Field is required',
            },
        }
    },
    created() {
        this.currentTaskMemo = JSON.parse(JSON.stringify(this.taskMemo));
    },
    methods: {
        confirmTaskMemo() {
            if (this.isNew) {
                this.$emit('create', this.task.id, this.currentTaskMemo);
            } else {
                Object.assign(this.taskMemo, this.currentTaskMemo);
                this.$emit('update', this.task.id, this.currentTaskMemo);
            }
        }
    }
}
</script>
