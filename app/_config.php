<?php

global $project;
$project = 'app';

global $database;
$database = 'SS_userhelp';

require_once('conf/ConfigureFromEnv.php');

MySQLDatabase::set_connection_charset('utf8');

error_reporting(E_ALL);

// This line set's the current theme. More themes can be
// downloaded from http://www.silverstripe.org/themes/
SSViewer::set_theme('userhelp');

if(@$_GET['db'] == "sqlite3") {
	global $databaseConfig;
	$databaseConfig['type'] = 'SQLite3Database';
}

DocumentationService::set_automatic_registration(false);
DocumentationService::enable_meta_comments();

DocumentationViewer::set_link_base('');
DocumentationViewer::$check_permission = false;

try {
	DocumentationService::register("framework", BASE_PATH ."/src/framework_master/docs/", 'trunk');
	DocumentationService::register("framework", BASE_PATH ."/src/framework_3.1/docs/", '3.1', false, true);
	DocumentationService::register("framework", BASE_PATH ."/src/framework_3.0/docs/", '3.0');
} catch(InvalidArgumentException $e) {
	
} // Silence if path is not found (for CI environment)

DocumentationViewer::set_edit_link(
	'framework',
	'https://github.com/silverstripe/silverstripe-userhelp-content/edit/%version%/docs/%lang%/%path%',
	array(
		'rewritetrunktomaster' => true
	)
);


Object::add_extension('Controller', 'ControllerExtension');

if(Director::isLive()) {
	ControllerExtension::$google_analytics_code = 'UA-84547-10';
}

// Validator::set_javascript_validation_handler('none');	

DocumentationSearch::enable();
DocumentationSearch::set_meta_data(array(
	'ShortName' => 'SilverStripe Userhelp',
	'Description' => 'Userhelp for SilverStripe CMS / Framework',
	'Tags' => 'silverstripe sapphire php framework cms content management system'
));
// Set shared index (avoid issues with different temp paths between CLI and web users)
if(file_exists(BASE_PATH . '/.lucene-index')) {
	DocumentationSearch::set_index(BASE_PATH . '/.lucene-index');
}