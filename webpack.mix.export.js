/**
 * Plugin Export Script
 *
 * Exports the production-ready plugin with only the files and folders needed for
 * uploading to a site or zipping. Edit the `files` or `folders` variables if
 * you need to change something.
 *
 * This configuration is a copy of the configuration from https://github.com/justintadlock/mythic/
 * modified to fit Pressmodo's plugins purposes.
 */

// Import required packages.
const mix     = require( 'laravel-mix' );
const rimraf  = require( 'rimraf' );
const fs      = require( 'fs' );

// Folder name to export the files to.
let exportPath = 'posterno-elementor';

// Plugin root-level files to include.
let files = [
	'posterno-elementor.php',
	'readme.txt',
	'uninstall.php'
];

// Folders to include.
let folders = [
	'vendor',
	'templates',
	'includes',
	'languages',
];

// Delete the previous export to start clean.
rimraf.sync( exportPath );

// Loop through the root files and copy them over.
files.forEach( file => {

	if ( fs.existsSync( file ) ) {
		mix.copy( file, `${exportPath}/${file}` );
	}
} );

// Loop through the folders and copy them over.
folders.forEach( folder => {

	if ( fs.existsSync( folder ) ) {
		mix.copyDirectory( folder, `${exportPath}/${folder}` );
	}
} );

// Delete the `vendor/bin` and `vendor/composer/installers` folder, which can
// get left over, even in production. Mix will also create an additional
// `mix-manifest.json` file in the root, which we don't need.
mix.then( () => {

	let files = [
		'mix-manifest.json',
		`${exportPath}/vendor/bin`,
		`${exportPath}/vendor/composer/installers`,
		`${exportPath}/vendor/composer/installers`,
		`${exportPath}/dist/mix-manifest.json`,
		`${exportPath}/dist/mix-js.map`,
		`${exportPath}/dist/fonts`,
		`${exportPath}/dist/svg`,
		`${exportPath}/resources/admin/node_modules`,
		`${exportPath}/resources/admin/public`,
		`${exportPath}/resources/admin/src`,
		`${exportPath}/resources/admin/.browserslistrc`,
		`${exportPath}/resources/admin/.eslintrc.js`,
		`${exportPath}/resources/admin/babel.config.js`,
		`${exportPath}/resources/admin/package.json`,
		`${exportPath}/resources/admin/postcss.config.js`,
		`${exportPath}/resources/admin/vue.config.js`,
		`${exportPath}/resources/admin/yarn.lock`,
		`${exportPath}/resources/admin/.gitignore`,
		`${exportPath}/resources/admin/dist/favicon.ico`,
		`${exportPath}/resources/admin/dist/index.html`,
		`${exportPath}/vendor/dealerdirect`,
		`${exportPath}/vendor/phpcompatibility`,
		`${exportPath}/vendor/squizlabs`,
		`${exportPath}/vendor/wp-coding-standards`,
		`${exportPath}/vendor/**/.git`,
		`${exportPath}/vendor/**/.editorconfig`,
		`${exportPath}/vendor/**/.gitignore`,
		`${exportPath}/vendor/**/composer.json`,
		`${exportPath}/vendor/**/composer.lock`,
		`${exportPath}/vendor/**/LICENSE`,
		`${exportPath}/vendor/**/phpcs.xml`,
		`${exportPath}/vendor/**/readme.md`,
		`${exportPath}/vendor/**/.gitattributes`,
		`${exportPath}/dist/**/*.map`,
	];

	files.forEach( file => {
		rimraf.sync( file );
	} );
} );
