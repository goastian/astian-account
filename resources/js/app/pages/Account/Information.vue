<template>
    <v-user-layout>
        <q-page padding>
            <div class="container-main q-gutter-y-md q-pa-md">
                <q-toolbar class="q-ma-sm">
                    <q-toolbar-title class="text-grey-7">
                        Account information
                    </q-toolbar-title>
                </q-toolbar>
                <div class="row form items-start">
                    <div class="col-8 card form-left">
                        <div class="row q-gutter-x-md">
                            <div class="col">
                                <q-input
                                    filled
                                    dense
                                    v-model="form.name"
                                    label="First Name"
                                    hint="Maximum 100 characters"
                                    counter
                                    maxlength="100"
                                    :error="!!errors.name"
                                />
                                <v-error :error="errors.name" />
                            </div>

                            <div class="col">
                                <q-input
                                    filled
                                    dense
                                    v-model="form.last_name"
                                    label="Last Name"
                                    hint="Maximum 100 characters"
                                    counter
                                    maxlength="100"
                                    :error="!!errors.last_name"
                                />
                                <v-error :error="errors.last_name" />
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6 col-lg-4">
                            <q-input
                                filled
                                dense
                                v-model="form.email"
                                label="Email"
                                type="email"
                                hint="Must be unique and valid. Max 100 characters"
                                maxlength="100"
                                :error="!!errors.email"
                            />
                            <v-error :error="errors.email" />
                        </div>

                        <div class="row q-gutter-x-md">
                            <div class="col">
                                <q-select
                                    v-model="form.country"
                                    dense="dense"
                                    emit-value
                                    map-options
                                    :options="
                                        countries.map((c) => ({
                                            label: `${c.emoji} ${c.name_en} `,
                                            value: c.name_en,
                                        }))
                                    "
                                    label="Country"
                                    outlined
                                    :error="!!errors.country"
                                >
                                </q-select>
                                <v-error :error="errors.country"></v-error>
                            </div>

                            <div class="col">
                                <q-input
                                    filled
                                    dense
                                    v-model="form.city"
                                    label="City"
                                    hint="Optional. Max 100 characters"
                                    maxlength="100"
                                    :error="!!errors.city"
                                />
                                <v-error :error="errors.city" />
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6 col-lg-4">
                            <q-input
                                filled
                                dense
                                v-model="form.address"
                                label="Address"
                                hint="Optional. Max 150 characters"
                                maxlength="150"
                                :error="!!errors.address"
                            />
                            <v-error :error="errors.address" />
                        </div>

                        <div class="row q-gutter-x-md">
                            <div class="col">
                                <q-select
                                    v-model="form.dial_code"
                                    dense="dense"
                                    emit-value
                                    map-options
                                    :options="
                                        countries.map((c) => ({
                                            label: `${c.emoji} ${c.name_en} (${c.dial_code})`,
                                            value: c.dial_code,
                                        }))
                                    "
                                    label="Dial Code"
                                    outlined
                                    :error="!!errors.dial_code"
                                >
                                </q-select>
                                <v-error :error="errors.dial_code"></v-error>
                            </div>

                            <div class="col">
                                <q-input
                                    filled
                                    dense
                                    v-model="form.phone"
                                    label="Phone"
                                    hint="Required if dial code is filled. Must be unique. Max 25 characters"
                                    maxlength="25"
                                    :error="!!errors.phone"
                                />
                                <v-error :error="errors.phone" />
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6 col-lg-4">
                            <div class="q-mb-xs text-subtitle2">Birthday</div>
                            <VueDatePicker
                                v-model="form.birthday"
                                :enable-time-picker="false"
                                :max-date="new Date()"
                                format="yyyy-MM-dd"
                                model-type="format"
                                placeholder="YYYY-MM-DD"
                            />
                            <div class="text-caption text-grey q-mt-xs">
                                Optional. Format: YYYY-MM-DD. Must be a past date
                            </div>
                            <v-error :error="errors.birthday" />
                        </div>
                        <div class="q-mt-lg">
                            <q-btn
                                label="Submit"
                                color="primary"
                                unelevated
                                no-caps
                                @click="update"
                            />
                        </div>
                    </div>

                    <div class="col column card state">
                        <div class="column q-gutter-y-md">
                            <h3 class="subtitle">Profile Status</h3>
                            <div
                                class="row items-center no-wrap q-gutter-x-sm"
                                v-if="!form['phone']?.trim()"
                            >
                                <div class="container-icon danger">
                                    <q-icon class="icon danger" name="mdi-alert-circle-outline" />
                                </div>
                                <span>We need your phone number to help protect your account.</span>
                            </div>
                            <div
                                class="row items-center no-wrap q-gutter-x-sm"
                                v-else-if="!validatedForm()"
                            >
                                <div class="container-icon warning">
                                    <q-icon class="icon warning" name="mdi-alert" />
                                </div>
                                <span>Some information is still missing from your profile.</span>
                            </div>
                            <div v-else class="row no-wrap q-gutter-x-md">
                                <span>
                                    ✅
                                </span>
                                <span>
                                    Your profile is complete!
                                    Thank you for providing all your information. This helps keep your account secure and personalized.
                                </span>
                            </div>
                        </div>
                        <div>
                            <div class="row justify-between">
                                <span>Completed</span>
                                <span>{{ progress() }}%</span>
                            </div>
                            <div class="loader-wrapper">
                                <div class="loader-bar" :style="{ width: progress() + '%' }"></div>
                            </div>
                        </div>
                        <div v-if="!validatedForm()">
                            <span>Pending information:</span>
                            <div
                                v-for="(item, index) in info"
                            >
                                <div
                                    v-if="!form[item.name]?.trim()"
                                    :class="item.clas"
                                    class="row q-gutter-x-sm items-center"
                                >
                                    <q-icon :name="item.icon" />
                                    <span>{{ item.description }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </q-page>
    </v-user-layout>
</template>

<script>
export default {
    data() {
        return {
            form: {
                name: "",
                last_name: "",
                email: "",
                country: "",
                city: "",
                address: "",
                dial_code: "",
                phone: "",
                birthday: "",
            },
            countries: [],
            errors: {},
            info: [
                {
                    icon: 'mdi-alert-circle',
                    name: 'email',
                    description: 'correo Electronico (crítico)',
                    clas: 'danger',
                },
                {
                    icon: 'mdi-alert',
                    name: 'name',
                    description: 'Name',
                    clas: 'warning',
                },
                {
                    icon: 'mdi-alert-circle',
                    name: 'last_name',
                    description: 'LastName',
                    clas: 'danger',
                },
                {
                    icon: 'mdi-alert-circle',
                    name: 'phone',
                    description: 'Número de teléfono (crítico)',
                    clas: 'danger',
                },
                {
                    icon: 'mdi-alert-circle',
                    name: 'dial_code',
                    description: 'Codigo (crítico)',
                    clas: 'danger',
                },
                {
                    icon: 'mdi-alert',
                    name: 'country',
                    description: 'Country',
                    clas: 'warning'
                },
                {
                    icon: 'mdi-alert',
                    name: 'city',
                    description: 'City',
                    clas: 'warning'
                },
                {
                    icon: 'mdi-alert',
                    name: 'address',
                    description: 'Address',
                    clas: 'warning'
                },
                {
                    icon: 'mdi-alert',
                    name: 'birthday',
                    description: 'Birthday',
                    clas: 'warning'
                }
,           ],
        };
    },

    created() {
        this.getCountries();
    },

    mounted() {
        this.form = this.$page.props.user;
        console.log(this.form);
        this.infoo = [
            {
                icon: 'mdi-alert-circle',
                name: 'phone',
                description: 'Número de teléfono (crítico)',
            },
            {
                icon: 'mdi-alert',
                name: 'address',
                description: 'Address',
            }
        ];
        this.progress();
    },

    methods: {
        async update() {
            try {
                const res = await this.$server.put(
                    this.form.links.update,
                    this.form,
                    {
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                    }
                );
                if (res.status === 200) {
                    this.form = res.data.data;
                    this.errors = {};
                    this.$q.notify({
                        type: "positive",
                        message: "Information has been updated successfully",
                        timeout: 3000,
                    });
                }
            } catch (e) {
                if (e.response && e.response.status == 422) {
                    this.errors = e.response.data.errors;
                }
            }
        },

        async getCountries() {
            try {
                const res = await this.$server.get("/api/public/countries", {
                    params: { order_by: "name_en", order_type: "asc" },
                });
                if (res.status === 200) {
                    this.countries = res.data;
                }
            } catch (e) {}
        },

        validated(name) {
            if(this.form[name]?.trim) {
                return false;
            }
            return true;
        },

        validatedForm() {
            for(const data of this.info) {
                if(this.form[data.name] == null || this.form[data.name].trim() == '' ) {
                    return false;
                }
            }
            return true;
        },

        progress() {
            const total = this.info.length;
            let count = 0;
            for(const data of this.info) {
                if(this.form[data.name] == null || this.form[data.name].trim() == '') {
                    count += 1;
                }
            }
            count = total - count;
            return Math.round((count / total) * 100);
        }
    },
};
</script>

<style scoped>
.container-main {
    padding: 1rem;
}

.card {
    background-color: var(--q-background-primary);
    border-radius: .5rem;
    padding: 2rem 1.8rem;
}

.form {
    gap: 2rem;
}

.form-left {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.state {
    gap: 1rem;
}

.subtitle {
    font-size: 20px;
    font-weight: 700;
    line-height: 22px;
    color: var(--q-color);
}

.container-icon {
    padding: .4rem;
    border-radius: 50%;
}

.container-icon.danger {
    background-color: var(--q-color-red-light);
}

.container-icon.warning {
    background-color: var(--q-color-yellow-light);
}

.icon {
    font-size: 30px;
}

.danger {
    color: var(--q-color-red);
}

.warning {
    color: var(--q-color-yellow)
}

.loader-wrapper {
  width: 100%;
  max-width: 400px;
  height: 12px;
  background-color: #e0e0e0;
  border-radius: 6px;
  overflow: hidden;
  margin: 5px auto;
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
}

.loader-bar {
  height: 100%;
  background: linear-gradient(to right, #3498db, #2980b9);
  transition: width 0.2s ease-in-out;
}

@media (max-width: 700px) {
    .form {
        flex-direction: column-reverse;
    }

    .form-left {
        width: 100%;
    }

    .form > div:last-child {
        width: 100%;
    }

    .loader-wrapper {
        max-width: 100%;
    }
}
</style>
