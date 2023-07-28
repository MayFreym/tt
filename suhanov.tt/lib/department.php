<?php
namespace Suhanov\Tt;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;


/**
 * Class DepartmentTable
 * 
 * Fields:
 * <ul>
 * <li> id int mandatory
 * <li> name int mandatory
 * <li> parent int mandatory
 * </ul>
 *
 * @package Suhanov\Tt
 **/

class DepartmentTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'tt_department';
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
				'NAME',
				[
					'required' => true,
					'title' => Loc::getMessage('DEPARTMENT_ENTITY_NAME_FIELD'),
				]
			),
			new IntegerField(
				'PARENT',
				[
					'required' => true,
					'title' => Loc::getMessage('DEPARTMENT_ENTITY_PARENT_FIELD'),
				]
			),
			(new Reference(
				'PARENT_DEP',
				DepartmentTable::class,
				Join::on('this.PARENT', 'ref.ID')
			))
		];
	}
}