<template>
    <el-button type="danger" @click="open(item)">Destroy</el-button>
</template>
<script>
import { ElMessage, ElMessageBox } from "element-plus";

export default {
    props: ["item"],

    methods: {
        open(item) {
            ElMessageBox.confirm(
                `Are you sure to destroy this channel ${item.channel}`,
                `Remove channel`,
                {
                    confirmButtonText: "OK",
                    cancelButtonText: "Cancel",
                    type: "warning",
                }
            )
                .then(() => {
                    this.remove(item);
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

        async remove(item) {
            try {
                const res = await this.$server.delete(item.links.destroy);

                if (res.status == 200) {
                    this.popup(
                        "Channel has been removed successful",
                        "success"
                    );
                }
            } catch (e) {
                if (e.response && e.response.status == 403) {
                    this.popup(e.response.data.message, "warning");
                }
            }
        },
    },
};
</script>
