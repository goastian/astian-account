<template>
    <div class="tokens">
        <div class="head">
            <p>List of sessions</p>
        </div>
    </div>
    <div class="table" style="margin-bottom: 1em">
        <el-table :data="tokens" :lazy="true">
            <el-table-column prop="name" label="name" width="200" />
            <el-table-column prop="scopes" label="scopes" width="400">
                <template #default="scope">
                    {{ scope.row.scopes.join(", ") }}
                </template>
            </el-table-column>
            <el-table-column prop="created_at" label="created" width="200" />
            <el-table-column prop="expires_at" label="expires" width="200" />
            <el-table-column label="actions">
                <template #default="scope">
                    <div class="actions">
                        <div class="box">
                            <v-token-remove
                                :token="scope.row"
                                @revoked="getTokens"
                            >
                            </v-token-remove>
                        </div>
                    </div>
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>
<script>
import VTokenRemove from "./TokenRemove.vue";

export default {
    components: {
        VTokenRemove,
    },

    data() {
        return {
            tokens: [],
        };
    },

    created() {
        this.getTokens();
    },

    mounted() {
        this.listenEvents();
    },

    methods: {
        getTokens() {
            this.$server
                .get("/oauth/tokens")
                .then((res) => {
                    this.tokens = res.data;
                })
                .catch((e) => {});
        },

        listenEvents() {
            this.$echo
                .private(this.$channels.ch_1(this.$id))
                .listen("RevokeCredentialsEvent", (e) => {
                    this.getTokens();
                });
        },
    },
};
</script>
<style lang="scss" scoped>
.tokens {
    .head {
        p {
            margin: 0;
        }
    }
}
</style>
