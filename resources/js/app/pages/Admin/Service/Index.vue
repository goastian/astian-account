<template>
    <v-header title="services" />
    <div class="container">
        <v-filter :params="params" @change="searching"></v-filter>
        <q-table
            grid
            flat
            bordered
            :rows="services"
            :columns="headers"
            row-key="name"
            hide-bottom
            :rows-per-page-options="[search.per_page]"
            hide-pagination
        >
            <template v-slot:top>
                <h5>List of services</h5>
                <q-space />
                <v-create @created="getServices()"></v-create>
            </template>
            <template v-slot:body-cell-revoked="props">
                <q-td>
                    {{ props.row.revoked ? "Yes" : "No" }}
                </q-td>
            </template>
            <template v-slot:body-cell-system="props">
                <q-td>
                    {{ props.row.system ? "Yes" : "No" }}
                </q-td>
            </template>
            <template v-slot:body-cell-actions="props">
                <q-td class="">
                    <v-update
                        @updated="getServices"
                        :item="props.row"
                    ></v-update>

                    <v-delete
                        @deleted="getServices"
                        :item="props.row"
                    ></v-delete>
                </q-td>
            </template>
            <template v-slot:item="props">
                <div class="container-card q-pa-md col-xs-12 col-sm-6 col-md-4">
                    <div class="card" :class="[ props.row.system ? 'success' : 'default' ]">
                        <!-- top -->
                        <div class="row justify-between">
                            <!-- Indicador de estado verificado -->
                            <div class="status-indicator">
                                <div class="tooltip">
                                    <div class="badge" :class="[ props.row.system ? 'success' : 'default' ]">
                                        <q-icon name="mdi-shield-check" />
                                        <span v-if="props.row.system" class="">System</span>
                                        <span v-else>External</span>
                                    </div>
                                    <div v-if="props.row.system" class="tooltip-content">
                                        This service is part of the system and cannot be deleted
                                    </div>
                                    <div v-else class="tooltip-content">
                                        This service is not part of the system and can be deleted
                                    </div>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="action-buttons">
                                <!--
                                <button v-if="!props.row.system" class="button toggle" @click="changeSystem(props.row)">
                                    <q-icon name="mdi-shield-check" />
                                </button>
                                -->

                                <v-update
                                    @updated="getServices"
                                    :item="props.row"
                                ></v-update>

                                <v-delete
                                    v-if="!props.row.system"
                                    @deleted="getServices"
                                    :item="props.row"
                                ></v-delete>
                            </div>
                        </div>

                        <!-- Header de la tarjeta -->
                        <div class="card-header">
                            <div class="status-icon" :class="[ props.row.system ? 'success' : 'default' ]">
                                <q-icon name="mdi-file-document-check-outline" size="28px" />
                            </div>
                            <div class="card-info">
                                <span>{{ props.row.group_name }}</span>
                                <h3 class="card-title">{{ props.row.name }}</h3>
                                <span>{{ props.row.description }}</span>
                            </div>
                        </div>

                        <!-- Contenido de la tarjeta -->
                        <div class="card-content">
                            <div class="scopes">
                                <div class="scope">
                                    <div class="row items-center">
                                        <q-icon name="mdi-cog" />
                                        <span>Roles:</span>
                                    </div>
                                    <v-add-scope @add="getServices()" :id="props.row.id" />
                                    <!--
                                    <span class="scope-count">3</span>
                                    -->
                                </div>

                                <div class="scope-list" v-if="scopes[props.row.id]">
                                    <div class="scope-item badge" v-for="(scope, index) in scopes[props.row.id]">
                                        <span class="scope-title">{{ scope.role.name }}</span>
                                        <button @click="copyToClipboard(scope.gsr_id)">
                                            <q-icon name="mdi-content-copy" size="12px"/>
                                        </button>
                                        <v-edit-scope @edit="getServices()" :item="scope" />
                                        <button class="delete-scope" @click="revokeRole(scope)">
                                            <q-icon name="mdi-close" size="12px" />
                                        </button>
                                    </div>
                                </div>
                                <div v-if="scopes[props.row.id] < 1" >
                                    <span>No roles have been assigned to this service</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </q-table>

        <div class="row justify-center q-mt-md">
            <q-pagination
                v-model="search.page"
                color="grey-8"
                :max="pages.total_pages"
                size="sm"
            />
        </div>
    </div>
</template>
<script>
import VCreate from "./Create.vue";
import VUpdate from "./Update.vue";
import VDelete from "./Delete.vue";
import VAddScope from "./AddScope.vue";

export default {
    components: {
        VCreate,
        VUpdate,
        VDelete,
        VAddScope,
    },

    data() {
        return {
            services: [],
            scopes: {},
            headers: [
                { label: "Name", name: "value", field: "name", align: "left" },
                { label: "Slug", name: "value", field: "slug", align: "left" },
                {
                    label: "Description",
                    name: "description",
                    field: "description",
                    align: "left",
                },
                {
                    label: "System",
                    name: "system",
                    field: "system",
                    align: "left",
                },
                {
                    label: "Group",
                    name: "group_name",
                    field: "group_name",
                    align: "left",
                },
                {
                    label: "Actions",
                    name: "actions",
                    field: "actions",
                    align: "center",
                },
            ],
            params: [
                { key: "Name", value: "name" },
                { key: "Group", value: "group" },
                { key: "Created", value: "created" },
                { key: "Updated", value: "updated" },
            ],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
            snackbar: false,
        };
    },

    watch: {
        "search.page"(value) {
            this.getServices();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getServices();
            }
        },
    },

    created() {
        this.getServices();
    },

    methods: {
        changePage(event) {
            this.search.page = event;
        },

        searching(event) {
            this.getServices(event);
        },

        getServices(param = null) {
            var params = {};
            Object.assign(params, this.search);
            Object.assign(params, param);

            this.$server
                .get("/api/admin/services", {
                    params: params,
                })
                .then((res) => {
                    this.services = res.data.data;
                    var meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;

                    this.services.forEach(async (item) => {
                        await this.getScopes(item.links.scopes, item.id);
                    });
                })
                .catch((e) => {});
        },

        async getScopes(url, id) {
            try {
                const response = await this.$server.get(url);
                //this.$set(this.scopes, id, response.data.data)
                this.scopes[id] = response.data.data;
            } catch (e) {
                console.log(e);
            }
        },

        revokeRole(itemscope) {
            console.log(itemscope)
            this.$server.delete(itemscope.links.revoke).
                then((res) => {
                    console.log(res);
                    this.$q.notify({
                        type: "positive",
                        message: res.data.message,
                        timeout: 3000,
                    });
                    this.getServices();
                })
        },

        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                this.$q.notify({
                    type: "positive",
                    message: "Copy successfully",
                    timeout: 3000,
                });
            } catch (err) {}
        },

        changeSystem(item) {
            console.log(item.links.update)
            this.$server.put(item.links.update, {
                "system": true,
            }, {
                headers: {
                    "Content-Type" : "application/x-www-form-urlencoded"
                }
            }).then((res) => {
                this.$q.notify({
                    type: "positive",
                    message: "Now the service is part of the system",
                    timeout: 3000,
                });
            }).catch((e) => {})
            console.log(item)
        },
    },
};
</script>

<style scoped>
.container {
    padding: 1rem;
}

.container-card {
    display: flex;
    gap: 2rem;
    justify-content: center;
}

.card {
    transition: all 0.3s ease;
    border-radius: .4rem;
    padding: 1rem;
    background-color: white;
    display: flex;
    flex-direction: column;
    gap: .5rem;
    width: 100%;
    min-width: 100px;
    max-width: 500px;
}

.card.success {
    border-top: 4px solid var(--teal-primary);
}

.card.default {
    border-top: 4px solid var(--purple-primary);
}

.card:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transform: translateY(-2px);
}

.card .status-indicator {
}

.card .action-buttons {
}

.card:hover .action-buttons {
    opacity: 1;
}

.card-header {
    display: flex;
    gap: 16px;
    align-items: center;
}

.card-header .status-icon {
    padding: .8rem 1rem;
    border-radius: 50%;
}

.card-header .status-icon.success {
    background-color: var(--teal-secondary);
    color: var(--teal-primary);
}

.card-header .status-icon.default {
    background-color: var(--purple-secondary);
    color: var(--purple-primary);
}

.card-header .card-title {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    line-height: 25px;
}

.card-content {
}

.card-content .scopes {
    display: flex;
    flex-direction: column;
    gap: .5rem;
}

.card-content .scopes .scope {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    color: #666;
    border-bottom: .04rem solid var(--border-color);
    padding: .4rem 0;
}

.card-content .scopes .scope .scope-count {
  background-color: green;
  color: white;
  padding: 4px 8px;
  border-radius: 12px;
}

.card-content .scope-list {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.card-content .scope-item.badge {
    display: flex;
    justify-content: space-between;
    gap: .8rem;
    align-items: center;
    padding: .2rem 1rem;
    background-color: lightgreen;
    border-radius: 12px;
    color: green;
}

.card-content .scope-item .delete-scope {
  background: none;
  border: none;
  color: red;
  cursor: pointer;
}

.card .badge.success {
  background-color: var(--green-secondary);
  color: var(--green-primary);
  padding: 4px 1rem;
  border-radius: 12px;
  font-size: 12px;
  display: flex;
  align-items: center;
  gap: .4rem;
}

.card .badge.default {
  background-color: var(--black-primary);
  color: var(--white-primary);
  padding: 4px 1rem;
  border-radius: 12px;
  font-size: 12px;
  display: flex;
  align-items: center;
  gap: .4rem;
}

.card-info > span:first-child {
    font-size: 12px;
}

.card .button {
  border: 1px solid #ccc;
  border-radius: 50%;
  padding: .2rem .4rem;
  cursor: pointer;
}

.card .button.toggle {
  background-color: white;
}

.card .button.edit {
  background-color: white;
}

.card .button.delete {
  background-color: white;
}

.card .button.delete[disabled] {
  background-color: #f0f0f0;
  cursor: not-allowed;
}

/* Tooltip */
.tooltip {
  position: relative;
  display: inline-block;
}

.tooltip .tooltip-content {
  visibility: hidden;
  width: 150px;
  background-color: black;
  color: white;
  text-align: center;
  padding: 5px;
  border-radius: 4px;
  position: absolute;
  z-index: 1;
  bottom: 125%;
  left: 50%;
  margin-left: -75px;
}

.tooltip:hover .tooltip-content {
  visibility: visible;
}
</style>
