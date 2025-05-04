<?php namespace MoldersMedia\FailedJobInterface\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use MoldersMedia\FailedJobInterface\Repositories\EloquentFailedJobRepository;

class IndexController extends Controller
{
    /**
     * @var EloquentFailedJobRepository
     */
    private $repository;

    public function __construct(EloquentFailedJobRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('fji::welcome');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJobs(Request $request)
    {
        $data = $this->repository->getFailedJobs($request);

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJob(Request $request)
    {
        $data = $this->repository->getJob($request->get('job_id'));

        $data->payload = json_decode($data->payload);

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function retryJob(Request $request)
    {
        $data = $this->repository->getJob($request->get('job_id'));

        $data->payload = json_decode($data->payload);

        $data->retry();

        return response()->json($data);
    }
}
