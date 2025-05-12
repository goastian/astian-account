<template>
    <v-partner-layout>
        <q-page padding class="q-pa-md">
            <q-card class="q-pa-md shadow-2 rounded-borders">
                <q-card-section>
                    <div class="text-h6">Generate Referral Link</div>
                </q-card-section>

                <q-card-actions class="flex q-pa-md">
                    <span class="text-grey-5">
                        <strong>Commission rate : </strong>
                        {{ partner?.commission_rate }} %
                    </span>
                    <q-space />
                    <q-btn
                        label="Generate"
                        color="primary"
                        @click="generateReferralLink"
                    />
                </q-card-actions>
                <q-separator />
                <q-card-section
                    v-if="
                        partner &&
                        Object.keys(partner.referral_links || {}).length
                    "
                >
                    <div
                        v-for="(url, key) in partner.referral_links"
                        :key="key"
                        class="q-mb-md"
                    >
                        <q-input readonly :label="key" :model-value="url" dense>
                            <template v-slot:append>
                                <q-btn
                                    flat
                                    icon="content_copy"
                                    @click="copyToClipboard(url)"
                                />
                            </template>
                        </q-input>
                    </div>

                    <q-banner
                        dense
                        class="bg-green-2 text-green-9"
                        v-if="copied"
                    >
                        Link copied to clipboard!
                    </q-banner>
                </q-card-section>
            </q-card>
        </q-page>
    </v-partner-layout>
</template>

<script>
export default {
    data() {
        return {
            partner: {
                referral_links: {},
            },
            copied: false,
        };
    },

    mounted() {
        this.partner = this.$page.props.partner;
    },

    methods: {
        async generateReferralLink() {
            try {
                const res = await this.$server.post(this.$page.props.route);
                if (res.status == 201) {
                    this.partner = res.data.data;
                }
            } catch (error) {}
        },

        copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                this.copied = true;
                setTimeout(() => (this.copied = false), 2000);
            });
        },
    },
};
</script>
