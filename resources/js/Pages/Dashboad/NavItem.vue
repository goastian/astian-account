<template>
    <li class="nav-item">
        <router-link :to="path" class="nav-link" aria-current="page">
            <slot></slot>
        </router-link>
    </li>
</template>
<script>
export default {
    props: {
        path: {
            type: [Object, String],
            default: "#",
        },
    },
};
</script>
