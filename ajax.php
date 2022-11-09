<?php

namespace Knowitop\iTop\Extension\DashletCalendar;

use ApplicationContext;
use AttributeDateTime;
use DateTime;
use DBObjectSearch;
use DBObjectSet;
use Exception;
use Expression;
use IssueLog;
use LoginWebPage;
use utils;

require_once('../../approot.inc.php');

try {
    require_once(APPROOT . '/application/startup.inc.php');
    require_once(APPROOT . '/application/loginwebpage.class.inc.php');
    LoginWebPage::DoLogin(); // Check user rights and prompt if needed
    $sStartIntervalDate = utils::ReadParam('start', '', false, utils::ENUM_SANITIZATION_FILTER_STRING);
    $sStartDateAttr = utils::ReadParam('start_attr', '', false, utils::ENUM_SANITIZATION_FILTER_FIELD_NAME);
    $sEndIntervalDate = utils::ReadParam('end', false, false, utils::ENUM_SANITIZATION_FILTER_STRING);
    $sEndDateAttr = utils::ReadParam('end_attr', '', false, utils::ENUM_SANITIZATION_FILTER_FIELD_NAME);
    $bShowUnfinished = (bool)utils::ReadParam('unfinished', false, false, utils::ENUM_SANITIZATION_FILTER_INTEGER);
    $sTitleAttr = utils::ReadParam('title_attr', '', false, utils::ENUM_SANITIZATION_FILTER_FIELD_NAME);
    $sDescriptionAttr = utils::ReadParam('description_attr', '', false, utils::ENUM_SANITIZATION_FILTER_FIELD_NAME);
    $sFilter = utils::ReadParam('filter', '', false, utils::ENUM_SANITIZATION_FILTER_STRING);
    $oFilter = DBObjectSearch::unserialize(base64_decode($sFilter));
    $sClass = $oFilter->GetClassAlias();
    if ($sEndDateAttr && !$bShowUnfinished)
    {
        // выбраны атрибуты начала и окончания и не показываются незавершенные
        // В этом случае выбираем все, у которых дата окончания больше даты начала интервала
        // и дата начала меньше даты окончания интерала
        $oFilter->AddCondition($sStartDateAttr, $sEndIntervalDate, '<');
        $oFilter->AddCondition($sEndDateAttr, $sStartIntervalDate, '>');
    }
    elseif ($sEndDateAttr && $bShowUnfinished)
    {
        // выбраны атрибуты начала и окончания и показываются незавершенные
        // В этом случае выбираем все, у которых дата окончания больше даты начала интервала или пустая,
        // а дата начала меньше даты окончания интервала
        $oFilter->AddCondition($sStartDateAttr, $sEndIntervalDate, '<');
        $sOQLCondition = "$sClass.$sEndDateAttr > '$sStartIntervalDate' OR ISNULL($sClass.$sEndDateAttr)";
        $oExpr = Expression::FromOQL($sOQLCondition);
        $oFilter->AddConditionExpression($oExpr);
    }
    else
    {
        // выбран только атрибут начала, ищем только то, что попало в интервал
        $sOQLCondition = "$sClass.$sStartDateAttr > '$sStartIntervalDate' AND $sClass.$sStartDateAttr < '$sEndIntervalDate'";
        $oExpr = Expression::FromOQL($sOQLCondition);
        $oFilter->AddConditionExpression($oExpr);

        // Код ниже не работает, т.к. в запросе появляется один и тот же алиас :start_date (название поля)
        // и, соответственно, одно и то же его значение
        //$oFilter->AddCondition($sStartDateAttr, $sStartIntervalDate, '>');
        //$oFilter->AddCondition($sStartDateAttr, $sEndIntervalDate, '<');
    }
    $oObjectSet = new DBObjectSet($oFilter);
    $aEvents = array();
    while ($oObj = $oObjectSet->Fetch()) {
        $aEvent = array();
        $aEvent['title'] = strip_tags(html_entity_decode($oObj->GetAsHTML($sTitleAttr))) . ($sDescriptionAttr ? "\n" . strip_tags(html_entity_decode($oObj->GetAsHTML($sDescriptionAttr))) : '');
        $aEvent['start'] = $oObj->Get($sStartDateAttr);
        $sEndDate = '';
        if ($sEndDateAttr) {
            $sEndDate = $oObj->Get($sEndDateAttr);
            if (!$sEndDate && $bShowUnfinished) {
				$sEndDate = date_format(new DateTime(), AttributeDateTime::GetInternalFormat());
            }
        }
        $aEvent['end'] = $sEndDate;
        $aEvent['url'] = ApplicationContext::MakeObjectUrl(get_class($oObj), $oObj->GetKey());
        $aEvents[] = $aEvent;
    }
    $jsonEvents = json_encode($aEvents);
    echo $jsonEvents;
} catch (Exception $e) {
	IssueLog::Error($e->getMessage());
	echo htmlentities($e->getMessage(), ENT_QUOTES, 'utf-8');
}