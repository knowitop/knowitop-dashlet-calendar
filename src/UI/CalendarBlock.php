<?php

declare(strict_types=1);

namespace Knowitop\iTop\Extension\DashletCalendar\UI;

use Combodo\iTop\Application\UI\Base\UIBlock;
use Knowitop\iTop\Extension\DashletCalendar\ModuleConfig;
use UserRights;
use function file_exists;
use function strtolower;
use function substr;
use function trim;

class CalendarBlock extends UIBlock
{
	public const BLOCK_CODE = 'ibo-calendar-block';
	public const DEFAULT_JS_ON_READY_TEMPLATE_REL_PATH = ModuleConfig::MODULE_NAME.'/templates/calendar/layout';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = ModuleConfig::MODULE_NAME.'/templates/calendar/layout';

	private array $aFullcalendarOptions;

	public function __construct(string $sId, array $aFullcalendarOptions = [])
	{
		parent::__construct($sId);
		$this->aFullcalendarOptions = $aFullcalendarOptions;

		$this->AddFullcalendarLibAssents();

		// Unset .fc css from font-combodo.css that clashes with .fc fullcalendar style.
		$this
			->AddCssFileRelPath(ModuleConfig::GetRootPath(false).'/assets/css/font-combodo-unset.css')
			->AddCSSClass('font-combodo-unset');

	}

	private function AddFullcalendarLibAssents(): void
	{
		$sAssetsRelPath = ModuleConfig::GetRootPath(false).'/assets';
		$this
			->AddJsFileRelPath($sAssetsRelPath.'/fullcalendar-5.11.0/lib/main.min.js')
			->AddCssFileRelPath($sAssetsRelPath.'/fullcalendar-5.11.0/lib/main.min.css');

		$sLang = strtolower(substr(trim(UserRights::GetUserLanguage()), 0, 2));
		if (file_exists(ModuleConfig::GetRootPath(true).'/assets/fullcalendar-5.11.0/lib/locales/'.$sLang.'.js')) {
			$this->AddJsFileRelPath($sAssetsRelPath.'/fullcalendar-5.11.0/lib/locales/'.$sLang.'.js');
		} else {
			$this->AddJsFileRelPath($sAssetsRelPath.'/fullcalendar-5.11.0/lib/locales-all.min.js');
		}
	}

	public function GetFullcalendarOptions(): array
	{
		return $this->aFullcalendarOptions;
	}
}