<template>
    <el-button type="danger" @click="open(scope)">Destroy</el-button>
</template>
<script>
import { ElMessageBox, ElMessage } from "element-plus";

export default {
    props: {
        scope: {
            type: Object,
            required: true,
        },
    },

    methods: {
        open(item) {
            ElMessageBox.confirm(
                `Deleting this scope "${item.scope}", may cause system failures. 
                Only delete scopes that were entered by mistake or 
                that are no longer necessary for the operation of any application.`,
                "Caution: System failure",
                {
                    confirmButtonText: "OK",
                    cancelButtonText: "Cancel",
                    type: "warning",
                }
            )
                .then(() => {
                    this.removeScope(item);
                })
                .catch(() => {});
        },

        async removeScope(item) {
            try {
                const res = await this.$server.delete(item.links.destroy);
                if (res.status == 200) {
                    this.popup(`Scope "${item.scope}" has been deleted.`);
                }
            } catch (e) {
                if (e.response && e.response.status == 403) {
                    this.popup(e.response.data.message, "warning");
                }
            }
        },

        /*
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
    },
};
</script>
