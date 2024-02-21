<template>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <th
                    class="text-uppercase"
                    v-for="(item, index) in items"
                    :key="index"
                    v-text="item"
                ></th>
            </thead>
            <tbody>
                <slot name="body"></slot>
            </tbody>
        </table>
    </div>
</template>
<script>
export default {
    props: ["items"],
};
</script>
