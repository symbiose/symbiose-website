<?php
namespace core;

/**
 * A backend API.
 * @author Simon Ser
 * @since 1.0alpha1
 */
class BackendApiApplication extends \core\Application {
	/**
	 * Initialize this backend.
	 */
	public function __construct() {
		parent::__construct();

		$this->name = 'backendApi';
	}

	public function run() {
		if ($this->user->isAdmin()) {
			$controller = $this->getController();
		} else {
			$this->httpResponse()->redirect403($this);
			return;
		}

		$controller->execute();

		$this->httpResponse->setContent($controller->responseContent());
	}
}