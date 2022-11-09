<?php

//
// iTop module definition file
//

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'knowitop-dashlet-calendar/1.2.0',
	array(
		// Identification
		//
		'label' => "Dashlet Calendar",
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
		),
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'vendor/autoload.php',
			'src/Hook/DashletCalendar.php',
			'src/Hook/DashletCalendarStaticContent.php'
		),
		'webservice' => array(),
		'data.struct' => array(// add your 'structure' definition XML files here,
		),
		'data.sample' => array(// add your sample data XML files here,
		),

		// Documentation
		//
		'doc.manual_setup' => '', // hyperlink to manual setup documentation, if any
		'doc.more_information' => '', // hyperlink to more information, if any

		// Default settings
		//
		'settings' => array(
			// Module specific settings go here, if any
			'colors' => array(
				'blue' => '#006699',
				'cyan' => '#009999',
				'green' => '#009933',
				'red' => '#CC0000',
				'brown' => '#996633',
				'gray' => '#666666',
				'yellow' => '#CCCC00',
				'orange' => '#FF9900',
				'purple' => '#993366',
				'pink' => '#CC6699',
			),
			'fullcalendar_options' => [
				'titleFormat' => [
					'year' => 'numeric',
					'month' => 'short',
					'day' => 'numeric'
				],
				'eventTimeFormat' => [
					'hour'=> 'numeric',
					'minute' => '2-digit',
					'meridiem' => false
				],
				'nowIndicator' => true,
				'dayMaxEventRows' => true, // for all non-TimeGrid views
				'eventMaxStack' => 6,
				'views' => [
					'timeGrid' => [
						'dayMaxEventRows' => 6 // adjust to 6 only for timeGridWeek/timeGridDay
					]
				],
				'aspectRatio' => 2,
				'expandRows' => true,
			],
		),
	)
);
