<template>
    <div class="card">
        <div class="head">Create new token</div>
        <div class="body">
            <div class="item">
                <div>
                    <input
                        type="text"
                        placeholder="Token Name"
                        v-model="name"
                        class="input"
                    />
                    <v-error :error="errors.name"></v-error>
                </div>
                <div>
                    <button class="btn btn-primary" @click="createToken">
                        add token
                    </button>
                </div>
            </div>

            <div class="scopes">
                <p>Scopes</p>
                <div class="items" v-for="(item, index) in scopes" :key="index">
                    <div>
                        <input
                            class="form-check-input"
                            type="checkbox"
                            :value="item.id"
                            :id="item.id"
                            @click="isSelected(item.id)"
                        />
                    </div>
                    <div>
                        <label class="" :for="item.id">
                            <strong>{{ item.id }}</strong>
                            : {{ item.description }}
                        </label>
                    </div>
                </div>
            </div>

            <div class="token">
                <label for="token">Access token generated</label>
                <textarea
                    name="token"
                    id="token"
                    v-model="tokens.accessToken"
                    class="textarea"
                ></textarea>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    emits: ["tokenCreated"],

    data() {
        return {
            name: "",
            tokens: {},
            errors: {},
            scopes: {},
            scopesSelected: {},
        };
    },

    mounted() {
        this.getScopes();
        this.scopesSelected = [];
        this.listenEvent();
    },

    methods: {
        async createToken(event) {
            const button = event.target;
            button.disabled = true;

            try {
                const res = await this.$server.post(
                    "/oauth/personal-access-tokens",
                    {
                        name: this.name,
                        scopes: this.scopesSelected,
                    }
                );

                if (res.status == 200) {
                    this.tokens = res.data;
                    this.errors = {};
                    this.scopesSelected = [];
                    this.name = null;
                    this.$emit("tokenCreated", res.data);
                    /**
                     * clean checked
                     */
                    let element =
                        document.getElementsByClassName("form-check-input");
                    for (let index = 0; index < element.length; index++) {
                        element[index].checked = false;
                    }

                    button.disabled = false;
                }
            } catch (e) {
                if (e.response && e.response.status == 422) {
                    this.errors = e.response.data.errors;
                }
                button.disabled = false;
            }
        },

        isSelected(id) {
            const position = this.scopesSelected.indexOf(id);
            if (position != -1) {
                this.scopesSelected.splice(position, 1);
            } else {
                this.scopesSelected.push(id);
            }
        },

        getScopes() {
            this.$server
                .get("/api/oauth/scopes")
                .then((res) => {
                    this.scopes = res.data;
                })
                .catch((e) => {});
        },

        listenEvent() {
            this.$echo
                .private(this.$channels.ch_0())
                .listen(".StoreRoleEvent", (e) => {
                    this.getScopes();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen(".UpdateRoleEvent", (e) => {
                    this.getScopes();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen(".DestroyRoleEvent", (e) => {
                    this.getScopes();
                });
        },
    },
};
</script>

<style lang="scss" scoped>
.card {
    width: 95%;
    border: 1px solid var(--border-color-light);
    border-radius: 1em;
    color: var(--first-color);
    padding: 0.5em;
    margin: auto;

    .head {
        font-size: 1.2em;
        font-weight: bold;
    }
    .body {
        .item {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 1%;

            div {
                flex: 1 1 100%;

                @media (min-width: 800px) {
                    flex: 1 1 calc(100% / 2);
                    justify-content: center;
                    align-content: center;
                }
            }
        }
        .scopes {
            border: 1px solid var(--border-color-light);
            padding: 0.5em;
            border-radius: 0.5em;
            display: flex;
            flex-wrap: wrap;

            p {
                font-weight: bold;
                margin: 0.4%;
                flex: 1 1 100%;
            }

            .items {
                flex: 0 1 100%;

                @media (min-width: 800px) {
                    flex: 0 1 calc(100% / 2);
                }
                display: flex;
                font-size: 0.9em;
            }
        }

        .token {
            margin-top: 1%;
            label {
                display: block;
                font-weight: bold;
            }

            textarea {
                height: 100px;
            }
        }
    }
}
</style>
