<template>
    <q-page padding>
        <div class="q-pa-md">
            <div class="row items-center q-col-gutter-md">
                <div class="col-auto">
                    <q-icon name="mdi-account" size="120px" color="primary" />
                </div>

                <div class="col">
                    <div class="text-h4 text-weight-bold">
                        Welcome {{ $user.name }} {{ $user.last_name }}
                    </div>
                    <div class="text-subtitle1 text-grey-7 q-mt-sm">
                        Hello, {{ $user.name }}! We're glad you're here. What
                        would you like to do today? Below are several options to
                        manage your account and set it up as you prefer.
                    </div>
                </div>
            </div>

            <q-list bordered separator class="rounded-borders q-mt-lg">
                <q-item
                    v-for="link in links"
                    :key="link.route"
                    clickable
                    @click="$router.push({ name: link.route })"
                >
                    <q-item-section>
                        <q-item-label>{{ link.name }}</q-item-label>
                    </q-item-section>
                    <q-item-section side>
                        <q-icon name="mdi-chevron-right" />
                    </q-item-section>
                </q-item>
            </q-list>
        </div>
    </q-page>
</template>

<script>
export default {
    inject: ["$user"],
    data() {
        return {
            links: [
                { name: "Personal Information", route: "personal_information" },
                { name: "Change Password", route: "security" },
                {
                    name: "Set up two-factor authentication (2FA)",
                    route: "security",
                },
                { name: "View my subscriptions", route: "payment" },
                { name: "Contact support", route: "help" },
                { name: "Privacy preferences", route: "privacy" },
            ],
        };
    },
};
</script>
