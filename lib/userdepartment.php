<?php

namespace Suhanov\Tt;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;


/**
 * Class UserDepartmentTable
 * 
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> USER_ID int mandatory
 * <li> DEPARTMENT_ID int mandatory
 * </ul>
 *
 * @package Suhanov\Tt
 **/

class UserDepartmentTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'tt_user_department';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return [
			new IntegerField(
				'ID',
				[
					'primary' => true,
					'autocomplete' => true,
					'title' => Loc::getMessage('DEPARTMENT_ENTITY_ID_FIELD'),
				]
			),
			new IntegerField(
				'USER_ID',
				[
					'required' => true,
					'title' => Loc::getMessage('DEPARTMENT_ENTITY_USER_ID_FIELD'),
				]
			),
			new IntegerField(
				'DEPARTMENT_ID',
				[
					'required' => true,
					'title' => Loc::getMessage('DEPARTMENT_ENTITY_DEPARTMENT_ID_FIELD'),
				]
			),
			(new Reference(
				'USER_LIST',
				DepartmentTable::class,
				Join::on('this.DEPARTMENT_ID', 'ref.ID')
			))
			->configureJoinType('inner'),
		];
	}
}
