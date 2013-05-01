<?php

namespace category\models;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class CategoryRepository extends EntityRepository {
	public function getCategories($filter = null) {
		$qry_str = 'SELECT c
					FROM category\models\Category c
					LEFT JOIN c.tags t';
		
		$qry_array = array();
		
		$qry_array[] = 'c.deleted_at IS NULL';
		
		if (!empty($filter['name'])) {
			$qry_array[] = 'c.name LIKE \'%' . $filter['name'] . '%\' ';
		}
		
		if (!empty($filter['slug'])) {
			$qry_array[] = 'c.slug LIKE \'%' . $filter['slug'] . '%\' ';
		}
		
		if (isset($filter['active']) && $filter['active'] != '') {
			$status = !empty($filter['active'])?1:0;
			$qry_array[] = 'c.active = \'' . $status . '\' ';
		}
		
		if (!empty($filter['tags']) && is_array($filter['tags'])) {
			foreach ($filter['tags'] as $tag) {
				if (!empty($tag)) {
					$tag_qry_array[] = ' t.name = \'' . $tag . '\' ';
				}
			}
			$qry_array[] = implode(' AND ', $tag_qry_array);
		}
		
		if (count($qry_array) > 0) {
			$qry_str .= ' WHERE ' . implode(' AND ', $qry_array);
		}
		
		$qry_str .= ' ORDER BY c.name ASC';
		
		$qry = $this->_em->createQuery($qry_str);
		$categories = $qry->getResult();
		
		return $categories;
	}
}

?>