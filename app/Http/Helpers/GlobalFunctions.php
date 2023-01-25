<?php

use App\Models\FilePermission;
use App\Models\FolderControl;
use App\Models\FolderPermission;
use App\Models\FormatFile;
use App\Models\FormatFolder;
use App\Models\Project;
use App\Models\ProjectPermission;
use Illuminate\Support\Facades\Auth;

function typeFile($data,$size=null)
{
    if ($data=='application/zip' || $data=='application/x-rar-compressed' || $data=='application/x-compressed') {
        return '<i class="fa fa-file-zipper '.$size.'" style="color: #FFCC41;"  ></i>';
    }elseif($data=='application/pdf'){
        return '<i class="fa fa-file-pdf '.$size.'" style="color: #FA1D0F;"></i>';
    }elseif($data=='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
        return '<i class="fa fa-file-excel'.$size.'" style="color: #107C41;" ></i>';
    }elseif($data=='application/vnd.openxmlformats-officedocument.wordprocessingml.document'){
        return '<i class="fa fa-file-word '.$size.'" style="color: #185ABD;"></i>';
    }elseif($data=='image/png' || $data=='image/jpeg' || $data=='image/bmp' || $data=='image/svg+xml'){
        return '<i class="fa fa-file-image '.$size.'" style="color: #436CCB;"></i>';
    }elseif($data=='text/plain' || $data=='application/xml'){
        return '<i class="far fa-file-lines '.$size.'"></i>';
    }elseif($data=='text/calendar'){
        return '<i class="fa fa-calendar '.$size.'" style="color: #436CCB;"></i>';
    }elseif($data=='audio/mpeg'){
        return '<i class="far fa-file-audio '.$size.'" style="color: #F98F51;"></i>';
    }elseif($data=='application/msaccess'){
        return '<i class="fa fa-database '.$size.'" style="color: #436CCB;"></i>';
    }elseif($data=='application/vnd.openxmlformats-officedocument.presentationml.presentation'){
        return '<i class="far fa-file-powerpoint '.$size.'" style="color: #C43E1C;"></i>';
    }elseif($data=='application/x-ms-dos-executable' || $data=='application/x-msi'){
        return '<i class="fab fa-windows '.$size.'" style="color: #39C1FF;"></i>';
    }elseif($data=='video/mp4' || $data=='video/x-msvideo'){
        return '<i class="far fa-file-video '.$size.'"></i>';
    }elseif($data=='text/csv'){
        return '<i class="fa fa-file-csv '.$size.'" style="color: #007901;"></i>';
    }else{
        return '<i class="fa fa-file '.$size.'"></i>';
    }


}

function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    // Uncomment one of the following alternatives
    $bytes /= pow(1024, $pow);
    //$bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . ' ' . $units[$pow];
}

function getFolderData($folder_route)
{
    return FolderControl::where('folder_route',$folder_route)->first();
}

function getStatus(int $number)
{
    if ($number==1) {
        return 'Activo';
    }elseif ($number==1) {
        return 'Pendiente';
    }elseif ($number==1) {
        return 'Terminado';
    }
    elseif ($number==1) {
        return 'Cancelado';
    }

}

function fileTypes()
{
    return [
        'archivo' => 'Subir Archivo',
        'texto' => 'Crear archivo de texto',
        'link' => 'Acceso Directo',
    ];
}

function canProject( $id, $permission)
{
    $project = Project::where('id',$id)->first();
    if ($project->user_created_id == Auth::user()->id || $project->user_manager_id == Auth::user()->id) {
        return true;
    } else {
        $p = ProjectPermission::where('project_id',$project->id)->where('user_id',Auth::user()->id)->where($permission,1)->first();
        //dd(Auth::user()->id);
        if ($p===null) {
            return false;
        }else{
            return true;
        }
    }
}

function canFolder( $id, $permission)
{
    $folder = FormatFolder::where('id',$id)->first();
    if ($folder->folder_user_id == Auth::user()->id) {
        return true;
    } else {
        $f = FolderPermission::where('folder_id',$folder->id)->where('user_id',Auth::user()->id)->where($permission,1)->first();
        //dd(Auth::user()->id);
        if ($f===null) {
            return false;
        }else{
            return true;
        }
    }
}
function canFile( $id, $permission)
{
    $file = FormatFile::where('id',$id)->first();
    if ($file->file_user_id == Auth::user()->id) {
        return true;
    } else {
        $fi = FilePermission::where('file_id',$file->id)->where('user_id',Auth::user()->id)->where($permission,1)->first();
        //dd(Auth::user()->id);
        if ($fi===null) {
            return false;
        }else{
            return true;
        }
    }
}

function getClientIp() {
    $ipaddress =
        getenv('HTTP_CLIENT_IP')?:
        getenv('HTTP_X_FORWARDED_FOR')?:
        getenv('HTTP_X_FORWARDED')?:
        getenv('HTTP_FORWARDED_FOR')?:
        getenv('HTTP_FORWARDED')?:
        getenv('REMOTE_ADDR')?:
        'Storage';

    $ipaddress = preg_replace("/[^0-9a-zA-Z.=]/", "_", $ipaddress);

    return $ipaddress;
}

function serverPath($forDocumentServer = NULL) {
    return $forDocumentServer && isset($GLOBALS['EXAMPLE_URL']) && $GLOBALS['EXAMPLE_URL'] != ""
        ? $GLOBALS['EXAMPLE_URL']
        : (getScheme() . '://' . $_SERVER['HTTP_HOST']);
}

function getScheme() {
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
}
