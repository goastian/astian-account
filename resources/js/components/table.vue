<template>
    <div class="table">
        <p class="head">
            <slot name="title"></slot>
        </p>
        <div class="table-responsive">
            <table>
                <thead>
                    <slot name="head"></slot>
                </thead>
                <tbody>
                    <slot name="body"></slot>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
export default {};
</script>

<style lang="scss" scoped>
.table {
    border: 1px solid var(--border-color-light);
    font-size: 0.8em;
    color: var(--first-color);
    border-radius: 0.5em;
    margin: 1% auto;
    padding: 0.5em;

    @media (min-width: 800px) {
        width: 95%;
    }

    .head {
        font-size: 1.3em;
        font-weight: bold;
        margin-bottom: 0;
    }

    .table-responsive {
        overflow: scroll;
        table {
            font-size: 0.8em;
            width: 100%;
            @media (min-width: 800px) {
                font-size: 1em;
            }

            thead {
            }

            tbody {
            }
        }
    }
}
</style>
