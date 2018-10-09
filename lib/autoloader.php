<?php

namespace WPS\Lib;


/*

get_last_index

*/
function get_last_index($namespace_chunks) {

	if ( empty($namespace_chunks) ) {
		return 0;
	}

	return count($namespace_chunks) - 1;

}


/*

get_last_value

*/
function get_last_value($namespace_chunks) {

	if ( empty($namespace_chunks) ) {
		return '';
	}

	return strtolower( $namespace_chunks[ get_last_index($namespace_chunks) ] );

}


/*

is_large_chunk

*/
function is_large_chunk($namespace_chunks) {
	return count($namespace_chunks) > 1;
}


/*

split_namespace_into_chunks

*/
function split_namespace_into_chunks($namespace) {
	return array_filter( explode('\\', $namespace) );
}


/*

replace_underscores_with_dashes

*/
function replace_underscores_with_dashes($chunk_name) {
	return str_ireplace( '_', '-', $chunk_name);
}


/*

replace_spaces_with_dashes

*/
function replace_spaces_with_dashes($chunk_name) {
	return str_ireplace( ' ', '-', $chunk_name);
}


/*

get_plugin_classes_path

*/
function get_plugin_classes_path() {
	return trailingslashit( dirname( dirname( __FILE__ ) ) ) . 'classes/';
}


/*

add_folder_name

*/
function add_folder_name($folder_name) {
	return strtolower( trailingslashit( replace_spaces_with_dashes($folder_name) ) );
}


/*

remove_packagename_from_chunks

*/
function remove_packagename_from_chunks($namespace_chunks, $packagename) {

	return array_values( array_filter($namespace_chunks, function($namespace_chunk) use ($packagename) {

		if ($namespace_chunk === false) {
			return false;
		}

		return $packagename !== $namespace_chunk;

	}));

}


/*

remove_filename_from_chunks

*/
function remove_filename_from_chunks($namespace_chunks) {

	array_pop($namespace_chunks);

	return $namespace_chunks;

}


/*

build_folder_structure

*/
function build_folder_structure($namespace_chunks) {

	// We know it's a top-level file
	if ( !is_array($namespace_chunks) || empty($namespace_chunks) ) {
		return '';
	}

	$folder_path = '';

	foreach ($namespace_chunks as $namespace_chunk) {
		$folder_path .= add_folder_name($namespace_chunk);
	}

	return $folder_path;

}


/*

build_filename

*/
function build_filename($filename) {

	if ( !is_string($filename) ) {
		return '';
	}

	return "class-" . replace_spaces_with_dashes( replace_underscores_with_dashes( strtolower($filename) ) ) . ".php";

}


/*

combine_folder_and_filename

*/
function combine_folder_and_filename($folder_structure, $file_name) {
	return $folder_structure . $file_name;
}


/*

Removes any trailing dashes

*/
function remove_trailing_dash($string) {
	return rtrim($string, "-");
}


/*

Find file to load

*/
function find_file_to_autoload($namespace, $plugin_path) {

	// Separates the components of the incoming namespace
	$namespace_chunks 				= split_namespace_into_chunks($namespace);
	$chunks_no_packagename 		= remove_packagename_from_chunks($namespace_chunks, 'WPS');
	$chunks_no_filename 			= remove_filename_from_chunks($chunks_no_packagename);
	$folder_structure 				= build_folder_structure($chunks_no_filename);
	$file_name 								= build_filename( get_last_value($namespace_chunks) );

	$plugin_path 							.= combine_folder_and_filename($folder_structure, $file_name);

	// Now we include the file.
	if ( file_exists( $plugin_path ) ) {
		return $plugin_path;
	}

	return false;

}


/*

Let's load this thing

*/
function autoload() {

	$plugin_path = get_plugin_classes_path();

	spl_autoload_register( function($namespace) use ($plugin_path) {

		$file_path = find_file_to_autoload($namespace, $plugin_path);

		if ($file_path) {
			include_once($file_path);
		}

	});

}

autoload();
