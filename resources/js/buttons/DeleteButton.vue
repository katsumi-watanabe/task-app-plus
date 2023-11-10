<template>
    <v-row justify="center">
        <v-dialog
        v-model="deleteDialog"
        persistent
        width="auto"
        >
        <template v-slot:activator="{ props }">
            <v-btn
            color="red"
            v-bind="props"
            >
            <v-icon>mdi-delete</v-icon>

            </v-btn>
        </template>
        <v-card>
            <v-card-title class="text-h5">
            本当に削除しますか？
            </v-card-title>
            <v-card-text>削除する場合は[OK]、削除しない場合は[CANCEL]をクリックしてください。</v-card-text>
            <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                @click="deleteDialog = false"
            >
                CANCEL
            </v-btn>
            <v-btn
                @click="confirmDelete()"
            >
                OK
            </v-btn>
            </v-card-actions>
        </v-card>
        </v-dialog>
    </v-row>
</template>
<script>
export default {
    data() {
        return {
            deleteDialog: false,
        };
    },
    emits: ['delete'],
    props: {
        task: {
            type: Object,
        }
    },

    methods: {
        confirmDelete() {
            this.$emit('delete', this.task);
        },
  },
};
</script>
