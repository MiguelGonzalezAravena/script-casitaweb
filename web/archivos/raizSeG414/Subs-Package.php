<?php
//Pagina de Rodrigo Zaupa (rigo@casitaweb.net)
if (!defined('CasitaWeb!-PorRigo'))die(base64_decode("d3d3LmNhc2l0YXdlYi5uZXQgLSByaWdv"));
function read_tgz_file($gzfilename, $destination, $single_file = false, $overwrite = false){}
function read_tgz_data($data, $destination, $single_file = false, $overwrite = false){}
function read_zip_data($data, $destination, $single_file = false, $overwrite = false){}
function url_exists($url){}
function loadInstalledPackages(){}
function saveInstalledPackages($instmods){}
function getPackageInfo($gzfilename){}
function packageRequireFTP($destination_url, $files = null){}
function parsePackageInfo(&$packageXML, $testing_only = true, $method = 'install', $previous_version = ''){}
function matchPackageVersion($version, $versions){}
function parse_path($path){}
function deltree($dir, $delete_dir = true){}
function mktree($strPath, $mode){}
function copytree($source, $destination){}
function listtree($path, $sub_path = ''){}
function parseModification($file, $testing = true, $undo = false){}
function parseBoardMod($file, $testing = true, $undo = false){}
function package_get_contents($filename){}
function package_flush_cache($trash = false){}
function package_chmod($filename){}
function package_crypt($pass){}
function package_create_backup($id = 'backup'){}
function fetch_web_data($url, $post_data = '', $keep_alive = false){}
class xmlArray{}
class ftp_connection{}
if (!function_exists('smf_crc32')){}

?>