<template>
    <div class="table-responsive">
        <table class="table table-hover table-dark">
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
<styles scoped lang="css">
.table {
    font-size: 12px !important;
    padding: 0px;
    margin: 0px;
}

thead {
    padding: 0px;
    margin: 0px;
}

tbody {
    padding: 0px;
    margin: 0px;
}
</styles>
