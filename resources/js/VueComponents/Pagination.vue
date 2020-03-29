<template>
    <ul class="pagination justify-content-center">
        <li class="page-item" v-if="pagination.current_page > 1">
            <a href="javascript:void(0)" class="page-link" aria-label="Previous" v-on:click.prevent="changePage(pagination.current_page - 1)">
                <span aria-hidden="true">«</span>
            </a>
        </li>
        <li class="page-item" v-for="page in pagesNumber" :class="{'active': page == pagination.current_page}">
            <a href="javascript:void(0)" class="page-link" v-on:click.prevent="changePage(page)">{{ page }}</a>
        </li>
        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
            <a href="javascript:void(0)" class="page-link" aria-label="Next" v-on:click.prevent="changePage(pagination.current_page + 1)">
                <span aria-hidden="true">»</span>
            </a>
        </li>
    </ul>
</template>
<script>
    export default {
        name: 'pagination',
        props: {
            pagination: {
                type: Object,
                required: true
            },
            offset: {
                default: 4
            }
        },
        computed: {
            pagesNumber() {
                if (!this.pagination.to) {
                    return [];
                }
                let from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }
                let to = from + (this.offset * 2);
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }
                let pagesArray = [];
                for (let page = from; page <= to; page++) {
                    pagesArray.push(page);
                }
                return pagesArray;
            }
        },
        methods : {
            changePage(page) {
                this.pagination.current_page = page;
                this.$emit('paginate');
            }
        }
    }
</script>