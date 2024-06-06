<template>
    <div class="profile">
        <div class="head">
            <p>
                About Me
                <span class="h5 fw-light text-primary">
                    <i class="bi bi-person-check h2"></i>
                </span>
            </p>
        </div>
        <div class="body">
            <div class="items">
                <p>
                    <i class="bi bi-person-bounding-box text-primary"></i>
                    {{ user.name }}
                    {{ user.last_name }}
                </p>
                <p>
                    <i class="bi bi-envelope-at-fill text-primary"></i>
                    {{ user.email }}
                </p>
                <p v-show="user.birthday">
                    <i class="bi bi-cake2 text-primary"></i>
                    {{ user.birthday }}
                </p>
                <p v-show="user.phone">
                    <i class="bi bi-telephone-plus text-primary"></i>
                    {{ user.phone }}
                </p>
                <p v-show="user.country">
                    <i class="bi bi-houses text-primary"></i>
                    {{ user.country }} - {{ user.city }} -

                    {{ user.address }}
                </p>
                <p>
                    <i class="bi bi-arrow-through-heart text-primary"></i>
                    Join us {{ user.created }}
                </p>

                <v-update
                    styles="btn btn-link float-end"
                    :user="user"
                    @success="authenticated"
                ></v-update>
            </div>
            <div class="scopes">
                <p class="title">
                    My Scopes <i class="bi bi-shield-lock h3 text-primary"></i>
                </p>
                <p class="items" v-for="(item, index) in roles" :key="index">{{ item.id }}</p>
            </div>
        </div>
    </div>

    <v-message :message="message" @close="close"></v-message>
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
            message: null,
            sessions: {},
        };
    },

    created() {
        this.authenticated();
        this.scopes();
    },

    methods: {
        authenticated() {
            this.$server
                .get("/api/gateway/user")
                .then((res) => {
                    this.user = res.data;
                    window.$auth = res.data;
                })
                .catch((e) => {
                    if (e.response && e.response.status == 401) {
                        window.location.href = "/login";
                    }
                });
        },

        close() {
            this.message = null;
        },

        showMessage(event) {
            if (!event.status) {
                this.message = event;
                setTimeout(() => {
                    window.location.href = window.location.host;
                }, 5000);
            }
            this.message = event.data.message;
        },

        scopes() {
            this.$server
                .get("/api/oauth/scopes")
                .then((res) => {
                    this.roles = res.data;
                })
                .catch((e) => {
                    if (e.response && e.response.status == 401) {
                        window.location.href = "/login";
                    }
                });
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
    margin: auto;
    border: 1px solid var(--border-color-light);
    border-radius: 1em;
    padding: 1em;
    color: var(--first-color);

    .head {
        font-size: 1.2em;

        p {
            font-weight: bold;
            i {
                font-size: 1.3em;
            }
        }
    }

    .body {
        .items {
            display: flex;
            flex-wrap: wrap;

            p {
                flex: 1 1 calc(100% / 3);
            }
        }

        .scopes {
            display: flex;
            flex-wrap: wrap;

            .title {
                flex: 1 1 100%;
                font-size: 1.2em;
                font-weight: bold;
            }

            .items {
                flex: 1 1 auto;
                margin: 0.3% 0;
            }
        }
    }
}
</style>
