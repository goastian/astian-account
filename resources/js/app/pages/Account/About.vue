<template>
    <q-toolbar>
        <q-toolbar-title>Home</q-toolbar-title>
    </q-toolbar>
    <div class="column q-gutter-y-xl q-pa-lg">
        <div class="row no-wrap items-center q-gutter-x-lg">
            <div class="img">
                <q-icon name="mdi-account" size="120px"/>
            </div>
            <div class="column greet">
                <h2>Welcome {{ $user.name }} {{ $user.last_name }}</h2>
                <span>Hello, {{ $user.name }}! We're glad you're here. What would you like to do today? Below are several options to manage your account and set it up as you prefer.</span>
            </div>
        </div>
        <!--
        <div class="options card">
            <span class="tag">Options</span>
            <router-link
                v-for="(item, index) in links"
                :key="index"
                :to="item.route"
            >
                {{ item.name }}
            </router-link>
        </div>
        -->
        <div class="apps card">
            <span class="tag">Apps</span>
            <a
                v-for="(item, index) in apps"
                :key="index"
                :href="item.route"
                target="_blank"
            >
                <q-icon :name="item.icon" size="20px" />
                {{ item.name }}
            </a>
        </div>
    </div>
</template>

<script>
export default {
    inject: ["$user"],
    data() {
        return {
            links: [
                {
                    name: 'Personal Information',
                    route: 'personal_information'
                },
                {
                    name: 'Change Password',
                    route: 'security'
                },
                {
                    name: 'Set up two-factor authentication (2FA)',
                    route: 'security'
                },
                {
                    name: 'View my subscriptions',
                    route: 'payment'
                },
                {
                    name: 'Contact support',
                    route: 'help'
                },
                {
                    name: 'Privacy preferences',
                    route: 'privacy'
                }
            ],
            apps: [
                {
                    name: 'Contacts',
                    route: 'https://www.google.com',
                    icon: 'mdi-contacts'
                },
                {
                    name: 'Cloud',
                    route: 'https://www.google.com',
                    icon: 'mdi-cloud-check-outline'
                },
                {
                    name: 'AstianGO',
                    route: 'https://www.google.com',
                    icon: 'mdi-magnify'
                },
                {
                    name: 'Calendar',
                    route: 'https://www.google.com',
                    icon: 'mdi-calendar-blank-outline'

                },
                {
                    name: 'Midori',
                    route: 'https://www.google.com',
                    icon: 'mdi-earth'
                },
                {
                    name: 'Notes',
                    route: 'https://www.google.com',
                    icon: 'mdi-note-multiple-outline'
                },
                {
                    name: 'AstianVPN',
                    route: 'https://www.google.com',
                    icon: 'mdi-shield-outline'
                }
            ]
        }
    }
};
</script>

<style scoped>
.img {
    border-radius: 50%;
    background-color: var(--bg-link-active);
    color: var(--text-link-active);
}
.img .q-icon {
    width: 100%;
}

.greet h2 {
    font-size: 2rem;
}

.greet span {
    width: 100%;
    min-width: 100px;
    max-width: 800px;
}

.card {
    border: 1px solid var(--border-color);
    color: var(--text);
    padding: 2rem 4rem;
    position: relative;
    border-radius: 1rem;
}

.options {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
}

.tag {
    width: 70px;
    text-align: center;
    position: absolute;
    top: -12px;
    left: 35px;
    background-color: var(--bg-main);
}

.options a {
    display: block;
    text-align: left;
    padding: 1rem;
    border-radius: .5rem;
}

.apps {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2rem;
}

.apps .q-icon {
    padding: .5rem;
    border-radius: 50%;
    background-color: var(--bg-link-active);
    color: var(--text-link-active);
}

@media (max-width: 800px) {
    .options {
        grid-template-columns: repeat(2, 1fr);
        row-gap: .5rem;
        padding: 1rem 2rem;
    }
}

</style>
