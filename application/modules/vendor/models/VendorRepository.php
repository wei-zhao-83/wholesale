<?php

namespace vendor\models;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class VendorRepository extends EntityRepository {
	public function getVendors($filter = null) {
		$qry_str = 'SELECT v
					FROM vendor\models\Vendor v
					LEFT JOIN v.tags t';
		
		$qry_array = array();
		
		$qry_array[] = 'v.deleted_at IS NULL';
		
		if (!empty($filter['name'])) {
			$qry_array[] = 'v.name LIKE \'%' . $filter['name'] . '%\' ';
		}
		
		if (!empty($filter['phone'])) {
			$qry_array[] = 'v.phone LIKE \'%' . $filter['phone'] . '%\' ';
		}
		
		if (!empty($filter['tags']) && is_array($filter['tags'])) {
			foreach ($filter['tags'] as $tag) {
				if (!empty($tag)) {
					$tag_qry_array[] = ' t.name = \'' . $tag . '\' ';
				}
			}
			
			if (!empty($tag_qry_array)) {
				$qry_array[] = implode(' AND ', $tag_qry_array);
			}
		}
		
		if (count($qry_array) > 0) {
			$qry_str .= ' WHERE ' . implode(' AND ', $qry_array);
		}
		
		$qry_str .= ' ORDER BY v.name ASC';
		
		$qry = $this->_em->createQuery($qry_str);
		$vendors = $qry->getResult();
		
		return $vendors;
	}
}

?>