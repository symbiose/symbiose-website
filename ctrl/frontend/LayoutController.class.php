<?php
namespace ctrl\frontend;

class LayoutController extends \core\ApplicationComponent {
	/**
	 * The page.
	 * @var Page
	 */
	protected $page = null;

	protected $translation;

	/**
	 * Initialize the back controller.
	 * @param Application $app    The application.
	 * @param Page        $page   The page.
	 */
	public function __construct(\core\Application $app, &$page) {
		parent::__construct($app);

		//$daos = new \core\Daos;
		//$this->managers = new \core\Managers($daos);
		$this->page = $page;
		//$this->translation = new \core\ModuleTranslation($app, 'customer', 'index');
		//$this->page->setTranslation($this->translation);
	}

	public function execute(\core\HTTPRequest $request) {
		$isIndex = ($this->page->module() == 'customer' && $this->page->action() == 'index');

		$this->page->addVar('isIndex', $isIndex);
	}
}