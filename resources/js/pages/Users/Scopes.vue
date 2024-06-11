<template>
    <!-- Card para mostrar y actualizar datos del usuario-->
    <v-modal @is-clicked="loadData(user)" @is-accepted="update(user)">
        <template v-slot:button> Scopes </template>
        <template v-slot:head>
            Add Or remove Scopes for {{ user.name }}
        </template>

        <template v-slot:body>
            <div class="user-scopes">
                <div
                    class="item"
                    v-for="(item, index) in roles"
                    v-show="!item.public"
                >
                    <div class="box">
                        <v-confirm
                            bg="btn-light"
                            btn=""
                            @is-clicked="confirmAction(item)"
                            @is-confirmed="addOrRemoveScope(item.id)"
                            @is-cancel="cancelAction(item.id)"
                        >
                            <template v-slot:button>
                                <input
                                    :id="item.id"
                                    :value="item.id"
                                    type="checkbox"
                                    class="user_roles"
                                />
                            </template>
                            <template v-slot:head> Alert Scopes </template>
                            <template v-slot:body>
                                <p v-html="text_body"></p>
                            </template>
                        </v-confirm>
                        <label class="label" :for="item.id">
                            <span class="fw-bold">{{ item.scope }}:</span>
                            {{ item.description }}
                        </label>
                    </div>
                </div>
            </div>

            <div :class="{ message: !status, hide: status }">
                <p v-html="message"></p>
            </div>
        </template>
    </v-modal>
</template>
<script>
export default {
    emits: ["success"],

    props: ["user"],

    data() {
        return {
            message: null,
            status: false,
            errors: {},
            roles: {},
            text_body: {},
        };
    },

    methods: {
        /**
         * Show a windows confirmation
         * @param item
         */
        confirmAction(item) {
            const checked = document.getElementById(item.id).checked;
            this.text_body = checked
                ? `The next role <strong>${item.scope}</strong> will be removed`
                : `The next role <strong>${item.scope}</strong> will be added`;
        },

        /**
         * Add or remove roles
         * @param id
         */
        addOrRemoveScope(id) {
            this.message = null;
            this.status = null;
            const checked = document.getElementById(id).checked;

            if (checked) {
                this.addRoles(id);
            } else {
                this.removeRoles(id);
            }
        },

        /**
         * Cancel operation
         */
        cancelAction(id) {
            var role = document.getElementById(id);
            role.checked = !role.checked;
        },

        /**
         * Add roles
         */
        async addRoles(id) {
            try {
                const res = this.$server.post(this.user.links.roles, {
                    scope_id: id,
                });

                if (res.status == 422) {
                    this.message = `A new Scope <strong> (${res.data.data.scope}) </strong> added`;
                }
            } catch (e) {
                if (e.response && e.response.data.status == 403) {
                    this.message = e.response.data.errors.message;
                }
                if (e.response && e.response.status == 422) {
                    this.message = e.response.data.message;
                }
            }
        },

        async removeRoles(id) {
            try {
                const res = await this.$server.delete(
                    `${this.user.links.roles}/${id}`
                );

                if (res.status == 200) {
                    this.message = `The Scope <strong> ${res.data.data.scope} </atrong> was removed`;
                }
            } catch (e) {
                if (e.response && e.response.status == 403) {
                    this.message = e.response.data.message;
                }

                if (e.response && e.response.status == 422) {
                    this.errors = e.response.data.errors;
                }
            }
        },

        loadData(user) {
            this.getRoles();
            this.getUserRoles(user);
        },

        async getRoles() {
            try {
                const res = await this.$server.get("/api/admin/roles");

                if (res.status == 200) {
                    this.roles = res.data.data;
                }
            } catch (e) {}
        },

        /**
         * Obtiene los roles de un usuario
         */
        async getUserRoles(item) {
            try {
                this.message = null;
                const res = await this.$server.get(item.links.roles);

                if (res.status == 200) {
                    this.role_selected(res.data.data);
                }
            } catch (e) {}
        },

        /**
         * selecciona los roles del usuario
         * @param {*} objetos
         */
        role_selected(objetos) {
            const roles = document.querySelectorAll(".user_roles");

            for (let i = 0; i < roles.length; i++) {
                roles[i].checked = false;
            }

            for (let i = 0; i < objetos.length; i++) {
                for (let j = 0; j < roles.length; j++) {
                    if (roles[j].value == objetos[i]["id"]) {
                        roles[j].checked = true;
                    }
                }
            }
        },
    },
};
</script>
<style lang="scss" scoped>
.user-scopes {
    display: flex;
    flex-wrap: wrap;

    .item {
        flex: 1 1 calc(100% / 2);
        margin-bottom: 0.5em;

        .box {
            display: flex;

            label {
                margin-left: 1%;
            }
        }
    }
}

.hide {
    display: none;
}

.message {
    display: block;
    border-top: 1px solid var(--border-color-light);
    padding: 1em;
    text-align: center;
    color: var(--first-color);
}
</style>
