<template>
    <el-button type="success" @click="showModal(user)"> Details </el-button>

    <el-dialog
        v-model="show_modal"
        title="Panel to update user information"
        draggable
        destroy-on-close
        append-to-body
        fullscreen
    >
        <div class="row">
            <div class="col">
                <label class="text-sm fw-bold">First Name</label>
                <el-input v-model="form.name" />
                <v-error :error="errors.name"></v-error>
            </div>
            <div class="col">
                <label class="text-sm fw-bold">Last Name</label>
                <el-input v-model="form.last_name" />
                <v-error :error="errors.last_name"></v-error>
            </div>
            <div class="col">
                <label class="text-sm fw-bold">Email Address</label>
                <el-input v-model="form.email" />
                <v-error :error="errors.email"></v-error>
            </div>

            <div class="col">
                <label class="text-sm fw-bold">Phone Number</label>
                <el-input
                    v-model="form.phone"
                    style="max-width: 600px"
                    placeholder="Please input"
                    class="input-with-select"
                >
                    <template #prepend>
                        <el-select
                            v-model="form.dial_code"
                            placeholder="Select"
                            style="width: 115px"
                        >
                            <el-option
                                v-for="item in countries"
                                :key="item.value"
                                :label="item.emoji + ' ' + item.name_en"
                                :value="item.dial_code"
                            />
                        </el-select>
                    </template>
                </el-input>

                <v-error :error="errors.dial_code"></v-error>
                <v-error :error="errors.phone"></v-error>
            </div>

            <div class="col">
                <label class="text-sm fw-bold" for="">Country</label>
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
            <div class="col">
                <label class="text-sm fw-bold">City Or State</label>
                <el-input v-model="form.city" placeholder="city or state" />
                <v-error :error="errors.city"></v-error>
            </div>
            <div class="col">
                <label class="text-sm fw-bold">Home Address</label>
                <el-input v-model="form.address" placeholder="Home Address" />
                <v-error :error="errors.address"></v-error>
            </div>
            <div class="col">
                <label class="text-sm fw-bold">Date of birth</label>
                <el-date-picker
                    v-model="birthday"
                    type="date"
                    placeholder="Pick a day"
                />

                <v-error :error="errors.birthday"></v-error>
            </div>
            <div class="col">
                <label class="label text-sm fw-bold">Join us</label>
                <span class="text-sm">{{ form.created }}</span>
            </div>
            <div class="col">
                <label class="label text-sm fw-bold">Last Update</label>
                <span class="text-sm">{{ form.updated }}</span>
            </div>
        </div>

        <template #footer>
            <div class="dialog-footer">
                <el-button type="info" @click="update(user)">Update</el-button>
                <el-button type="warning" @click="show_modal = false"
                    >Close</el-button
                >
            </div>
        </template>
    </el-dialog>
</template>
<script>
import { ElMessage } from "element-plus";
export default {
    props: ["user"],

    data() {
        return {
            show_modal: false,
            birthday: null,
            errors: {},
            countries: [],
            form: {},
        };
    },

    watch: {
        birthday(value) {
            this.transformaDate(value);
        },
    },

    methods: {
        /**
         * Show the modal
         */
        showModal(user) {
            this.loadData(user);
            this.show_modal = !this.show_modal;
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
         * Load data
         */
        loadData(user) {
            this.form = user;
            this.birthday = user.birthday;
            this.getCountries();
        },

        /**
         * Update user
         * @param {*} item
         */
        async update(item) {
            try {
                const res = await this.$server.put(
                    item.links.update,
                    this.form
                );

                if ([200, 201].includes(res.status)) {
                    this.popup("User has been updated.", "success");

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
                if (e.response && e.response.status == 403) {
                    this.popup(
                        "Without authorization to perform this action",
                        "warning"
                    );
                }
            }
        },

        /**
         * Get the all countries
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
    display: flex;
    flex-wrap: wrap;
    .col {
        flex: 1 1 calc(95% / 2);
        padding: 0.5em;
    }
}
</style>
