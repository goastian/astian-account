<template>
    <div class="tokens">
        <div class="head">
            <div class="row">
                <div class="col">
                    <p>List of token</p>
                </div>
                <div class="col">
                    <v-create
                        @created="getPersonalAccessToken"
                    ></v-create>
                </div>
            </div>
        </div>
    </div>

    <div class="table" style="margin-bottom: 1em">
        <el-table :data="tokens" :lazy="true">
            <el-table-column prop="name" label="name" width="200" />
            <el-table-column prop="scopes" label="scopes" width="300">
                <template #default="scope">
                    {{ scope.row.scopes.join(", ") }}
                </template>
            </el-table-column>
            <el-table-column prop="created_at" label="created" width="200" />
            <el-table-column prop="expires_at" label="expires" width="200" />
            <el-table-column label="actions" min-width="200">
                <template #default="scope">
                    <div class="actions">
                        <div class="box">
                            <v-remove
                                :token="scope.row"
                                @removed="getPersonalAccessToken"
                            ></v-remove>
                        </div>
                    </div>
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>
<script>
import VCreate from "./Create.vue";
import VRemove from "./Remove.vue";

export default {
    components: {
        VCreate,
        VRemove,
    },

    data() {
        return {
            tokens: [],
            user_id: null,
        };
    },

    created() {
        this.getPersonalAccessToken();
    },

    methods: {
        getPersonalAccessToken() {
            this.$server
                .get("/oauth/personal-access-tokens")
                .then((res) => {
                    this.tokens = res.data;
                })
                .catch((e) => {});
        },

        listenEvents() {
            this.$echo
                .private(this.$channels.ch_1(this.$id))
                .listen("RevokeCredentialsEvent", (e) => {
                    this.getPersonalAccessToken();
                });
        },
    },
};
</script>
<style lang="scss" scoped>
.tokens {
    .head {
        .row {
            display: flex;
            flex-wrap: wrap;
            .col {
                flex: calc(100% / 2);
                p {
                    margin: 0;
                }
                &:nth-child(2) {
                    text-align: center;
                }
            }
        }
    }
}
</style>
