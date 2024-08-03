<template>
    <div class="row">
        <div class="col">
            <el-card>
                <template #header>
                    <div class="card-header">
                        <span>Two Factor Authentication</span>
                    </div>
                </template>
                <div>
                    <el-input v-model="token" placeholder="Insert Code ..." />
                    <v-error :error="errors.token"></v-error>
                </div>
                <template #footer>
                    <el-button @click="requestCode()" type="primary">
                        Request token</el-button
                    >
                    <el-button
                        :type="user.m2fa ? 'success' : 'danger'"
                        @click="activateFactor()"
                    >
                        {{ user.m2fa ? "Activated" : "Disabled" }}
                    </el-button>
                </template>
            </el-card>
        </div>
        <div class="col">
            <el-card>
                <template #header>
                    <div class="card-header">
                        <span>Sessions</span>
                    </div>
                </template>
                <div>
                    <el-button type="warning" @click="revocarCredentials">
                        Revoke all tokens
                    </el-button>
                </div>
            </el-card>
        </div>
        <div class="col">
            <el-card>
                <template #header>
                    <div class="card-header">
                        <span>Delete my account</span>
                    </div>
                </template>
                <div>
                    <el-button type="danger" @click="removeAccount(user)">
                        Destroy my account
                    </el-button>
                </div>
            </el-card>
        </div>
    </div>
</template>

<script>
import { ElMessage, ElMessageBox } from "element-plus";

export default {
    data() {
        return {
            token: "",
            user: {},
            errors: {},
        };
    },

    created() {
        this.getAuthUser();
        this.listener();
    },

    methods: {
        /**
         * show a simple message
         *
         * @param message
         * @param type
         */
        popup(message, type = "success") {
            if (message) {
                ElMessage({
                    message: message,
                    type: type,
                });
            }
        },

        /**
         * Request for a fresh code to activate the 2FA
         */
        async requestCode() {
            try {
                const res = await this.$server.post("/m2fa/authorize");
                if (res.status == 201) {
                    this.popup(res.data.message, "success");
                    this.errors = {};
                }
            } catch (err) {
                if (err.response) {
                    this.popup(err.response.data.message, "warning");
                }
            }
        },

        /**
         * Get the current user
         */
        async getAuthUser() {
            try {
                const res = await this.$server.get("/api/gateway/user");

                if (res.status == 200) {
                    this.user = res.data;
                }
            } catch (err) {}
        },

        /**
         * Activate 2FA autentication
         */
        async activateFactor() {
            try {
                const res = await this.$server.post("/m2fa/activate", {
                    token: this.token,
                });
                if (res.status == 201) {
                    this.token = "";
                    this.errors = {};
                    this.popup("2FA has been activated successful");
                    this.getAuthUser()
                }
            } catch (err) {
                if (err.response) {
                    this.errors = err.response.data.errors;
                }
            }
        },

        /**
         * remove account
         */
        async removeAccount(item) {
            ElMessageBox.confirm(
                `The account will be eliminated permanent. Once eliminated any
                information you have saved will be deleted. We will send you an
                email`,
                "Delete permanently",
                {
                    confirmButtonText: "OK",
                    cancelButtonText: "Cancel",
                    type: "warning",
                }
            ).then(() => {
                this.$server
                    .delete(item.links.disable)
                    .then((res) => {
                        if (res.status == 200) {
                            this.popup(
                                "Your account has been destoryed, we're sending an email confimation. in some seconds your session will be destroy",
                                "warning"
                            );
                        }
                    })
                    .catch((err) => {
                        if (
                            err.response.status == 403 &&
                            err.response.data.message
                        ) {
                            this.popup(err.response.data.message, "warning");
                        }
                    });
            });
        },

        /**
         * Remove tokens
         */
        revocarCredentials() {
            ElMessageBox.confirm(
                "Are you sure you want to destroy all sessions?",
                "Delete token genereted",
                {
                    confirmButtonText: "OK",
                    cancelButtonText: "Cancel",
                    type: "warning",
                }
            ).then(() => {
                this.$server
                    .delete("/api/oauth/credentials/revoke")
                    .then((res) => {
                        this.popup(res.data.message, "success");
                    })
                    .catch((e) => {
                        console.error(e.response);
                    });
            });
        },

        /**
         * Listen events
         */
        listener() {
            this.$echo
                .private(this.$channels.ch_0())
                .listen("M2FAEvent", (e) => {
                    this.getAuthUser();
                });
        },
    },
};
</script>
<style lang="scss" scoped>
.row {
    margin: auto;
    width: 70%;
    .col {
        margin-bottom: 0.5em;
    }
}
</style>
