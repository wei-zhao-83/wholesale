<?php

namespace user\models;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class UserRepository extends EntityRepository {
	public function getUsers($filter = null) {
		$qry_str = 'SELECT u
					FROM user\models\User u
					JOIN u.role r';
		
		$qry_array = array();
		
		if (!empty($filter['username'])) {
			$qry_array[] = 'u.username LIKE \'%' . $filter['username'] . '%\' ';
		}
		if (!empty($filter['phone'])) {
			$qry_array[] = 'u.phone LIKE \'%' . $filter['phone'] . '%\' ';
		}
		if (!empty($filter['email'])) {
			$qry_array[] = 'u.email LIKE \'%' . $filter['email'] . '%\' ';
		}
		if (isset($filter['active']) && $filter['active'] != '') {
			$active = !empty($filter['active'])?1:0;
			$qry_array[] = 'u.active = \'' . $active . '\' ';
		}
		if (!empty($filter['role'])) {
			$qry_array[] .= 'r.id = \'' . $filter['role'] . '\' ';
		}
		
		if (count($qry_array) > 0) {
			$qry_str .= ' WITH ' . implode(' AND ', $qry_array);
		}

		$qry = $this->_em->createQuery($qry_str);
		$users = $qry->getResult();
		
		return $users;
	}
	
	public function getUsersByIds($ids) {
		$qry_str = 'SELECT u FROM user\models\User u
					WHERE u.id IN (' . implode(',', $ids) . ')';
		
		$qry = $this->_em->createQuery($qry_str);
		$users = $qry->getResult();
		
		return $users;
	}
	
	public function countUsersBy($fields = array()) {
		$qry_str = 'SELECT COUNT(u.id)
					FROM user\models\User u
					JOIN u.role r ';
		
		foreach ($fields as $field_name => $field_value) {
			if ($field_name != 'role') {
				$qry_array[] = 'u.' . $field_name . '= \'' . $field_value. '\' ';
			}
		}
					
		if (!empty($fields['role'])) {
			$qry_array[] = 'r.id = \'' . $fields['role'] . '\' ';
		}
		
		if (count($qry_array) > 0) {
			$qry_str .= ' WITH ' . implode(' AND ', $qry_array);
		}
		
		$qry = $this->_em->createQuery($qry_str);
		$num = $qry->getSingleScalarResult();
		
		return (int)$num;
	}
	
	public function getUserByIdentity($identity) {
		$qry_str = 'SELECT u
					FROM user\models\User u
					JOIN u.role r
					WHERE u.username = \'' . $identity . '\' OR u.email = \'' . $identity . '\' ';
					
		$qry = $this->_em->createQuery($qry_str);
		$user = $qry->getSingleResult();
		
		return $user;
	}
}

?>