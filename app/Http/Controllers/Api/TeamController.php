<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TeamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{

    /**
     * @var TeamService
     */
    private $teamService;

    /**
     * TeamController constructor.
     * @param TeamService $teamService
     */
    public function __construct(TeamService $teamService) {
		$this->teamService = $teamService;
	}

    public function index() {
        return response()->json($this->teamService->getAllTeams());
    }

    public function join($id) {
        return response()->json($this->teamService->joinTeam($id));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {
    	$validator = Validator::make($request->all(), [
			'title' => ['required', 'string'],
		]);

		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'data' => $validator->errors()
			]);
		}

		$response = $this->teamService->createTeam($request->get('title'));
		return response()->json($response);

    }

    public function inviteFriend(Request $request) {
        $validator = Validator::make($request->all(), [
            'invitations' => ['required', 'array'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors()
            ]);
        }

        return response()->json($this->teamService->invite($request->get('invitations')));
    }

    public function show($id) {
        return response()->json($this->teamService->getTeam($id));
    }
}
