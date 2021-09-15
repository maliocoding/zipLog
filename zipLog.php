<?php

date_default_timezone_set("Asia/Jakarta");
define('BASE_PATH', realpath(dirname(__FILE__)));


$folders = ['NOBUPaymentBackend','DanaPaymentBackend','GopayPaymentBackend','ShopeepayPaymentBackend','AstrapayPayment','OVOPaymentBackend','MegaPaymentBackend','BCAParser'];
$zip = new ZipArchive();

foreach ($folders as $folder) {
	if (!empty($argv[1])) { //for manual date input
		$date = date("Ym", strtotime($argv[1]));
	}else{
		$dt2 = new DateTime("-1 month");
		$date = $dt2->format("Ym");
	}


	$logFolder = BASE_PATH.'/'.$folder.'/log/';
	$compressed_folder = $logFolder.'compressed';

	if (!file_exists($compressed_folder)){
		mkdir($compressed_folder,0775);
	}
    
	// Get real path for our folder
	$rootPath = realpath($logFolder);

	// Initialize archive object	
	$zip->open($compressed_folder.'/'.$date.'.zip', ZipArchive::CREATE);
	$filesToDelete = array();

	// Create recursive directory iterator
	/** @var SplFileInfo[] $files */
	$files = new RecursiveIteratorIterator(
	    new RecursiveDirectoryIterator($rootPath),
	    RecursiveIteratorIterator::LEAVES_ONLY
	);

	$files = scandir($logFolder); 
	foreach($files as $file){
	   if(is_file($logFolder.$file)){
	     $getDate = substr($file, -12,-6);
	     if($getDate == $date){

	     //  Add current file to archive
	       $moved = $zip->addFile($logFolder.$file, $file);
		   if($moved){
		   		echo $file." success archived \n";
	     		$filesToDelete[] = $logFolder.$file; //add list file already archived to remove
		   }
			
	     }

	  }
	}

	$zip->close();
	if(empty(!$filesToDelete)){
		foreach ($filesToDelete as $file){ // remove file lists
		    unlink($file);
		}
	}
	
}

// Zip archive will be created only after closing object



?>