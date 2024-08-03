<template>
    <el-button type="primary" @click="showModal">
       Add
    </el-button>

    <el-dialog
        v-model="show_modal"
        title="Create new user"
        draggable
        destroy-on-close
        append-to-body
        fullscreen
    >
        <div class="row">
            <div class="form">
                <div class="item">
                    <el-input placeholder="First Name" v-model="form.name" />
                    <v-error :error="errors.name"></v-error>
                </div>
                <div class="item">
                    <el-input
                        placeholder="Last Name"
                        v-model="form.last_name"
                    />
                    <v-error :error="errors.last_name"></v-error>
                </div>
                <div class="item">
                    <el-input
                        type="email"
                        v-model="form.email"
                        placeholder="Email Address"
                    />
                    <v-error :error="errors.email"></v-error>
                </div>
                <div class="item">
                    <el-select
                        v-model="form.country"
                        filterable
                        placeholder="Select"
                    >
                        <el-option
                            v-for="item in countries"
                            :key="item.value"
                            :label="item.emoji + ' ' + item.name_en"
                            :value="item.name_en"
                        />
                    </el-select>

                    <v-error :error="errors.country"></v-error>
                </div>

                <div class="item">
                    <el-input v-model="form.city" placeholder="City" />
                    <v-error :error="errors.city"></v-error>
                </div>
                <div class="item">
                    <el-input
                        v-model="form.address"
                        placeholder="Home Address"
                    />
                    <v-error :error="errors.address"></v-error>
                </div>
                <div class="item">
                    <el-input
                        v-model="form.phone"
                        style="max-width: 600px"
                        placeholder="Phone number"
                        class="input-with-select"
                    >
                        <template #prepend>
                            <el-select
                                v-model="form.dial_code"
                                placeholder="Country"
                                style="width: 115px"
                            >
                                <el-option
                                    v-for="item in countries"
                                    :key="item.value"
                                    :label="
                                        item.emoji +
                                        ' ' +
                                        item.name_en +
                                        ' ' +
                                        item.dial_code
                                    "
                                    :value="item.dial_code"
                                />
                            </el-select>
                        </template>
                    </el-input>
                    <v-error :error="errors.phone"></v-error>
                    <v-error :error="errors.dial_code"></v-error>
                </div>
                <div class="item">
                    <el-date-picker
                        v-model="birthday"
                        type="date"
                        placeholder="Pick a day"
                    />
                    <v-error :error="errors.birthday"></v-error>
                </div>
            </div>
            <div class="scopes">
                <div class="head">
                    <p>Roles</p>
                </div>
                <div class="body">
                    <div
                        class="item"
                        v-for="(item, index) in roles"
                        :key="index"
                        v-show="!item.public"
                    >
                        <el-checkbox-group v-model="form.scope" size="small">
                            <el-checkbox :value="item.id" :id="item.scope">
                                <strong>{{ item.scope }}: </strong>
                                <span>{{ item.description }}</span>
                            </el-checkbox>
                        </el-checkbox-group>
                    </div>
                </div>
            </div>
        </div>
        <template #footer>
            <div class="dialog-footer">
                <el-button type="success" @click="createUser">Register</el-button>
                <el-button type="warning" @click="close">Close</el-button>
            </div>
        </template>
    </el-dialog>
</template>
<script>
import { ElMessage } from "element-plus";

export default {
    data() {
        return {
            show_modal: false,
            birthday: null,
            form: {
                name: null,
                last_name: null,
                email: null,
                country: null,
                city: null,
                address: null,
                dial_code: null,
                phone: null,
                birthday: null,
                scope: [],
            },
            errors: {},
            roles: {},
            countries: [],
        };
    },

    watch: {
        birthday(value) {
            this.transformaDate(value);
        },
    },

    methods: {
        /**
         * Show the modal in the windowss
         */
        showModal() {
            this.show_modal = !this.show_modal;
            this.loadData();
        },

        /**
         * message
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
         * Transforma the date in specific format 'Y-m-d'
         *
         * @param date
         */
        transformaDate(date) {
            const originalDate = new Date(date);
            const year = originalDate.getFullYear();
            const month = String(originalDate.getMonth() + 1).padStart(2, "0");
            const day = String(originalDate.getDate()).padStart(2, "0");
            this.form.birthday = `${year}-${month}-${day}`;
        },

        /**
         *  reset keys when the windows is closed
         */
        close() {
            this.form = {};
            this.form.scope = [];
            this.countries = {};
            this.show_modal = false;
        },

        /**
         * Load necesary data to register new users
         */
        loadData() {
            this.getRoles();
            this.getCountries();
        },

        /**
         * Get the all roles
         */
        async getRoles() {
            try {
                const res = await this.$server.get("/api/admin/roles");

                if (res.status == 200) {
                    this.roles = res.data.data;
                }
            } catch (e) {}
        },

        /**
         * Create a new user in the system
         *
         */
        async createUser() {
            try {
                const res = await this.$server.post(
                    "/api/admin/users",
                    this.form
                );

                if (res.status == 201) {
                    this.popup(
                        "New user has been registered successfully",
                        "success"
                    );
                    this.form = { scope: [] };
                    this.errors = {};
                }
            } catch (e) {
                if (
                    e.response &&
                    e.response.data.errors &&
                    e.response.status == 422
                ) {
                    this.errors = e.response.data.errors;
                }
            }
        },

        /**
         * Get the all contries
         */
        async getCountries() {
            try {
                const res = await this.$server.get("/api/locations/countries");

                if (res.status == 200) {
                    this.countries = res.data;
                }
            } catch (e) {}
        },
    },
};
</script>

<style lang="scss" scoped>
.row {
    .form {
        display: flex;
        flex-wrap: wrap;

        .item {
            flex: 1 1 calc(95% / 3);
            padding: 0.5em;
        }
    }

    .scopes {
        .head {
            p {
                font-size: 1.3em;
                margin-bottom: 0;
            }
        }

        .body {
            display: flex;
            flex-wrap: wrap;

            .item {
                flex: 1 0 calc(95% / 2);
            }
        }
    }
}
</style>
