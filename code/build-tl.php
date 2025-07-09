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

      $this->ofile->fwrite($header);
   }

   public function __invoke(string $fname)
   {
      $file = new \SplFileObject($fname, "r");
      
      $header = $file->fgets();
      
      goto_regex($file, "@^== Family Group@");
      
      while (!$file->eof()) { // foreach would call rewind()!
      
         $line = $file->fgets();
          
         // We exit when a line starts with "== "
         $rc = preg_match("/^== /", $line); 
      
         if ($rc === 1) 
             break;
      
          $this->ofile->fput($line);
      }
   }
}

$creator = new TimelineCreator("output.adoc", "= Johann Heinrich Timeline\n");

$files = ["/home/kurt/jhk/m/p/petzen-band1a-image220"];

foreach($files as $file) {

   $creator($file);  
}
