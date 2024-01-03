<template>
    <div class="container-fluid">
        <div class="row">
            <div
                class="col-3 border-left"
                style="border-right: 1px outset white"
            >
                <ul class="nav flex-column">
                    <router-link class="nav-link" :to="{ name: 'home.about' }">
                        Profile
                    </router-link>

                    <router-link class="nav-link" :to="{ name: 'home.revoke' }">
                        Revoke Credentials
                    </router-link>
                    <router-link class="nav-link" :to="{ name: 'home.2fa' }">
                        Manage 2FA
                    </router-link>
                    <router-link class="nav-link" :to="{ name: 'home.totp' }">
                        Manage TOTP
                    </router-link>
                </ul>
            </div>
            <div class="col-9">
                <router-view />
            </div>
        </div>
    </div>
</template>
<script>
export default {
     
};
</script>
<style lang=""></style>
