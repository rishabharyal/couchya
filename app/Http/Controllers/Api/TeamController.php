<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\InvitationService;
use App\Services\TeamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{

    /**
     * @var TeamService
     */
    private $teamService;

    private $invitationService;

    /**
     * TeamController constructor.
     * @param TeamService $teamService
     */
    public function __construct(TeamService $teamService, InvitationService $invitationService) {
		$this->teamService = $teamService;
        $this->invitationService = $invitationService;
	}

    public function index() {
        return response()->json($this->teamService->getAllTeams());
    }

    public function join($id) {
        return response()->json($this->teamService->joinTeam($id));
    }

    public function getInvitations() {
        return response()->json($this->invitationService->getInvitations());
    }

    public function deleteInvitation($id) {
        $this->invitationService->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Invitation deleted successfully!'
        ]);
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

    public function getMatches(Request $request) {
        $validator = Validator::make($request->all(), [
            'team_id' => ['required', 'exists:teams,id']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors()
            ]);
        }

        return response()->json($this->teamService->getMatches($request->get('team_id')));


    }

    public function inviteFriend(Request $request) {
        $validator = Validator::make($request->all(), [
            'invitations' => ['required', 'array'],
            'team_id' => ['required', 'exists:teams,id']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors()
            ]);
        }

        return response()->json($this->teamService->invite($request->get('team_id'), $request->get('invitations')));
    }

    public function show($id) {
        return response()->json($this->teamService->getTeam($id));
    }
}
