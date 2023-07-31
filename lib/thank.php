<?php
namespace Suhanov\Tt;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;


/**
 * Class ThankTable
 * 
 * Fields:
 * <ul>
 * <li> id int mandatory
 * <li> user_from_id int mandatory
 * <li> user_to_id int mandatory
 * <li> date datetime mandatory
 * </ul>
 *
 * @package Suhanov\Tt
 **/

class ThankTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'tt_thank';
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
					'title' => Loc::getMessage('THANK_ENTITY_ID_FIELD'),
				]
			),
			new IntegerField(
				'USER_FROM_ID',
				[
					'required' => true,
					'title' => Loc::getMessage('THANK_ENTITY_USER_FROM_ID_FIELD'),
				]
			),
			(new Reference(
				'USER_FROM_ID_LIST',
				UserTable::class,
				Join::on('this.USER_FROM_ID', 'ref.ID')
			))
			->configureJoinType('inner'),
			new IntegerField(
				'USER_TO_ID',
				[
					'required' => true,
					'title' => Loc::getMessage('THANK_ENTITY_USER_TO_ID_FIELD'),
				]
			),(new Reference(
				'USER_TO_ID_LIST',
				UserTable::class,
				Join::on('this.USER_TO_ID', 'ref.ID')
			))
			->configureJoinType('inner'),
			new DatetimeField(
				'DATE',
				[
					'required' => true,
					'title' => Loc::getMessage('THANK_ENTITY_DATE_FIELD'),
				]
			),
		];
	}
}