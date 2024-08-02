<template>
    <el-button type="danger" @click="open(client)">Destroy</el-button>
</template>
<script>
import { ElMessage, ElMessageBox } from "element-plus";

export default {
    emits: ["clientRemoved"],

    props: {
        client: {
            type: Object,
            required: true,
        },
    },

    methods: {
        open(client) {
            ElMessageBox.confirm(
                `Are you share you want to remove this client with ID ${client.id} ?`,
                "Delete client",
                {
                    confirmButtonText: "OK",
                    cancelButtonText: "Cancel",
                    type: "warning",
                }
            )
                .then(() => {
                    this.destroyClient(client);
                })
                .catch(() => {});
        },

        /**
         * message
         */
        popup(message, type = "success") {
            if (message) {
                ElMessage({
                    message: message,
                    type: type,
                });
            }
        },

        async destroyClient(client) {
            try {
                const res = await this.$server.delete(
                    "/oauth/clients/" + client.id
                );

                if (res.status == 200) {
                    this.popup(
                        `The client with id ${client.name} has been removed`
                    );
                }
            } catch (err) {}
        },
    },
};
</script>
<style lang="scss" scoped></style>
