<?php

namespace tag\models;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class TagRepository extends EntityRepository {
	public function getTags($filter = null) {
		$qry_str = 'SELECT t
					FROM tag\models\Tag t';
		
		$qry_array = array();
		
		if (!empty($filter['name'])) {
			$qry_array[] = 't.name LIKE \'%' . $filter['name'] . '%\' ';
		}
		
		if (count($qry_array) > 0) {
			$qry_str .= ' WHERE ' . implode(' AND ', $qry_array);
		}
		
		$qry = $this->_em->createQuery($qry_str);
		$tags = $qry->getResult();
		
		return $tags;
	}
}

?>