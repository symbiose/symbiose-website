<?php
namespace ctrl\frontend\gutenberg;

class GutenbergController extends \core\BackController {
	public function executeShowPage(\core\HTTPRequest $request) {
		$manager = $this->managers->getManagerOf('gutenberg');

		try {
			$page = $manager->getPage($request->getData('pageName'));
		} catch(\Exception $e) {
			$this->page()->addVar('error', $e->getMessage());
			return;
		}

		$this->page()->addVar('title', $page['title']);
		$this->page()->addVar('page', $page);
	}
}