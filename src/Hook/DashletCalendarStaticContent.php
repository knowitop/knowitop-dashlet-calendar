<?php

namespace Knowitop\iTop\Extension\DashletCalendar\Hook;

use ApplicationMenu;
use AttributeDashboard;
use DashboardMenuNode;
use DBObjectSet;
use iApplicationUIExtension;
use iPageUIExtension;
use iTopWebPage;
use Knowitop\iTop\Extension\DashletCalendar\ModuleConfig;
use MetaModel;
use UserRights;
use utils;
use WebPage;

class DashletCalendarStaticContent implements iPageUIExtension, iApplicationUIExtension
{
	private static $bLoaded = false;

	// iPageUIExtension

	public function GetBannerHtml(iTopWebPage $oPage)
	{
		// Workaround to use the module with Coverage Windows module that uses the same fullcalendar lib.
		// Trying to load libs only for Dashboard pages (not Details, Create, Edit of Search page).
		if (utils::ReadParam('operation', null) === null) {
			$aContextParams = utils::ReadParam('c', array(), false, 'context_param');
			if (isset($aContextParams['menu'])) {
				$oMenuNode = ApplicationMenu::GetMenuNode(ApplicationMenu::GetMenuIndexById($aContextParams['menu']));
				if ($oMenuNode instanceof DashboardMenuNode) {
					self::AddToPage($oPage);
				}
			} else {
				// For the Welcome dashboard on the start page without any context params (http://localhost/pages/UI.php)
				self::AddToPage($oPage);
			}
		}

		return '';
	}

	public function GetNorthPaneHtml(iTopWebPage $oPage)
	{
		return '';
	}

	public function GetSouthPaneHtml(iTopWebPage $oPage)
	{
		return '';
	}

	// iApplicationUIExtension

	public function OnDisplayProperties($oObject, WebPage $oPage, $bEditMode = false)
	{
		if (!$bEditMode) {
			$aAttrDefs = MetaModel::ListAttributeDefs(get_class($oObject));
			foreach ($aAttrDefs as $oAttrDef) {
				if ($oAttrDef instanceof AttributeDashboard) {
					self::AddToPage($oPage);
					break;
				}
			}
		}
	}

	public function OnDisplayRelations($oObject, WebPage $oPage, $bEditMode = false)
	{
	}

	public function OnFormSubmit($oObject, $sFormPrefix = '')
	{
	}

	public function OnFormCancel($sTempId)
	{
	}

	public function EnumUsedAttributes($oObject)
	{
		return array();
	}

	public function GetIcon($oObject)
	{
		return '';
	}

	public function GetHilightClass($oObject)
	{
		return HILIGHT_CLASS_NONE;
	}

	public function EnumAllowedActions(DBObjectSet $oSet)
	{
		return array();
	}

	////////////////////////////////////////////////

	public static function AddToPage(WebPage $oPage)
	{
		if (!self::$bLoaded) {
			$oPage->add_linked_stylesheet(ModuleConfig::GetAssetsUrl().'fullcalendar-5.11.0/lib/main.min.css');
			$oPage->add_linked_script(ModuleConfig::GetAssetsUrl().'fullcalendar-5.11.0/lib/main.min.js');
			$sLang = substr(strtolower(trim(UserRights::GetUserLanguage())), 0, 2);
			if (file_exists(__DIR__.'/fullcalendar-5.11.0/lib/locales/'.$sLang.'.js')) {
				$oPage->add_linked_script(ModuleConfig::GetAssetsUrl().'fullcalendar-5.11.0/lib/locales/'.$sLang.'.js');
			} else {
				$oPage->add_linked_script(ModuleConfig::GetAssetsUrl().'fullcalendar-5.11.0/lib/locales-all.min.js');
			}
			self::$bLoaded = true;
		}
	}

}
