<?php
declare(strict_types=1);

class BuildIndex {

     private function goto_regex(\SplFileObject $file, string $regex) : void
     { 
       foreach($file as $line) {
                 
           if (preg_match( $regex, $line) === 1)   
              break;
       }
     }
     
     public function __construct()
     {
     }

     function __invoke(string $fname)
     {
       $file = new \SplFileObject($fname, "r");

       $found = false;
       
       $child_given = '';
       
       $this->goto_regex($file, "@\|$@");
       
       while (!$file->eof()) { // foreach calls rewind()!
       
         $line = $file->fgets();
           
         $rc = preg_match("/^\|$/", $line);
       
         if ($rc === 1) 
             break;
        
         if ($found)  {

            $this->get_surname($line);
             
         } else if ($line[0] == "." && $line[1] == " ") {
       
             $found = true;
       
       /*
        Regular expression to match a sequence of given names
       Explanation:
       * preg_match(...) finds the first match only.
       
       * The (?<!\S) ensures that the match isn't preceded by a non-space character.
       
       * [A-Z][a-zäöü]+ matches a single name.
       
       * (?: [A-Z][a-zäöü]+)* allows additional names, each preceded by a space.
       
       * The /u modifier enables proper handling of umlaut characters like ä, ö, and ü.
       
       */ 
             $given_name_pattern = '/(?<!\S)([A-Z][a-zäöü]+(?: [A-Z][a-zäöü]+)*)/u';  // 'u' for Unicode support
       
             preg_match($given_name_pattern, substr($line, 2), $m);  
       
             $child_given = trim($m[0]);
         } 
       }
     }

     private function get_surname(string $line)
     { 
        if (substr($line, 0, 6) == 'Eltern') {
      
            $line = $file->fgets();
      
            $father_name_pattern = '/(?<!\S)((?:[A-Z][a-zäöüß]+ ){1,4})([A-Z][a-zäöüß]+)(?!\S)/u';
      
            if (preg_match($father_name_pattern, $line, $matches)) {
      
                $fatherGivenNames = trim($matches[1]); // The space after the last given name is captured, so trim it.
                $surname = $matches[2];
                
                echo "Given names: $fatherGivenNames\n";
                echo "Surname: $surname\n";
      
            } else {
                echo "No full name found.\n";
            }
      
            echo "Child name: $child_given $surname\n";
            
            $found = false;
        }
     }
}

