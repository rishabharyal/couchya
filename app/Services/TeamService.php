<?php

namespace App\Services;

use App\Models\Invitation;
use App\Models\Movie;
use App\Models\TeamMember;
use App\Models\User;
use App\Models\UserLikes;
use App\Repos\TeamMemberRepo;
use App\Repos\TeamRepo;
use App\Services\TwilioService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeamService {

	use WithoutMiddleware;

	/**
	 * @author Rishabh Aryal <rish.aryal@gmail.com>
	 * @var TeamRepo $teamRepo
	 */
	private $teamRepo;

	private $teamMemberRepo;

	private $twilioService;

	public function __construct(TeamRepo $teamRepo, TeamMemberRepo $teamMemberRepo, TwilioService $twilioService) {
		$this->teamRepo = $teamRepo;
		$this->teamMemberRepo = $teamMemberRepo;
		$this->twilioService = $twilioService;
	}

	public function createTeam($teamName): array {
		$userId = Auth::id();
		$team = $this->teamRepo->create($teamName, $userId);
		$this->teamMemberRepo->create($team->id, $userId);

		return [
			'success' => true,
			'data' => [
				'title' => $team->title,
				'code' => $team->code,
				'id' => $team->id,
				'members' => $this->getTeamMembers($team)
			]
		];
	}

	public function getAllTeams() {
		$teams = Auth::user()->allTeams()->get();
		$data = [];

		foreach ($teams as $key => $team) {
			$teamMembers = $this->getTeamMembers($team);
			$data[] = [
				'id' => $team->id,
                'title' => $team->title,
                'code' => $team->code,
                'members' => $teamMembers
			];
		}

		return [
			'success' => true,
			'data' => $data
		];

	}

	private function getTeamMembers($team) {
		return User::whereIn('id', $team->members()->pluck('user_id')->toArray())->get()->toArray();
	}

	public function getTeam($teamId) {

	    $teamInfo = $this->teamRepo->getTeam($teamId);

	    if (!$teamInfo) {
	        return [
	            'success' => false,
                'message' => 'The team does not exist!'
            ];
        }

	    $teamMembers = $this->getTeamMembers($teamInfo);

	    return [
	        'success' => true,
            'data' => [
                'id' => $teamInfo->id,
                'title' => $teamInfo->title,
                'code' => $teamInfo->code,
                'members' => $teamMembers
            ]
        ];
    }

	public function joinTeam($teamId) {
		$userId = Auth::id();
		$alreadyInTeam = $this->teamRepo->isMemberInTeam($userId, $teamId);
		if (!$alreadyInTeam) {
			$this->teamMemberRepo->create($teamId, $userId);
		}

		return [
			'success' => true,
			'message' => 'User added to the team.'
		];
	}

	private function cleanPhoneNumber($number) {
		$number = str_replace('+', '', $number);
		$number = str_replace('-', '', $number);
		$number = str_replace(' ', '', $number);
		$number = str_replace('(', '', $number);
		$number = str_replace(')', '', $number);

		if (strlen($number) <= 10) {
			$number = '1' . $number;
		}

		return $number;
	}

	public function invite($teamId, $phoneNumbers) {
		$user = Auth::user();
		$message = $user->name . ' has invited you to join a team. Please open the app or download ' . env('APP_NAME') . ' app from store if not installed.';

		foreach ($phoneNumbers as $key => $phoneNumber) {
			$cleanedPhoneNumber = $this->cleanPhoneNumber($phoneNumber);
			$invitedUser = User::where('phone_number', $cleanedPhoneNumber)->first();
			$invitation = new Invitation();
			$invitation->invitated_to_phone = $cleanedPhoneNumber;
			$invitation->invited_by = $user->id;
			$invitation->team_id = $teamId;
			if ($invitedUser) {
				$invitation->user_id = $invitedUser->id;
			}
			$invitation->save();
		}

		$this->twilioService->sendMessage($phoneNumbers, $message);
		return [
			'success' => true,
			'message' => 'We have sent SMS to '. $this->twilioService->getNumberOfPeopleInvited() . ' people with invitation link.'
		];

	}

	public function getMatches($teamId) {
		$usersRelatedToTheTeam = TeamMember::where('team_id', $teamId)->pluck('user_id')->toArray();
		$userLikes = DB::table('user_likes')
			->select(DB::raw('COUNT(user_id) as total_count, movie_id'))
			->whereIn('user_id', $usersRelatedToTheTeam)
			->groupBy(['movie_id']);

		$data = [];

		foreach ($userLikes->get() as $key => $userLike) {
			if ($userLike->total_count < 2) {
				continue;
			}

			$movie = Movie::find($userLike->movie_id);

			$userIds = UserLikes::where('movie_id', $movie->id)->whereIn('user_id', $usersRelatedToTheTeam)->pluck('user_id')->toArray();

			$datum = [
				'id' => $movie->id,
				'title' => $movie->title,
				'poster' => $movie->poster,
				'image' => $movie->image,
				'genre' => 'Comedy',
				'release_year' => $movie->release_year,
				'likers' => User::whereIn('id', $userIds)->get(['id', 'name', 'profile_picture'])
			];

			$data[] = $datum;
		}

		return [
			'success' => true,
			'data' => $data
		];

	}
}
