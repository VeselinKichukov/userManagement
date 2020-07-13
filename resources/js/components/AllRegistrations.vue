<template>
    <div id="registrations">
        <div class="registrations-style">
            <div style="margin-bottom: 20px;">
                <h2>Registrations Table</h2>
            </div>
            <div class="table-style">
                <input class="input"
                       type="text"
                       v-model="search"
                       placeholder="Search..."
                       @input="resetPagination()"
                       style="width: 250px;">
                <div class="control">
                    <div class="select">
                        <select v-model="length" @change="resetPagination()">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                        </select>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-responsive">
                <thead>
                <tr>
                    <th v-for="column in columns"
                        :key="column.name"
                        @click="sortBy(column.name)"
                        :class="sortKey === column.name ? (sortOrders[column.name] > 0 ? 'sorting_asc' : 'sorting_desc') : 'sorting'"
                        style="width: 40%; cursor:pointer;">
                        {{column.label}}
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="registration in paginatedRegistrations"
                    :key="registration.id">
                    <td>{{registration.user_name}}</td>
                    <td>{{registration.user_id}}</td>
                    <td>{{registration.start_time}}</td>
                    <td>{{registration.end_time}}</td>
                    <td>{{registration.created_at}}</td>
                </tr>
                </tbody>
            </table>
            <div>
                <nav class="pagination" v-if="!tableShow.showdata">
                    <span class="page-stats">{{pagination.from}} - {{pagination.to}} of {{pagination.total}}</span>
                    <a v-if="pagination.prevPageUrl"
                       class="btn btn-sm btn-primary pagination-previous"
                       @click="--pagination.currentPage"> Prev </a>
                    <a class="btn btn-sm btn-primary pagination-previous"
                       v-else disabled> Prev </a>
                    <a v-if="pagination.nextPageUrl"
                       class="btn btn-sm pagination-next"
                       @click="++pagination.currentPage"> Next </a>
                    <a class="btn btn-sm btn-primary pagination-next"
                       v-else disabled> Next </a>
                </nav>
                <nav class="pagination" v-else>
                    <span class="page-stats">
                    {{pagination.from}} - {{pagination.to}} of {{filteredRegistrations.length}}
                    <span v-if="`filteredRegistrations.length < pagination.total`"></span>
                    </span>
                    <a v-if="pagination.prevPage"
                       class="btn btn-sm btn-primary pagination-previous"
                       @click="--pagination.currentPage"> Prev </a>
                    <a class="btn btn-sm pagination-previous btn-primary"
                       v-else disabled> Prev </a>
                    <a v-if="pagination.nextPage"
                       class="btn btn-sm btn-primary pagination-next"
                       @click="++pagination.currentPage"> Next </a>
                    <a class="btn btn-sm pagination-next btn-primary"
                       v-else disabled> Next </a>
                </nav>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        created() {
            this.getRegistrations();
            Fire.$on('reloadRegistrations', () => {
                this.getRegistrations();
            })
        },
        data() {
            let sortOrders = {};
            let columns = [
                {label: 'User Name', name: 'user_name'},
                {label: 'User id', name: 'user_id',type: 'number'},
                {label: 'Start time', name: 'start_time',type: 'time'},
                {label: 'End Time', name: 'end_time',type: 'time'},
                {label: 'Created at', name: 'created_at',type: 'date'},
            ];
            columns.forEach((column) => {
                sortOrders[column.name] = -1;
            });
            return {
                registrations: [],
                columns: columns,
                sortKey: 'created_at',
                sortOrders: sortOrders,
                length: 10,
                search: '',
                tableShow: {
                    showdata: true,
                },
                pagination: {
                    currentPage: 1,
                    total: '',
                    nextPage: '',
                    prevPage: '',
                    from: '',
                    to: ''
                },
            }
        },
        methods: {
            getRegistrations() {
                axios.get('/registrations/table', {params: this.tableShow})
                    .then(response => {
                        this.registrations = response.data;
                        this.pagination.total = this.registrations.length;
                    })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            paginate(array, length, pageNumber) {
                this.pagination.from = array.length ? ((pageNumber - 1) * length) + 1 : ' ';
                this.pagination.to = pageNumber * length > array.length ? array.length : pageNumber * length;
                this.pagination.prevPage = pageNumber > 1 ? pageNumber : '';
                this.pagination.nextPage = array.length > this.pagination.to ? pageNumber + 1 : '';
                return array.slice((pageNumber - 1) * length, pageNumber * length);
            },
            resetPagination() {
                this.pagination.currentPage = 1;
                this.pagination.prevPage = '';
                this.pagination.nextPage = '';
            },
            sortBy(key) {
                this.resetPagination();
                this.sortKey = key;
                this.sortOrders[key] = this.sortOrders[key] * -1;
            },
            getIndex(array, key, value) {
                return array.findIndex(i => i[key] == value)
            },
        },
        computed: {
            filteredRegistrations() {
                let registrations = this.registrations;
                if (this.search) {
                    registrations = registrations.filter((row) => {
                        return Object.keys(row).some((key) => {
                            return String(row[key]).toLowerCase().indexOf(this.search.toLowerCase()) > -1;
                        })
                    });
                }
                let sortKey = this.sortKey;
                let order = this.sortOrders[sortKey] || 1;
                if (sortKey) {
                    registrations = registrations.slice().sort((a, b) => {
                        let index = this.getIndex(this.columns, 'name', sortKey);
                        a = String(a[sortKey]).toLowerCase();
                        b = String(b[sortKey]).toLowerCase();
                        if (this.columns[index].type && this.columns[index].type === 'date') {
                            return (a === b ? 0 : new Date(a).getTime() > new Date(b).getTime() ? 1 : -1) * order;
                        } else if (this.columns[index].type && this.columns[index].type === 'number') {
                            return (+a === +b ? 0 : +a > +b ? 1 : -1) * order;
                        } else {
                            return (a === b ? 0 : a > b ? 1 : -1) * order;
                        }
                    });
                }
                return registrations;
            },
            paginatedRegistrations() {
                return this.paginate(this.filteredRegistrations, this.length, this.pagination.currentPage);
            }
        }
    };
</script>
