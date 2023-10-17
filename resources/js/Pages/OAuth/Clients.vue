<template>
    <div class="container-fluid">
        <div class="card w-75" style="margin: 1% auto">
            <div class="class card-head h4 text-center">Clientes</div>
            <div class="class card-body">
                <div class="class text-center row row-cols-3 col-sm-12">
                    <div class="col-4">
                        <label for="cliente">cliente</label>
                        <input
                            type="text"
                            v-model="form.name"
                            class="form-control"
                        />
                        <span class="errors" v-for="(item, index) in errors.name" :key="index"> {{ item }}</span>
                    </div>
                    <div class="col-6">
                        <label for="redirect">redirect</label>
                        <input
                            type="text"
                            v-model="form.redirect"
                            id="redirect"
                            class="form-control"
                        />
                        <span class="errors" v-for="(item, index) in errors.redirect" :key="index"> {{ item }}</span>

                    </div>
                    <div class="col-2 py-4">
                        <button
                            class="btn btn-block btn-success"
                            @click="storeClients"
                        >
                            registrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <v-table :items="items">
            <template v-slot:body>
                <tr v-for="(item, index) in this.clients" :key="index">
                    <td>
                        {{ item.name }}
                    </td>
                    <td>
                        {{ item.provider }}
                    </td>

                    <td>
                        {{ item.redirect }}
                    </td>
                    <td>{{ item.created_at }}</td>
                    <td>
                        {{ item.updated_at }}
                    </td>
                    <td>
                        <a @click="deleteClient(item)" class="btn btn-danger">
                            remove
                        </a>

                        <v-update
                            :target="item.name + '__' + index"
                            @is-clicked="setData(item)"
                            @is-accepted="updateClient(item.id)"
                            styles="mx-2"
                        >
                            <template v-slot:button> update </template>
                            <template v-slot:body>
                                <div class="class row row-cols-3 col-sm-12">
                                    <div class="col-4">
                                        <label for="cliente">cliente</label>
                                        <input
                                            type="text"
                                            v-model="update.name"
                                            class="form-control"
                                        />
                                    </div>
                                    <div class="col-8">
                                        <label for="redirect">redirect</label>
                                        <input
                                            type="text"
                                            v-model="update.redirect"
                                            id="redirect"
                                            class="form-control"
                                        />
                                    </div>
                                </div>
                                <div
                                    v-show="message"
                                    class="row py-2 my-2 text-center row-cols-1 col-12 bg-success"
                                >
                                   <span>{{ message }}</span> 
                                </div>
                            </template>
                        </v-update>
                    </td>
                </tr>
            </template>
        </v-table>
    </div>
</template>
<script>
import VTable from "./Components/table.vue";
import VUpdate from "./Components/modal.vue";

export default {
    components: {
        VUpdate,
        VTable,
    },

    data() {
        return {
            items: ["name", "provider", "redirect", "create", "update"],
            clients: {},
            form: {
                name: "",
                redirect: "",
            },
            update: {
                name: "",
                redirect: "",
            },
            message: "",
            errors : {}
        };
    },

    created() {
        this.getClients();
    },

    methods: {
        setData(item) {
            this.message = "";
            this.update = item;
        },

        getClients() {
            window.axios
                .get("http://auth.spondylus.xyz/oauth/clients")
                .then((res) => {
                    this.clients = res.data;
                })
                .catch((e) => {
                    console.log(e);
                });
        },

        storeClients() {
            window.axios
                .post("http://auth.spondylus.xyz/oauth/clients", this.form)
                .then((res) => {
                    this.form = { name: "", redirect: "" };
                    this.getClients();
                })
                .catch((e) => {
                     
                    if(e.response && e.response.data.errors){
                        this.errors = e.response.data.errors
                    }
                });
        },

        updateClient(id) {
            this.message = null;
            window.axios
                .put("/oauth/clients/" + id, this.update)
                .then((res) => {
                    this.message = "datos actualizados";
                    this.getClients();
                })
                .catch((e) => {
                    console.log(e);
                });
        },

        deleteClient(item) {
            window.axios
                .delete("/oauth/clients/" + item.id)
                .then((response) => {
                    this.getClients();
                })
                .catch((e) => {
                    console.log(e);
                });
        },
    },
};
</script>
<style></style>
