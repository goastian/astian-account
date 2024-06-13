<template>
    <div class="card">
        <div class="head">Add new clients</div>
        <div class="body">
            <div class="item">
                <input
                    type="text"
                    v-model="client.name"
                    class="input"
                    placeholder="Application Name"
                />
                <v-error :error="errors.name"></v-error>
            </div>
            <div class="item">
                <input
                    type="text"
                    v-model="client.redirect"
                    class="input"
                    placeholder="https://app.dominio.dom/callback"
                />
                <v-error :error="errors.redirect"></v-error>
            </div>
            <div class="item">
                <input
                    type="checkbox"
                    id="confidential"
                    v-model="client.confidential"
                />
                <label for="confidential">
                    Private Client (<strong>By default Public Client</strong>)
                </label>
                <v-error :error="errors.confidential"></v-error>
            </div>
            <div class="item">
                <button class="btn btn-primary" @click="storeClients">
                    Add new client
                </button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    emits: ["clientRegistered"],

    data() {
        return {
            client: {},
            errors: {},
        };
    },

    methods: {
        storeClients(event) {
            const button = event.target;
            button.disabled = true;

            this.client.confidential =
                document.getElementById("confidential").checked;

            this.$server
                .post("/oauth/clients", this.client)
                .then((res) => {
                    this.client = {};
                    this.errors = {};
                    this.$emit("clientRegistered", res.data);
                    button.disabled = false;
                })
                .catch((e) => {
                    if (e.response && e.response.data.errors) {
                        this.errors = e.response.data.errors;
                    }
                    button.disabled = false;
                });
        },
    },
};
</script>
<style lang="scss" scoped>
.card {
    color: var(--first-color);
    width: 95%;
    margin: auto;
    border: 1px solid var(--border-color-light);
    padding: 0.5em;
    border-radius: 1em;

    .head {
        font-size: 1.2em;
    }

    .body {
        @media (min-width: 800px) {
            display: flex;
            flex-wrap: wrap;
        }
        .item {
            font-size: 0.8em;
            margin-bottom: 1%;
            text-align: start;

            @media (min-width: 800px) {
                flex: 1 1 calc(100% / 2);
            }
            @media (min-width: 940px) {
                flex: 1 1 calc(100% / 3);
            }

            &:nth-child(3) {
                display: flex;
                margin-bottom: 1%;
                @media (min-width: 800px) {
                    display: block;
                }
            }
        }
    }
}
</style>
