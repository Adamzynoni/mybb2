<?php
/**
 * Topic create request.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Http\Requests\Topic;

use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Http\Requests\Request;
use MyBB\Core\Permissions\PermissionChecker;

class CreateRequest extends Request
{
	/**
	 * The route to redirect to if validation fails.
	 *
	 * @var string
	 */
	protected $redirectRoute = 'topics.create';
	/** @var Guard $guard */
	private $guard;

	/** @var PermissionChecker $permissionChecker */
	private $permissionChecker;

	/**
	 * @param Guard             $guard
	 * @param PermissionChecker $permissionChecker
	 */
	public function __construct(Guard $guard, PermissionChecker $permissionChecker)
	{
		$this->guard = $guard;
		$this->permissionChecker = $permissionChecker;
	}

	/**
	 * @return array
	 */
	public function rules()
	{
		$unviewableForums = implode(',', $this->permissionChecker->getUnviewableIdsForContent('forum'));

		return [
			'content' => 'required',
			'title' => 'required',
			'forum_id' => "required|exists:forums,id|not_in:{$unviewableForums}",
		];
	}

	/**
	 * @return bool
	 */
	public function authorize()
	{
		//return $this->guard->check();
		return true; // TODO: In dev return, needs replacing for later...
	}

	/**
	 * @return string
	 */
	protected function getRedirectUrl()
	{
		return $this->redirector->getUrlGenerator()->route($this->redirectRoute, $this->route()->parameters());
	}
}
