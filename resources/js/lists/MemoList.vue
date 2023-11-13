<template>
    <v-table class="bg-grey-lighten-4">
        <thead>
            <tr>
            <th class="text-left">
                日付
            </th>
            <th class="text-left">
                内容
            </th>
            <th class="text-left">
                編集
            </th>
            <th class="text-left">
                削除
            </th>
            </tr>
        </thead>
        <tbody>
            <tr
            v-for="memo in memos"
            :key="memo.id"
            >
                <td>{{ dateFormat(memo.created_at) }}</td>
                <td>{{ memo.content }}</td>
                <td>
                    <v-btn class="bg-success white ml-8">
                        <v-icon>mdi-table-edit</v-icon>
                    </v-btn></td>
                <td>
                    <v-btn
                        color="red"
                    >
                    <v-icon>mdi-delete</v-icon>
                    </v-btn>
                </td>
            </tr>
        </tbody>
    </v-table>
</template>
<script>
import { dateFormat } from '../dateFormat';
export default {
    props: {
        memos: {
            type: Object,
        }
    },
    methods: {
        dateFormat,
    }
}
  </script>
