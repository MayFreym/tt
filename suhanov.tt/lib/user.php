<?php

namespace Suhanov\Tt;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Fields\Relations\OneToMany;


/**
 * Class UserTable
 * 
 * Fields:
 * <ul>
 * <li> id int mandatory
 * <li> name string(100) mandatory
 * </ul>
 *
 * @package Suhanov\Tt
 **/

class UserTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'tt_user';
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
					'title' => Loc::getMessage('USER_ENTITY_ID_FIELD'),
				]
			),
			new StringField(
				'NAME',
				[
					'required' => true,
					'validation' => function () {
						return [
							new LengthValidator(null, 100),
						];
					},
					'title' => Loc::getMessage('USER_ENTITY_NAME_FIELD'),
				]
			),
			(new OneToMany('LIKES', ThankTable::class, 'USER_FROM_ID_LIST'))->configureJoinType('inner'),
		];
	}
}
