<template>
    <div>
        <div class="modal fade" id="show-job-modal" tabindex="-1" role="dialog" aria-labelledby="showJobModalTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="showJobModalTitle">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                @click="closeModal()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-if="job">


                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                   aria-controls="home" aria-selected="true">Exception</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                                   aria-controls="profile" aria-selected="false">Payload</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="stats-tab" data-toggle="tab" href="#status" role="tab"
                                   aria-controls="status" aria-selected="false">Status</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <pre>{{ job.exception }}</pre>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div v-html="prettyPrintJob(job.payload.data)"></div>
                            </div>

                            <div class="tab-pane fade" id="status" role="tabpanel" aria-labelledby="stats-tab">
                                <ul>
                                    <li>Characters: {{ countJobCharacters(job.payload.data) }}</li>
                                </ul>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal()">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import phpunserialize from 'phpunserialize'

export default {
    data() {
        return {
            job: null
        }
    },
    methods: {
        getFailedJob(jobId) {
            let self = this;
            axios
                .get(window.FJI.paths.get_job, {
                    params: {job_id: jobId}
                })
                .then(job => {
                    $('#show-job-modal').modal('show');
                    self.job = job.data;
                });
        }, retryFailedJob(jobId) {
            let self = this;
            axios
                .get(window.FJI.paths.retry_job, {
                    params: {job_id: jobId}
                })
                .then(job => {
                    $('#show-job-modal').modal('show');
                    self.job = job.data;
                });
        },
        prettyPrintJob(data) {
            return '<pre>' + JSON.stringify(data.command ? phpunserialize(data.command) : data, null, 2) + '</pre>';
        },
        countJobCharacters(data) {
            return JSON.stringify(data.command ? phpunserialize(data.command) : data, null, 2).length;
        },
        closeModal() {
            this.job = null;
            $('#show-job-modal').modal('hide');
        }
    },
    created() {
        let self = this;
        Bus.$on('getJob', jobId => {
            self.getFailedJob(jobId);
        });
        Bus.$on('retryJob', jobId => {
            self.retryFailedJob(jobId);
        });
    }
}
</script>
