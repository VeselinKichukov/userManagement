<?php

namespace App\Http\Controllers;

use App\Services\RegisterForAccessService;
use App\Services\RegistrationGraphService;
use App\Services\RegistrationTableService;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    private $registrationTableService;
    private $registerForAccessService;
    private $registrationGraphService;

    /**
     * Create a new controller instance.
     *
     * @param RegistrationTableService $registrationTableService
     * @param RegisterForAccessService $registerForAccessService
     * @param RegistrationGraphService $registrationGraphService
     */
    public function __construct(RegistrationTableService $registrationTableService,
                                RegisterForAccessService $registerForAccessService,
                                RegistrationGraphService $registrationGraphService)
    {
        $this->middleware('auth');
        $this->registrationTableService = $registrationTableService;
        $this->registerForAccessService = $registerForAccessService;
        $this->registrationGraphService = $registrationGraphService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('registrations');
    }

    /**
     * Construct and retrieve the table with all registrations
     *
     * @param Request $request
     * @return array|\Illuminate\Support\Collection
     */
    public function getRegistrations(Request $request)
    {
      return  $this->registrationTableService->getRegistrationsForTable($request);
    }

    /**
     * Construct and retrieve the graph for the current user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function graph()
    {
       $chartjs = $this->registrationGraphService->retrieveGraph();

        return view('graph', compact('chartjs'));
    }

    /**
     * Register the current user for access
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function register(Request $request)
    {
        return $this->registerForAccessService->registerAccess($request);
    }
}
