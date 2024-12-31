<?php

/**
 *	CertificateGeneration Helper
 */

function generateUniqueId(){

    $last6DigitUnique = substr(uniqid(), -5);
    $bytes = random_bytes(5);
    return $unique_id = bin2hex($bytes).$last6DigitUnique;
}

function directoryFunction($directoryName, $directoryNameYear){

    if (!file_exists($directoryName)) {
        $oldmask = umask(0);
        mkdir($directoryName, 0777, true);
        umask($oldmask);
        $f = fopen($directoryName . "/index.html", "w");
        fclose($f);
        if (!file_exists($directoryNameYear . "/index.html")) {
            $f = fopen($directoryNameYear . "/index.html", "w");
            fclose($f);
        }
    }
}

function saveCertificate($row, $fullPath, $unique_id, $app_info = 0){

    $row->certificate_link = $fullPath;
    $row->doc_id = $unique_id;
    $row->job_receiving_status = 1;
    $row->save();
    (!empty($app_info) && ($app_info->status_id == 5 || $app_info->status_id == 15 || ($app_info->status_id == 25 && $row->pdf_diff == 3))) ? '' : DB::table($row->table_name)->where('id', $row->app_id)->update([$row->field_name => $fullPath]);
}
