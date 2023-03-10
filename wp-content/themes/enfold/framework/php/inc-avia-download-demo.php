<?php
/**
 * Download demo files from external server
 *
 * @since 4.8.2
 * @added_by Günter
 */
if( ! defined( 'AVIA_FW' ) )	{	exit( 'No direct script access allowed' );	}

global $avia_config;

$demo_name = ! empty( $_REQUEST['demo_name'] ) ? stripslashes( $_REQUEST['demo_name'] ) : '';
$demo_full_name = ! empty( $_REQUEST['demo_full_name'] ) ? stripslashes( $_REQUEST['demo_full_name'] ) : '';
$debug_prefix = sprintf( __( 'Demo File Download (%s):', 'avia_framework' ), $demo_full_name ) . ' ';

if( defined( 'WP_DEBUG' ) && WP_DEBUG )
{
	error_log( $debug_prefix . __( 'Download started', 'avia_framework' ) );
}

if( empty( $_REQUEST['download_url'] ) || empty( $_REQUEST['import_dir'] ) || empty( $_REQUEST['demo_name'] ) )
{
	$msg = __( 'To few parameters provided - a download is not possible.', 'avia_framework' );
	if( defined( 'WP_DEBUG' ) && WP_DEBUG )
	{
		error_log( $debug_prefix . $msg );
	}
	exit( 'avia_error-' . $msg );
}


$tmp_filename = download_url( $_REQUEST['download_url'] );
if( $tmp_filename instanceof WP_Error )
{
	$msg = __( 'Error accessing file for download:<br /><br />', 'avia_framework' ) . implode( '<br>', $tmp_filename->get_error_messages() );
	if( defined( 'WP_DEBUG' ) && WP_DEBUG )
	{
		error_log( $debug_prefix . $msg );
	}
	exit( 'avia_error-' . $msg );
}

avia_backend_delete_folder( $_REQUEST['import_dir'] );

if( ! avia_backend_create_folder( $_REQUEST['import_dir'] ) )
{
	$msg = sprintf( __( 'Unable to create the download folder <pre>%s</pre> for demo files.', 'avia_framework' ), $_REQUEST['import_dir'] );
	if( defined( 'WP_DEBUG' ) && WP_DEBUG )
	{
		error_log( $debug_prefix . $msg );
	}

	exit( 'avia_error-' . $msg );
}

$zip = new ZipArchive();
if ( ! $zip->open( $tmp_filename ) )
{
	$msg = __( 'Wasn\'t able to work with Zip Archive', 'avia_framework' );
	if( defined( 'WP_DEBUG' ) && WP_DEBUG )
	{
		error_log( $debug_prefix . $msg );
	}

	exit( 'avia_error-' . $msg );
}

/*
 * iOS systems may add system files to zip like _MACOSX.
 * We check for first files to copy and ignore other files included.
 *
 * Files must be located like:
 *
 *		$demo_name.*
 *
 * or
 *
 *		$demo_name/$demo_name.*
 *
 * @since 5.0
 */
$imported = array(
			'xml'	=> false,
			'txt'	=> false
		);

$check1 = $demo_name;
$check2 = $demo_name . '/' . $demo_name;

for( $i = 0; $i < $zip->numFiles; $i++ )
{
	try
	{
		$source_file = $zip->getNameIndex( $i );
		$source_file_check = trim( str_replace( '\\', '/', $source_file ) );

		if( 0 !== stripos( $source_file_check, $check1 ) && 0 !== stripos( $source_file_check, $check2 ) )
		{
			continue;
		}

		if( false === stripos( $source_file_check, 'xml' ) && false === stripos( $source_file_check, 'txt' ) && false === stripos( $source_file_check, 'php' ) )
		{
			continue;
		}

		if( false !== stripos( $source_file_check, 'xml' ) )
		{
			if( $imported['xml'] )
			{
				continue;
			}

			$imported['xml'] = true;
		}
		else
		{
			if( $imported['txt'] )
			{
				continue;
			}

			$imported['txt'] = true;
		}

		$skip = stripos( $source_file_check, '/' );
		if( false === $skip )
		{
			$source_file_name = $source_file_check;
		}
		else
		{
			$source_file_name = substr( $source_file_check, $skip + 1 );
		}

		$dest_file = trailingslashit( $_REQUEST['import_dir'] ) . $source_file_name;

		$fp = $zip->getStream( $source_file );
		if( ! $fp )
		{
			throw new Exception();
		}

		$ofp = fopen( $dest_file, 'w' );
		if( false === $ofp )
		{
			throw new Exception();
		}

		while( ! feof( $fp ) )
		{
			$content = fread( $fp, 8192 );
			if( false === $content )
			{
				throw new Exception();
			}
			if( false === fwrite( $ofp, $content ) )
			{
				throw new Exception();
			}
		}

		fclose( $fp );
		fclose( $ofp );
	}
	catch( Exception $ex )
	{
		if( ! empty( $fp ) )
		{
			fclose( $fp );
		}
		if( ! empty( $ofp ) )
		{
			fclose( $ofp );
		}

		$zip->close();
		unlink( $tmp_filename );

		avia_backend_delete_folder( $_REQUEST['import_dir'] );

		$msg = __( 'Wasn\'t able to read demo files from downloaded zip file.', 'avia_framework' );
		if( defined( 'WP_DEBUG' ) && WP_DEBUG )
		{
			error_log( $debug_prefix . $msg );
		}
		exit( 'avia_error-' . $msg );
	}
}

$zip->close();
unlink( $tmp_filename );

