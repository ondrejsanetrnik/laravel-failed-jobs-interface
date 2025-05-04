/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.Bus = new Vue();

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('payload-modal', require('./payload_modal.vue'));
Vue.component('paginator', require('./paginator.vue'));

const app = new Vue({
    el: '#app',
    data() {
        return {
            jobs: {},
            pagination: {},
            filters: {
                connections: [],
                tags: [],
                queues: []
            },
            selected_filters: {
                connection: '',
                tags: [],
                queues: []
            }
        }
    },
    watch: {
        'selected_filters.tags': function(val, oldVal) {
            this.loadFailedJobs();
        },
        'selected_filters.queues': function(val, oldVal) {
            this.loadFailedJobs();
        },
        'selected_filters.connection': function(val, oldVal) {
            this.loadFailedJobs();
        },
    },
    methods: {
        loadFailedJobs(page = 1) {
            let self = this;
            axios
                .get(window.FJI.paths.get_jobs, {
                    params: {
                        page: page,
                        tags: self.selected_filters.tags,
                        queues: self.selected_filters.queues,
                        connection: self.selected_filters.connection,
                    }
                })
                .then(response => {
                    self.jobs = response.data.data;
                    self.pagination = response.data;
                });
        },
        displayJob(jobId) {
            Bus.$emit('getJob', jobId);
        },
        retryJob(jobId) {
            Bus.$emit('retryJob', jobId);
        },
        loadConnectionFilter() {
            let self = this;

            axios
                .get(window.FJI.paths.get_connections_filter)
                .then(response => {
                    self.filters.connections = response.data.filters;
                });
        },
        optionChanged() {
            // this.loadFailedJobs();
        },
        loadTagFilter() {
            let self = this;

            axios
                .get(window.FJI.paths.get_tags_filter)
                .then(response => {
                    self.filters.tags = response.data.filters;
                });
        },
        loadQueueFilter() {
            let self = this;

            axios
                .get(window.FJI.paths.get_queues_filter)
                .then(response => {
                    self.filters.queues = response.data.filters;
                });
        },
    },
    created() {

        let self = this;

        self.loadFailedJobs();
        self.loadConnectionFilter();
        self.loadQueueFilter();
        self.loadTagFilter();

        Bus.$on('loadJobs', page => {
            self.loadFailedJobs(page);
        })

    }
});
