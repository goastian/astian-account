<script setup>
import { ref } from "vue";

const CLOUD = process.env.MIX_MENU_CLOUD;
const CALENDAR = process.env.MIX_MENU_CALENDAR;
const CONTACTS = process.env.MIX_MENU_CONTACTS;
const NOTES = process.env.MIX_MENU_NOTES;
const OFFICE = process.env.MIX_MENU_OFFICE;
const CHAT = process.env.MIX_MENU_CHAT;
</script>

<template>
    <button
        type="button"
        class="btn btn-primary py-1 px-2"
        data-bs-toggle="modal"
        data-bs-target="#staticBackdrop"
    >
        <i class="bi bi-box h3"></i>
    </button>

    <div
        class="modal fade"
        id="staticBackdrop"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
        tabindex="-1"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <span>
                        <img
                            src="../../img/favicon.svg"
                            alt="astian icon"
                            class="icon mx-3"
                        />
                    </span>
                    <span> APPS</span>
                    <button
                        type="button"
                        class="btn-close border"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <a :href="CLOUD" class="btn btn-link">
                                <span>Cloud</span>
                                <i class="bi bi-cloud-fill"> </i>
                            </a>
                        </div>
                        <div class="col">
                            <a :href="CALENDAR" class="btn btn-link">
                                <span>Calendar</span>
                                <i class="bi bi-calendar-date"> </i>
                            </a>
                        </div>
                        <div class="col">
                            <a :href="CONTACTS" class="btn btn-link">
                                <span>Contacts</span>
                                <i class="bi bi-person-lines-fill"> </i>
                            </a>
                        </div>
                        <div class="col">
                            <a :href="NOTES" class="btn btn-link">
                                <span>Notes</span>
                                <i class="bi bi-journal-bookmark-fill"></i>
                            </a>
                        </div>
                        <div class="col">
                            <a :href="OFFICE" class="btn btn-link">
                                <span>Office</span>
                                <i class="bi bi-file-earmark-richtext-fill">
                                </i>
                            </a>
                        </div>

                        <div class="col">
                            <a :href="CHAT" class="btn btn-link">
                                <span>Chat</span>
                                <i class="bi bi-chat-dots-fill"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <a href="#" class="btn btn-link text-light border-bottom"> More Apps </a>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
a {
    text-decoration: none;
}

.col {
    flex: 0 0 auto;
    width: 48%;
    border-radius: 50%;
}
.col span {
    display: block;
}

.icon {
    width: 30px;
}

.col i {
    font-size: 50px;
    text-align: center;
}
.modal {
    --bs-modal-bg: $alpha;
    text-align: center;
    text-shadow: 15% 15% var(--skyblue);
}

.modal-content {
    background-color: var(--skyblue);
}

.modal .col a {
    width: 100%;
    border-radius: 5%;
    color: var(--white);
    padding: 0;
    margin: 0;
}

.modal .col a:hover {
    color: var(--secondary);
    text-shadow: 2px 2px 2px var(--white);
}
</style>
