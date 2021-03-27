<?php

namespace App\Services;

use App\User;
use App\UserStructure;

class UserStructureService
{

	protected $ids;

	public function users($parent_id) {

		$user_structure = UserStructure::all();

		$this->ids = [];
		$users = [];
		foreach ($user_structure as $user) {
			$users[] = [
				'id' 		=> $user->id,
				'parent_id' => $user->parent_id,
				'user' 		=> $user->user
			];
		}

		return $this->buildTree($users, $parent_id);

	}

	public function ids($parent_id) {
		$this->users($parent_id);
		$ids = $this->ids;
		$ids[] = $parent_id;
		sort($ids);
		return $ids;
	}

	private function buildTree($users, $parent_id = 0) {

	    $branch = [];

	    foreach ($users as $user) {

	        if ($user['parent_id'] == $parent_id) {
	            $children = $this->buildTree($users, $user['id']);
	            if ($children) {
	                $user['children'] = $children;
	            }

	            $this->ids[] = $user['id'];
	            $branch[] = $user;
	        }
	    }

	    return $branch;
	}

	public static function user_list($roles) {

		$list = [];

		$users = User::role($roles)->get();
		foreach ($users as $user) {
			$list[] = [
				'id' 	=> $user->id,
				'text'	=> $user->name
			];
		}

		return $list;
	}
}