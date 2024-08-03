<template>
    <div class="profile">
        <div class="row">
            <div class="col">
                <el-avatar :size="100" src="https://empty">
                    <img src="" />
                </el-avatar>
            </div>
            <div class="col">
                <el-descriptions direction="vertical" title="User Info">
                    <el-descriptions-item label="Full Name">
                        {{ user.name }}
                        {{ user.last_name }}</el-descriptions-item
                    >
                    <el-descriptions-item label="Email Address">{{
                        user.email
                    }}</el-descriptions-item>
                    <el-descriptions-item label="Telephone">{{
                        user.full_phone
                    }}</el-descriptions-item>
                    <el-descriptions-item label="Birthday">
                        {{ user.birthday }}</el-descriptions-item
                    >

                    <el-descriptions-item label="Address">
                        {{ user.country }} - {{ user.city }} -
                        {{ user.address }}
                    </el-descriptions-item>
                    <el-descriptions-item label="">
                        <v-update :user="user"></v-update>
                    </el-descriptions-item>
                </el-descriptions>
            </div>
            <div class="col">
                <el-card>
                    <template #header>
                        <div class="card-header">
                            <span>My Scopes</span>
                        </div>
                    </template>
                    <el-popover
                        v-for="(item, index) in roles"
                        :key="index"
                        placement="top-start"
                        :title="item.id"
                        :width="200"
                        trigger="hover"
                        :content="item.description"
                    >
                        <template #reference>
                            <el-button
                                style="
                                    margin-right: 0.5em;
                                    margin-bottom: 0.5em;
                                "
                            >
                                {{ item.id }}</el-button
                            >
                        </template>
                    </el-popover>
                </el-card>
            </div>
        </div>
    </div>
</template>
<script>
import VUpdate from "../Users/Update.vue";

export default {
    components: {
        VUpdate,
    },

    data() {
        return {
            user: {},
            roles: {},
            sessions: {},
        };
    },

    created() {
        this.authenticated();
        this.scopes();
    },

    methods: {
        async authenticated() {
            try {
                const res = await this.$server.get("/api/gateway/user");

                if (res.status == 200) {
                    this.user = res.data;
                }
            } catch (e) {}
        },
        async scopes() {
            try {
                const res = await this.$server.get("/api/oauth/scopes");

                if (res.status == 200) {
                    this.roles = res.data;
                }
            } catch (e) {}
        },

        listener() {
            this.$echo
                .private(this.$channels.ch_0())
                .listen("UpdateEmployeeEvent", (e) => {
                    this.authenticated();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen("StoreEmployeeRoleEvent", (e) => {
                    this.authenticated();
                });
            this.$echo
                .private(this.$channels.ch_0())
                .listen("DestroyEmployeeRoleEvent", (e) => {
                    this.authenticated();
                });

            this.$echo
                .private(this.$channels.ch_1(window.$auth.id))
                .listen("authorize", (e) => {
                    this.session();
                });
        },
    },
};
</script>

<style lang="scss" scoped>
.profile {
    .row {
        display: flex;
        flex-wrap: wrap;

        .col {
            flex: 1 1 auto;
            &:nth-child(1) {
                text-align: center;
                flex: 0 0 15%;
            }

            &:nth-child(3) {
                flex: 1 1 100%;
            }
        }
    }
}
</style>
