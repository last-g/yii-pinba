yii-pinba
=========

Simple Yii extension that incapsulates Pinba configuration and methods


To enable this extensin you need add Pinba to component list in config.php and do some simple configurations
	'pinba' => array(
		'class' => ext.yii-pinba.Pinba,
		// Whethe extension should be enabled
		'on' => true, // default true
		// Change scriptName in pinba to controller/action or to command args in cli mode
		'fixScriptName' => true, // default true
		'scriptName' => null,   // default null (if null pinba would use autodetect)
		'hostName' => null, // default null (if null pinba would use autodetect)
		'serverName' => null, // default null (if null pinba would use autodetect)
		'schema' => null // default null (if null pinba would use autodetect)
	)

In addition you need to add pinba extension to 'preload'