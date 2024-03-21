<script setup>
import { ref } from "vue";

const CLOUD = ref(process.env.MIX_MENU_CLOUD);
const CALENDAR = ref(process.env.MIX_MENU_CALENDAR);
const CONTACTS = ref(process.env.MIX_MENU_CONTACTS);
const NOTES = ref(process.env.MIX_MENU_NOTES);
const OFFICE = ref(process.env.MIX_MENU_OFFICE);
const CHAT = ref(process.env.MIX_MENU_CHAT);
</script>

<template>
    <button
        type="button p-0 m-0"
        class="btn btn-sm text-light"
        data-bs-toggle="modal"
        data-bs-target="#apps"
    >
        <i class="bi bi-box h5"></i>
    </button>

    <div
        class="modal fade"
        id="apps"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
        tabindex="-1"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <span> APPS</span>
                    <button
                        type="button"
                        class="btn-close border"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <ul>
                        <li>
                            <a class="text-decoration-none" :href="CLOUD">
                                Cloud
                                <i class="bi bi-cloud-fill"> </i>
                            </a>
                        </li>
                        <li>
                            <a class="text-decoration-none" :href="CALENDAR">
                                Calendar
                                <i class="bi bi-calendar-date"> </i>
                            </a>
                        </li>
                        <li>
                            <a class="text-decoration-none" :href="CONTACTS">
                                Contacts
                                <i class="bi bi-person-lines-fill"> </i>
                            </a>
                        </li>
                        <li>
                            <a class="text-decoration-none" :href="NOTES">
                                Notes
                                <i class="bi bi-journal-bookmark-fill"></i>
                            </a>
                        </li>
                        <li>
                            <a class="text-decoration-none" :href="OFFICE">
                                Office
                                <i class="bi bi-file-earmark-richtext-fill">
                                </i>
                            </a>
                        </li>
                        <li>
                            <a class="text-decoration-none" :href="CHAT">
                                Chat
                                <i class="bi bi-chat-dots-fill"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer text-center">
                    <a href="#" class="btn text-light border-bottom">
                        More Apps
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.modal {
    --bs-modal-bg: var(--apps-bg) !important;
    --bs-modal-color: var(--apps-title) !important;
}

ul a {
    color: inherit;
    padding: 1%;
}

ul a:hover {
    color: var(--apps-hover-color);
}

ul a i {
    font-size: 3em;
    display: block;
}

ul {
    padding: 0;
    margin: 0;
    list-style: none;
}

ul li {
    display: inline-block;
    width: 25%;
    padding: 4% 1%;
}
</style>
