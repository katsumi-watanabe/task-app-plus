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
            @click="confirmCategory"
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
        category: {
            type: Object,
        },
        formTitle: {
            type: String,
        },
        isNew: {
            type: Boolean,
        },
    },

    data() {
        return {
            currentCategory: this.category,
            name: '',
            rules: {
                required: value => !!value || 'Field is required',
            },
        }
    },
    methods: {
        confirmCategory() {
            if (this.isNew) {
                this.$emit('create', this.currentCategory);
            } else {
                this.$emit('update', this.currentCategory);
            }
        }
    }
}
</script>
