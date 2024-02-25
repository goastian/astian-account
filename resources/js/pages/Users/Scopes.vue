<template>
    <!-- Card para mostrar y actualizar datos del usuario-->
    <v-modal
        :target="'_rl_C__' + user.id"
        @is-clicked="loadData(user)"
        @is-accepted="update(user)"
        styles="btn-sm bg-warning"
    >
        <template v-slot:button> Scopes </template>
        <template v-slot:head>
            <h3 class="text-color">Add scopes for this user</h3>
        </template>

        <template v-slot:body>
            <div class="row user-scopes">
                <div
                    class="col form-check text-start"
                    v-for="(item, index) in roles"
                    v-show="!item.publico"
                >
                    <input
                        @click="confirmAction(item.id, $event)"
                        class="form-check-input"
                        :id="item.id"
                        :value="item.id"
                        type="checkbox"
                    />
                    <label class="form-check-label" :for="item.id">
                        <span class="fw-bold">{{ item.role }}:</span>
                        {{ item.descripcion }}
                    </label>
                </div>
                <div v-if="message" class="col-12 mt-4 py-4 fw-bold bg-info">
                    <span class="text-color">{{ message }}</span>
                </div>
            </div>

            <div
                :class="[
                    'col-12 bg-info text-center h3 mx-2 py-3',
                    [status ? 'show' : 'hide'],
                ]"
            >
                <span class="text-light">{{ status }}</span>
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
            client: false,
        };
    },

    methods: {
        confirmAction(id, event) {
            const checkbox = event.target;
            if (checkbox.checked) {
                const add_role = confirm(
                    "Are you sure you want to add a new Scope?"
                );
                this.addOrRemoveRoles(id);
            } else {
                const remove_role = confirm(
                    "Are you sure you want to remove this Scope?"
                );
                this.addOrRemoveRoles(id);
            }
        },

        /**
         * agrega o elimina permisos
         */
        addOrRemoveRoles(id) {
            this.message = null;
            this.status = null;
            const checked = document.getElementById(id).checked;

            if (checked) {
                this.$server
                    .post(this.user.links.roles, { role: id })
                    .then((res) => {
                        this.message = `A new Scope was added (${res.data.data.role})`;
                    })
                    .catch((e) => {
                        if (e.response && e.response.data.status == 403) {
                            this.message = e.response.data.errors.message;
                        }
                        if (e.response && e.response.data.message) {
                            this.message = e.response.data.message;
                        }
                    });
            } else {
                this.$server
                    .delete(`${this.user.links.roles}/${id}`)
                    .then((res) => {
                        this.message = `The Scope ${res.data.data.role} was removed`;
                    })
                    .catch((e) => {
                        if (e.response && e.response.status == 403) {
                            if (e.response && e.response.data.message) {
                                this.message = e.response.data.message;
                            }

                            if (e.response && e.response.data.errors) {
                                this.errors = e.response.data.errors;
                            }
                        }
                    });
            }
        },

        loadData(user) {
            this.getRoles();
            this.getUserRoles(user);
        },

        getRoles() {
            this.$server
                .get("/api/roles", {
                    params: {
                        per_page: 100,
                    },
                })
                .then((res) => {
                    this.roles = res.data.data;
                })
                .catch((e) => {
                    if (e.response) {
                        console.log(e.response);
                    }
                });
        },

        /**
         * Actualiza a un usuario
         * @param {*} item
         */
        update(item) {
            this.status = null;
            this.message = null;
            this.$server
                .put(item.links.update, this.user)
                .then((res) => {
                    this.status = "Usuario Actualizado";
                    this.$emit("success", res.data.data);
                    this.errors = {};
                })
                .catch((e) => {
                    if (e.response && e.response.data.errors) {
                        this.errors = e.response.data.errors;
                    }
                });
        },

        /**
         * Obtiene los roles de un usuario
         */
        getUserRoles(item) {
            this.message = null;
            this.$server
                .get(item.links.roles, {
                    params: {
                        per_page: 100,
                    },
                })
                .then((res) => {
                    this.role_selected(res.data.data);
                })
                .catch((e) => {});
        },

        /**
         * selecciona los roles del usuario
         * @param {*} objetos
         */
        role_selected(objetos) {
            const roles = document.querySelectorAll(".form-check-input");

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
.col {
    flex: 0 0 auto;
    text-align: left;

    @media (min-width: 240px) {
        width: 98%;
        margin-bottom: 3%;
    }

    @media (min-width: 800px) {
        margin-bottom: 1%;
        width: 48%;
    }

    @media (min-width: 940px) {
        width: 30%;
    }
}

.hide {
    display: none;
}

.show {
    display: block;
}
</style>
