<template>
    <div class="clients">
        <div class="head">
            <div class="row">
                <div class="col">
                    <p>List of clients</p>
                </div>
                <div class="col">
                    <v-register-client></v-register-client>
                </div>
            </div>
        </div>
        <div class="table" style="margin-bottom: 1em">
            <el-table :data="clients" :lazy="true">
                <el-table-column prop="id" label="id" width="200" />
                <el-table-column prop="secret" label="secret" width="300" />
                <el-table-column prop="name" label="name" width="150" />
                <el-table-column prop="redirect" label="redirect" width="300" />
                <el-table-column label="Operations">
                    <template #default="scope">
                        <div class="actions">
                            <div class="box">
                                <v-remove :client="scope.row"></v-remove>
                            </div>

                            <div class="box">
                                <v-update :client="scope.row"></v-update>
                            </div>
                        </div>
                    </template>
                </el-table-column>
            </el-table>
        </div>
    </div>
</template>
<script>
import VRegisterClient from "./RegisterClient.vue";
import VRemove from "./RemoveClient.vue";
import VUpdate from "./UpdateClient.vue";

export default {
    components: {
        VRegisterClient,
        VRemove,
        VUpdate,
    },

    data() {
        return {
            clients: [],
        };
    },

    created() {
        this.getClients();
    },

    methods: {
        getClients() {
            this.$server
                .get("/oauth/clients")
                .then((res) => {
                    this.clients = res.data;
                })
                .catch((e) => {});
        },
    },
};
</script>
<style lang="scss" scoped>
.clients {
    .head {
        margin-bottom: 1em;

        .row {
            display: flex;
            flex-wrap: wrap;

            .col {
                flex: 1 1 calc(100% / 2);
                p {
                    font-size: 1.3em;
                    margin: 0;
                }

                &:nth-child(2) {
                    text-align: center;
                }
            }
        }
    }

    .actions {
        display: flex;
        flex-wrap: wrap;
        .box {
            flex: auto;
            margin: 0.5em;
        }
    }
}
</style>
