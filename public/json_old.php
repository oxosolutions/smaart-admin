<?php

// 'chartType' 				=> 	'LineChart',

$settings = [

	   'title'     				=> 	['label'=>'Title','type'=>'text','options'=>[],'isArray'=>'false','chartType'=>['all']],

	   'is3D'      				=> 	['label'=>'3D Chart','type'=>'select','options'=>['true'=>'True','false'=>'False'],'isArray'=>'false','chartType'=>['PieChart']],

	   'pieHole'   				=> 	['label'=>'Pie Hole','type'=>'text','options'=>[],'isArray'=>'false','chartType'=>['PieChart']],

	   'width'     				=> 	['label'=>'Width','type'=>'text','options'=>[],'isArray'=>'false','chartType'=>['all']],

	   'height'    				=> 	['label'=>'Height','type'=>'text','options'=>[],'isArray'=>'false','chartType'=>['all']],

	   'colors'    				=> 	['label'=>'Colors','type'=>'text','options'=>[],'isArray'=>'false','chartType'=>['all']],

	   'pieStartAngle'			=> 	['label'=>'Pie Start Angle','type'=>'text','options'=>[],'isArray'=>'false','chartType'=>['PieChart']],

	   'reverseCategories'		=> 	['label'=>'Reserve Categories','type'=>'select','options'=>['true'=>'True','false'=>'False'],'isArray'=>'false','chartType'=>['PieChart']],

	   'fontSize'				=>	['label'=>'Font Size','type'=>'text','options'=>[],'isArray'=>'false','chartType'=>['all']],

	   'fontName'				=>	['label'=>'Font Name','type'=>'text','options'=>[],'isArray'=>'false','chartType'=>['all']],

	   'forceIFrame'			=>	['label'=>'Force iFrame','type'=>'select','options'=>['true'=>'True','false'=>'False'],'isArray'=>'false','chartType'=>['all']],

	   'areaOpacity'			=>	['label'=>'Area Opacity','type'=>'text','options'=>[],'isArray'=>'false','chartType'=>['all']],

	   'pieSliceBorderColor'	=>	['label'=>'Pie Slce Border Color','type'=>'text','options'=>[],'isArray'=>'false','chartType'=>['PieChart']],

	   'pieStartAngle'			=>	['label'=>'Pie Start Angle','type'=>'text','options'=>[],'isArray'=>'false','chartType'=>['PieChart']],
	   'legend'					=>	['label'=>'Legend','type'=>'select',
																	'options'	=>	
																			['top'=>'Top','bottom'=>'Bottom','left'=>'Left','right'=>'Right'],
																	'isArray'=>'false','chartType'=>['all']],
		'curveType'				=>	['label'=>'Curve Type','type'=>'select','options'=>['none'=>'None','function'=>'Smooth'],'isArray'=>'false','chartType'=>['LineChart']],
		'pointSize'				=>	['label'=>'Point Size','type'=>'text','options'=>[],'isArray'=>'false','chartType'=>['LineChart']],
		'backgroundColor'		=>	['label'=>'Background Color','type'=>'text','options'=>[],'isArray'=>'false','chartType'=>['all']],

		'isStacked'				=>	['label'=>'Is Stacked','type'=>'select',
																'options'=>['true'=>'True','percent'=>'Percent','relative'=>'Relative','absolute'=>'Absolute'],
																'isArray'=>'false',
																'chartType' => ['all']
																],
		'lineWidth'				=>	['label'=>'Line Width','type'=>'text','options'=>[],'isArray'=>'false','chartType'=>['LineChart']],


		'enableInteractivity'	=>	['label'=>'Enable Interactivity','type'=>'select','options'=>['true'=>'True','false'=>'False'],'isArray'=>'false','chartType'=>['all']],

		'keepAspectRatio'		=>	['label'=>'keep Aspect Ratio','type'=>'select','options'=>['true'=>'True','false'=>'False'],'isArray'=>'false','chartType'=>['all']],

		'colorAxis'				=>	['label'=>'Color Axis','type'=>'text','options'=>[],'isArray'=>'false','chartType'=>['all']],

	   'animation'				=>	[
										'statup' 	=>	['label'=>'Statup','type'=>'select','options'=>['true'=>'True','false'=>'False'],'isArray'=>'false'],

										'duration'	=>	['label'=>'Duration','type'=>'text','options'=>[],'isArray'=>'false'],

										'easing'	=>	['label'=>'Easing','type'=>'select',
																			'options'=>[
																						'inAndOut'=>'inAndOut','in'=>'In','out'=>'Out'
																					   ],
																			'isArray'=>'false'],

										'isArray'	=>	'true',
										'chartType'	=>	['all']
									],

		'chartArea'				=>	[
										'left'		=>	['label'=>'Left','type'=>'text','options'=>[],'isArray'=>'false'],
										'top'		=>	['label'=>'Top','type'=>'text','options'=>[],'isArray'=>'false'],
										'bottom'	=>	['label'=>'Bottom','type'=>'text','options'=>[],'isArray'=>'false'],
										'height'	=>	['label'=>'Height','type'=>'text','options'=>[],'isArray'=>'false'],
										'width'		=>	['label'=>'Width','type'=>'text','options'=>[],'isArray'=>'false'],
										'isArray'   =>  'true',
										'chartType'	=>	['all']
									],

		'bar'					=>	[	
										'groupWidth'	=>	['label'=>'Group Width','type'=>'text','options'=>[],'isArray'=>'false'],
										'isArray'	=>	'true',
										'chartType'	=>	['BarChart']
									],
		'tooltip'				=>	[
										'isHtml'		=>	['label'=>'Is HTML','type'=>'select','options'=>['true'=>'True','false'=>'False'],'isArray'=>'false'],

										'showColorCode'	=>	['label'=>'Show Color Code','type'=>'select',
																						'options'=>['true'=>'True','false'=>'False'],
																						'isArray'=>'false'],
										'isArray'	=>	'true',
										'chartType'	=>	['all']
									],
		

		'hAxis'					=>	[
										'textPosition'	=>	['label'=>'Text Position','type'=>'select',
																							'options'=>['horizontal'=>'Horizontal','vertical'=>'Vertical'],
																							'isArray'=>'false'],
										'gridlines'		=>	[
																'color'	=>	['label'=>'Grid Line Color','type'=>'text','options'=>[],'isArray'=>'false'],
																'isArray' => 'true'
															],
										'isArray'	=>	'true',
										'chartType'	=>	['all']
									],

		'bubble'				=>	[	
										'opacity'	=>	['label'=>'Bubble Opacity','type'=>'text','options'=>[],'isArray'=>'false'],
										'stroke'	=>	['label'=>'Bubble Stroke color','type'=>'text','options'=>[],'isArray'=>'false'],
										'isArray'	=>	'true',
										'chartType'	=>	['BubbleChart']
									],
		'sizeAxis'				=>	[	
										'maxSize'	=>	['label'=>'Size Axis Max Size','type'=>'text','options'=>[],'isArray'=>'false'],
										'isArray'	=>	'true',
										'chartType'	=>	['all']
									]

	];