<?php
namespace ctrl\backend\gutenberg;

class GutenbergController extends \core\BackController {
	protected function _addBreadcrumb($page = array()) {
		$breadcrumb = array(
			array(
				'url' => $this->app->router()->getUrl('main', 'showModule', array(
					'module' => $this->module()
				)),
				'title' => 'Gutenberg'
			)
		);

		$this->page()->addVar('breadcrumb', array_merge($breadcrumb, array($page)));
	}

	public function executeInsertPage(\core\HTTPRequest $request) {
		$this->page()->addVar('title', 'Ajouter une page');
		$this->_addBreadcrumb();

		$manager = $this->managers->getManagerOf('gutenberg');

		if ($request->postExists('page-name')) {
			$pageData = array(
				'name' => $request->postData('page-name'),
				'title' => $request->postData('page-title'),
				'content' => $request->postData('page-content')
			);

			$this->page()->addVar('page', $pageData);

			try {
				$page = new \lib\entities\GutenbergPage($pageData);
			} catch(\InvalidArgumentException $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			try {
				$manager->insertPage($page);
			} catch(\Exception $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			$this->page()->addVar('inserted?', true);
		}
	}

	public function executeUpdatePage(\core\HTTPRequest $request) {
		$this->page()->addVar('title', 'Modifier une page');
		$this->_addBreadcrumb();

		$manager = $this->managers->getManagerOf('gutenberg');

		$pageName = $request->getData('pageName');
		$page = $manager->getPage($pageName);

		$this->page()->addVar('page', $page);

		if ($request->postExists('page-name')) {
			$pageData = array(
				'name' => $request->postData('page-name'),
				'title' => $request->postData('page-title'),
				'content' => $request->postData('page-content')
			);

			$this->page()->addVar('page', $pageData);

			try {
				$page->hydrate($pageData);
			} catch(\InvalidArgumentException $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			try {
				$manager->updatePage($page);
			} catch(\Exception $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			$this->page()->addVar('updated?', true);
		}
	}

	public function executeDeletePage(\core\HTTPRequest $request) {
		$this->page()->addVar('title', 'Supprimer un billet');
		$this->_addBreadcrumb();

		$manager = $this->managers->getManagerOf('gutenberg');

		$pageName = $request->getData('pageName');

		if ($request->postExists('check')) {
			if (!$manager->pageExists($pageName)) {
				$this->page()->addVar('error', 'Cannot find the page named "'.$pageName.'"');
				return;
			}

			try {
				$manager->deletePage($pageName);
			} catch(\Exception $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			$this->page()->addVar('deleted?', true);
		} else {
			$page = $manager->getPage($pageName);
			$this->page()->addVar('page', $page);
		}
	}

	// LISTERS

	public function listPages() {
		$manager = $this->managers->getManagerOf('gutenberg');

		$pages = $manager->listPages();

		$list = array();

		foreach($pages as $page) {
			$item = array(
				'title' => $page['title'],
				'vars' => array('pageName' => $page['name'])
			);

			$list[] = $item;
		}

		return $list;
	}
}