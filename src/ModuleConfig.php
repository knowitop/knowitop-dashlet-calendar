<?php

namespace Knowitop\iTop\Extension\DashletCalendar;

use MetaModel;
use utils;

class ModuleConfig
{
	private const MODULE_NAME = 'knowitop-dashlet-calendar';

	/**
	 * @param string $sName
	 * @param mixed|null $sDefaultValue
	 *
	 * @return mixed|null
	 */
	public static function Get(string $sName, $sDefaultValue = null)
	{
		return MetaModel::GetModuleSetting(self::GetName(), $sName, $sDefaultValue);
	}

	/**
	 * @return string
	 */
	public static function GetName(): string
	{
		return self::MODULE_NAME;
	}

	public static function GetPath(): string
	{
		return utils::GetAbsoluteModulePath(static::GetName());
	}

	public static function GetRootUrl(bool $bAbsolute = true): string
	{
		$sUrl = 'env-'.utils::GetCurrentEnvironment().'/'.static::GetName().'/';
		if ($bAbsolute) {
			$sUrl = utils::GetAbsoluteUrlAppRoot().$sUrl;
		}

		return $sUrl;
	}

	public static function GetAssetsUrl(bool $bAbsolute = true): string
	{
		return static::GetRootUrl($bAbsolute).'assets/';
	}

	public static function GetImgAssetsUrl(bool $bAbsolute = true)
	{
		return static::GetRootUrl($bAbsolute).'assets/img/';
	}

	public static function GetJsAssetsUrl(bool $bAbsolute = true)
	{
		return static::GetRootUrl($bAbsolute).'assets/js/';
	}

	public static function GetCssAssetsUrl(bool $bAbsolute = true)
	{
		return static::GetRootUrl($bAbsolute).'assets/css/';
	}
}