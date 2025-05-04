@extends('fji::template.master')

@section('content')
    <main role="main" class="">

        <div class="col-lg-12">
            <div class="card text-white bg-dark mb-3">
                <div class="card-header">
                    Filters
                    <div class="pull-right filter-btn btn btn-outline-info">
                        <i class="fa fa-filter"></i>
                    </div>
                </div>
                <div class="card-body filter-body">

                    <form>
                        <div class="col-lg-12">
                            <h3>Connection</h3>
                            <div class="form-group">

                                <select class="form-control" id="exampleFormControlSelect2"
                                        v-model="selected_filters.connection" title="Select a connection" @change="optionChanged()">
                                    <option value="">Make a choice</option>
                                    <option v-for="connection in filters.connections" :value="connection">@{{connection}}
                                    </option>
                                </select>
                            </div>

                            <div class="m-lg-5"></div>

                            @if(config('failed_job_interface.uses_horizon'))
                            <h3>Tags</h3>
                            <ul class="tag-list">
                                <li v-for="tag in filters.tags">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" v-model="selected_filters.tags" type="checkbox" :id="'tag'+tag" :value="tag" @click="optionChanged()">
                                        <label class="form-check-label" :for="'tag'+tag">@{{tag}}</label>
                                    </div>
                                </li>
                            </ul>
                            @endif

                            <div class="m-lg-5"></div>

                            <h3>Queues</h3>
                            <ul class="tag-list">
                                <li v-for="queue in filters.queues">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" v-model="selected_filters.queues" type="checkbox" :id="'queue'+queue" :value="queue" @click="optionChanged()">
                                        <label class="form-check-label" :for="'queue'+queue">@{{queue}}</label>
                                    </div>
                                </li>
                            </ul>

                        </div>
                    </form>
                </div>
            </div>

            <paginator :data="pagination" :event="'loadJobs'"></paginator>
            <table class="table table-striped table-hover table-dark table-sm">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Failed at</th>
                    <th>Connection</th>
                    <th>Queue</th>
                    @if(config('failed_job_interface.uses_horizon'))
                        <th>Tags</th>
                    @endif
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="job in jobs">
                    <td>@{{job.id}}</td>
                    <td>@{{job.failed_at}}</td>
                    <td>@{{job.connection}}</td>
                    <td>@{{job.queue}}</td>

                    @if(config('failed_job_interface.uses_horizon'))
                        <td>
                            <ul v-if="job.tags" class="list-unstyled">
                                <li v-for="tag in job.tags">@{{tag}}</li>
                            </ul>
                        </td>
                    @endif

                    <td>
                        <button class="btn btn-info btn-outline-info btn-sm" @click="displayJob(job.id)">Info</button>
                        <button class="btn btn-success btn-outline-success btn-sm" @click="retryJob(job.id)">Retry</button>
                    </td>
                </tr>
                </tbody>
            </table>
            <paginator :data="pagination" :event="'loadJobs'"></paginator>
        </div>
    </main><!-- /.container -->



    <payload-modal></payload-modal>


@endsection
