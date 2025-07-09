<?php
declare(strict_types=1);

function goto_regex(\SplFileObject $file, string $regex) : void
{ 
   foreach($file as $line) {
            
       if (preg_match( $regex, $line) === 1)   
           break;
   }
}

class TimelineCreator {

   private \SplFileObject $ofile;

   public function __construct(string $outfile, string $header)
   {
      $this->ofile = new \SplFileObject($outfile, "w");

      $this->ofile->fwrite($header . "\n");
   }

   public function __invoke(string $fname)
   {
      $file = new \SplFileObject($fname, "r");
      
      $header = $file->fgets();

      $this->ofile->fwrite("=" . $header);
      
      goto_regex($file, "@^== Family Relationship@");
      
      while (!$file->eof()) { // foreach would call rewind()!
      
         $line = $file->fgets();
          
         // We exit when a line starts with "== "
         $rc = preg_match("/^== /", $line); 
      
         if ($rc === 1) 
             break;
      
          $this->ofile->fwrite($line);
      }
   }
}

$creator = new TimelineCreator("/home/kurt/adocs-4-genealogy/m/timelines/p/jhk-timeline.adoc", "= Johann Heinrich Timeline\n:page-role: doc-width\n");

$folder = "/home/kurt/adocs-4-genealogy/m/petzen/p/";

$files = [
"petzen-band1a-image220.adoc",
"petzen-band1a-image320.adoc",
"petzen-band2-image5-1.adoc",
"petzen-band2-image52.adoc",
"petzen-band2-image59-60.adoc",
"petzen-band2-image230.adoc",
"petzen-band2-image71.adoc",
"petzen-band2-image82-1.adoc",
"petzen-band2-image243.adoc",
"petzen-band2-image201.adoc",
"petzen-band2-image207-2.adoc",
//"petzen-band2-image27-2.adoc",
"petzen-band2-image339.adoc",
"petzen-band2-image211-3.adoc",
"petzen-band2-image348.adoc",
];

foreach($files as $file) {
 
   $creator($folder . $file);  
}
