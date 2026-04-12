<?php

namespace Knowitop\iTop\Extension\DashletCalendar;

use MetaModel;
use utils;
use function rtrim;

class ModuleConfig
{
	public const MODULE_NAME = 'knowitop-dashlet-calendar';

	public static function Get(string $sName, mixed $sDefaultValue = null): mixed
	{
		return MetaModel::GetModuleSetting(self::GetName(), $sName, $sDefaultValue);
	}

	public static function GetName(): string
	{
		return self::MODULE_NAME;
	}

	public static function GetRootPath(bool $bAbsolute = true): string
	{
		if ($bAbsolute) {
			return utils::GetAbsoluteModulePath(static::GetName());
		}

		return 'env-'.rtrim(utils::GetCurrentEnvironment(), '/').'/'.static::GetName();
	}

	public static function GetImgAssetsUrl(bool $bAbsolute = true): string
	{
		return static::GetRootUrl($bAbsolute).'/assets/img';
	}

	public static function GetRootUrl(bool $bAbsolute = true): string
	{
		$sUrl = 'env-'.rtrim(utils::GetCurrentEnvironment(), '/').'/'.static::GetName();
		if ($bAbsolute) {
			$sUrl = rtrim(utils::GetAbsoluteUrlAppRoot(), '/').'/'.$sUrl;
		}

		return $sUrl;
	}
}