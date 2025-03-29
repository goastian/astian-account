<template>
    <v-header title="Dashboard" />
    <div class="column q-gutter-y-md q-pa-md">
        <div class="row">
            <div class="column col q-gutter-y-md">
                <div class="container-welcome rounded pa-md card">
                    <div class="img flex justify-center items-center">
                        <q-icon name="mdi-account" size="40px"/>
                    </div>
                    <div class="column greet w-full">
                        <div class="row no-wrap justify-between items-center">
                            <span class="inline ">Welcome, back</span>
                            <span class="inline tag">Verified</span>
                        </div>
                        <div>
                            <h2>¡Hello, {{ $user.name }} {{ $user.last_name }}!</h2>
                            <span class="welcome__description">
                                We're glad you're here.  What would you like to do today? Below are several options to manage  your account and set it up as you prefer.
                            </span>
                        </div>
                    </div>
                </div>
                <div class="Container-apps column card ga-y-md">
                    <h3 class="subtitle">Apps</h3>
                    <div
                        class="apps"
                        :class="{ 'appsIsNavShow' : state.isVisible, 'appsIsNav' : !state.isVisible }"
                    >
                        <a
                            v-for="(item, index) in apps"
                            :key="index"
                            :href="item.route"
                            target="_blank"
                            class="cardApp"
                        >
                            <q-icon :name="item.icon" :class="item.color" size="26px" />
                            <h4>{{ item.name }}</h4>
                            <span>{{ item.description }}</span>
                        </a>
                    </div>
                </div>
            </div>
            <!--
            <div class="col card ga-y-md">
                <h3 class="subtitle">Recent Activity</h3>
            </div>
            -->
        </div>
        <!--
        <div class="row q-gutter-x-md">
            <div class="col card ga-y-md">
                <h3 class="subtitle">Account Security</h3>
                <div class="column q-gutter-y-md">
                    <div
                        class="row card-default justify-between items-center"
                        v-for="(item, index) in security"
                        :key="index"
                    >
                        <div class="row items-center q-gutter-x-md">
                            <q-icon :name="item.icon" class="icon" size="21px" />
                            <div>
                                <h4>{{ item.title }}</h4>
                                <span>{{ item.description }}</span>
                            </div>
                        </div>
                        <div>
                            <a>{{ item.btnTitle }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col card ga-y-md">
                <h3 class="subtitle">Linked Devices</h3>
                <div class="column q-gutter-y-md">
                    <div
                        class="row card-default justify-between items-center"
                        v-for="(item, index) in devices"
                        :key="index"
                    >
                        <div class="row items-center q-gutter-x-md">
                            <q-icon :name="item.icon" class="icon" size="21px" />
                            <div>
                                <h4>{{ item.title }}</h4>
                                <span>{{ item.date }} - {{ item.ubication }}</span>
                            </div>
                        </div>
                        <div>
                            <a v-if="item.btnTitle">{{ item.btnTitle }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        -->
    </div>
</template>

<script>
export default {
    inject: ["$user", "state"],
    data() {
        return {
            security: [
                {
                    title: 'Password',
                    description: 'Updated 2 months ago',
                    icon: 'mdi-key-outline',
                    btnTitle: 'change',
                },
                {
                    title: 'Two-factor authentication',
                    description: 'Not Enabled',
                    icon: 'mdi-cellphone',
                    btnTitle: 'enable'
                },
                {
                    title: 'Security Verification',
                    description: 'Last Verification: January 1, 2025',
                    icon: 'mdi-check',
                    btnTitle: 'Verify'
                }
            ],
            devices: [
                {
                    title: 'windows 10',
                    date: 'Now',
                    ubication: 'Tumbes - Perú',
                    icon: 'mdi-monitor',

                },
                {
                    title: 'Iphone 13',
                    date: '2 Hours Ago',
                    ubication: 'Tumbes - Perú',
                    icon: 'mdi-cellphone',
                    btnTitle: 'Disconnect'
                },
                {
                    title: 'Iphone 10',
                    date: '8 Hours Ago',
                    ubication: 'Tumbes - Perú',
                    icon: 'mdi-cellphone',
                    btnTitle: 'Disconnect'
                }
            ],
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
                    route: 'https://contacts.astian.org',
                    icon: 'mdi-contacts',
                    description: 'Store and access your contacts.',
                    color: 'teal',
                },
                {
                    name: 'AstianGO',
                    route: 'https://astiango.co',
                    icon: 'mdi-magnify',
                    description: 'Find information quickly',
                    color: 'orange',
                },
                {
                    name: 'Cloud',
                    route: 'https://cloud.astian.org',
                    icon: 'mdi-cloud-check-outline',
                    description: 'Store and share files',
                    color: 'purple'
                },
                {
                    name: 'Calendar',
                    route: 'https://calendar.astian.org',
                    icon: 'mdi-calendar-blank-outline',
                    description: 'Organize your events and reminders',
                    color: 'green'

                },
                {
                    name: 'Midori',
                    route: 'https://astian.org/midori-browser/',
                    icon: 'mdi-earth',
                    description: 'Browse fast and secure',
                    color: 'teal'
                },
                {
                    name: 'Notes',
                    route: 'https://notes.astian.org',
                    icon: 'mdi-note-multiple-outline',
                    description: 'Take notes and organize ideas',
                    color: 'orange'
                },
                {
                    name: 'AstianVPN',
                    route: 'https://vpn.astian.org',
                    icon: 'mdi-shield-outline',
                    description: 'Browse securely and privately',
                    color: 'purple',
                }
            ]
        }
    }
};
</script>

<style scoped>
.ga-y-md {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.icon {
    background-color: var(--primary-bg);
    color: var(--primary-color);
    padding: .5rem;
    border-radius: 50%;
}

.card-default > div:last-child > a {
    border-radius: .4rem;
    border: .04rem solid var(--primary-color);
    color: var(--primary-color);
    padding: .5rem 1rem;
}

.card-default > div:first-child > div > h4 {
    font-size: 14px;
    font-weight: 600;
    margin: 0;
    line-height: 20px;
}

.card-default > div:first-child > div > span {
    font-size: 13px;
    font-weight: 500;
    color: var(--color-gray);
}

.card-default {
    border-radius: .4rem;
    border: .04rem solid var(--border-color);
    padding: .5rem 1rem;
}

.container-welcome {
    display: flex;
    gap: 1rem;
}

.welcome__description {
    width: 100%;
    min-width: 20px;
    max-width: 100%;
}

@media (max-width: 900px) {

}

.greet > div:first-child > span:first-child {
    color: var(--color-gray);
    font-size: 12px;
}

.greet > div:last-child > h2 {
    font-size: 20px;
    margin: 0;
    line-height: 30px;
    font-weight: 600;
}

.greet > div:last-child > span {
    font-size: 13px;
}

.orange {
    background: linear-gradient(to bottom, #FF7478, #A74547);
    color: white;
}

.purple {
    background: linear-gradient(to bottom, #FC30AA, #9C1867);
    color: white;
}

.green {
    background: linear-gradient(to bottom, #7FDD00, #559500);
    color: white;
}

.teal {
    background: linear-gradient(to bottom, #00B9B4, #009793);
    color: white;
}

.img {
    border-radius: 50%;
    background-color: var(--primary-color);
    color: white;
    width: 74px;
    height: 60px;
}

.card {
    border: 1px solid var(--border-color);
    color: var(--text);
    padding: 1rem;
    position: relative;
    border-radius: 1rem;
    background-color: white;
}

.tag {
    width: auto;
    padding: .2rem 1rem;
    background-color: var(--green-secondary);
    border-radius: .4rem;
    color: var(--green-primary);
    border: .04rem solid var(--green-primary);
    font-size: 12px;
}

.inline {
    display: inline;
}

.subtitle {
    font-size: 16px;
    margin: 0;
    line-height: 22px;
    font-weight: 600;
}

.apps {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2rem;
}

.apps .q-icon {
    padding: .5rem;
    border-radius: .4rem;
}

.cardApp {
    min-width: 20px;
    width: 100%;
    max-width: 400px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: .8rem 1.4rem;
    border-radius: .8rem;
    border: .04rem solid var(--border-color);
}

.cardApp > h4 {
    font-size: 14px;
    font-weight: 600;
}

.cardApp > span {
    font-size: 13px;
    color: var(--color-gray);
}

@media (max-width: 1125px) {
    .appsIsNavShow {
        grid-template-columns: repeat(3, 1fr);

    }
}

@media (max-width: 918px) {

    .appsIsNavShow {
        grid-template-columns: repeat(2, 1fr);
    }

}

@media (max-width: 858px) {
    .appsIsNav {
        justify-content: center;
        grid-template-columns: repeat(3, 1fr);

    }
}

@media (max-width: 716px) {
    .appsIsNavShow {
        grid-template-columns: repeat(1, 1fr);

    }
}

@media (max-width: 658px) {
    .appsIsNav {
        grid-template-columns: repeat(2, 1fr);

    }
}

@media (max-width: 462px) {
    .appsIsNav {
        grid-template-columns: repeat(1, 1fr);
        place-items: center;
    }
}

</style>
