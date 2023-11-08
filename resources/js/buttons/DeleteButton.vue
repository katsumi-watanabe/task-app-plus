<template>
    <v-row justify="center">
        <v-dialog
        v-model="delete_dialog"
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
                color="green-darken-1"
                variant="text"
                @click="delete_dialog = false"
            >
                CANCEL
            </v-btn>
            <v-btn
                color="green-darken-1"
                variant="text"
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
            delete_dialog: false,
        };
    },
    emits: ['deleteOnClick'],
    props: {
        id: {
            type: Number,
            required: true
        }
    },

    methods: {
        confirmDelete() {
            axios.delete(`/api/v1/tasks/${this.id}`).then(res => {
                this.$emit('deleteOnClick', this.id);
                this.delete_dialog = false;
            })
        },
  },
};
</script>
