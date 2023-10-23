<template>
    <v-register @user-was-registered="getUsers"></v-register>
    <v-table
        :items="items"
        class="table-sm text-sm text-center"
        style="width: 80%; margin: 0 auto"
    >
        <template v-slot:body>
            <tr v-for="(item, index) in users" :key="index">
                <td>{{ item.nombre }}</td>
                <td>{{ item.apellido }}</td>
                <td>{{ item.correo_electronico }}</td>
                <td>{{ item.telefono }}</td>
                <td>{{ item.registrado }}</td>
                <td>
                    <v-update
                        :user="item"
                        @user-was-updated="getUsers"
                    ></v-update>
                    <v-status :user="item" @user-status="getUsers"></v-status>
                </td>
            </tr>
        </template>
    </v-table>

    <v-pagination :pages="pages" @send-current-page="changeList"></v-pagination>
</template>
<script>
import VRegister from "./Register.vue";
import VUpdate from "./Update.vue";
import VStatus from "./Status.vue";

export default {
    components: {
        VRegister,
        VUpdate,
        VStatus,
    },

    data() {
        return {
            items: ["nombre", "apellido", "correo", "telefono", "registrado"],
            users: {},
            pages: {},
        };
    },

    mounted() {
        this.getUsers();
        this.changeList()
        //this.listenEvents();
    },

    methods: {
        getUsers(id = 1) {
            window.axios
                .get("/api/users", { params: { page: id } })
                .then((res) => { 
                    this.users = res.data.data;
                    this.pages = res.data.meta.pagination;
                })
                .catch((e) => {
                    console.log(e.response);
                });
        },

        changeList(id) {
            this.getUsers(id)
        },

        /*  listenEvents() {
            this.$echo
                .private(this.$channels.ch_0())
                .listen(".UpdateEmployeeEvent", (e) => {
                    this.getUsers();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen(".StoreEmployeeEvent", (e) => {
                    this.getUsers();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen(".EnableEmployeeEvent", (e) => {
                    this.getUsers();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen(".DisableEmployeeEvent", (e) => {
                    this.getUsers();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen(".StoreEmployeeRoleEvent", (e) => {
                    this.getUsers();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen(".DestroyEmployeeRoleEvent", (e) => {
                    this.getUsers();
                });
        },*/
    },
};
</script>
