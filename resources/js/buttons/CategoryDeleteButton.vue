<template>
    <v-row justify="center">
        <v-dialog
            v-model="catDeleteDlg"
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
                class="bg-grey-lighten-3"
                @click="catDeleteDlg = false"
            >
                CANCEL
            </v-btn>
            <v-btn
                class="bg-light-blue"
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
    emits: ['delete'],
    props: {
        category: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            catDeleteDlg: false,
        };
    },

    methods: {
        confirmDelete() {
            this.$emit('delete', this.category);
      },
    },
};
</script>
