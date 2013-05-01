<?php

namespace permission\models;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class PermissionRepository extends EntityRepository {
	public function getPermissions($filter = null) {
		$qry_str = 'SELECT p
					FROM permission\models\Permission p';
		
		$qry_array = array();
		
		if (!empty($filter['name'])) {
			$qry_array[] = 'p.name LIKE \'%' . $filter['name'] . '%\' ';
		}
		if (!empty($filter['module'])) {
			$qry_array[] = 'p.module = \'' . $filter['module'] . '\' ';
		}
		if (isset($filter['removable']) && $filter['removable'] != '') {
			$removable = !empty($filter['removable'])?1:0;
			$qry_array[] = 'p.removable = \'' . $removable . '\' ';
		}
		
		if (count($qry_array) > 0) {
			$qry_str .= ' WHERE ' . implode(' AND ', $qry_array);
		}
		
		$qry = $this->_em->createQuery($qry_str);
		$permissions = $qry->getResult();
		
		return $permissions;
	}
	
	public function getPermissionsGroupByModule() {
		$temp_permissions = self::getPermissions();
		$permission = array();
		
		foreach ($temp_permissions as $temp_permission) {
			$permission[$temp_permission->getModule()][] = $temp_permission;
		}
		
		return $permission;
	}
}

?>