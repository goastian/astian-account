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
                            src="../../../img/favicon.svg"
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
                            <a href="#" class="btn btn-link">
                                <span>Cloud</span>
                                <i class="bi bi-cloud-fill"> </i>
                            </a>
                        </div>
                        <div class="col">
                            <a href="#" class="btn btn-link">
                                <span>Calendar</span>
                                <i class="bi bi-calendar-date"> </i>
                            </a>
                        </div>
                        <div class="col">
                            <a href="#" class="btn btn-link">
                                <span>Contacts</span>
                                <i class="bi bi-person-lines-fill"> </i>
                            </a>
                        </div>
                        <div class="col">
                            <a href="#" class="btn btn-link">
                                <span>Notes</span>
                                <i class="bi bi-floppy-fill"> </i>
                            </a>
                        </div>
                        <div class="col">
                            <a href="#" class="btn btn-link">
                                <span>Office</span>
                                <i class="bi bi-file-earmark-richtext-fill">
                                </i>
                            </a>
                        </div>
                        <div class="col">
                            <a href="#" class="btn btn-link">
                                <span>Sync</span>
                                <i class="bi bi-arrow-repeat"></i>
                            </a>
                        </div>
                        <div class="col">
                            <a href="#" class="btn btn-link">
                                <span>Drive</span>
                                <i class="bi bi-hdd-rack-fill"></i>
                            </a>
                        </div>
                        <div class="col">
                            <a href="#" class="btn btn-link">
                                <span>Chat</span>
                                <i class="bi bi-chat-dots-fill"></i>
                            </a>
                        </div>
                        <div class="col">
                            <a href="#" class="btn btn-link">
                                <span>Social</span>
                                <i class="bi bi-globe"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {};
</script>
<style lang="scss" scoped>
@import "../../../scss/__colors";
a {
    text-decoration: none;
}

.btn-primary {
    background-color: $blue !important;
    border-color: $blue;
}
 
.col {
    flex: 0 0 auto;
    width: 33%;
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
    text-shadow: 15% 15% $ternary;
}

.modal-content {
    background-color: $skyblue;
}

.modal .col a {
    width: 100%;
    border-radius: 5%;
    color: rgb(197, 219, 215) !important;
    padding: 0;
    margin: 0;
}

.modal .col a:hover {
    background-color: rgba(236, 64, 93, 0.637);
    text-shadow: 4px 4px 4px rgba(213, 230, 219, 0.548);
}
</style>
