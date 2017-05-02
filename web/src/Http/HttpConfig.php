<?php
/**
*
* @about     project GitHub Webhooks, 
* Application responsible 
* for receiving posts from github webhooks, and automating 
* our production environment by deploying
* 
* @autor     @jeffotoni
* @date     25/04/2017
* @since    Version 0.1
*/

// 
// 
// 

namespace web\src\Http;


//
// To handle the post coming from github
//

class HttpConfig
{
    
    public function Generate() 
    {

        //print "\nGenerating config!!\n";

        $i = $j = 0;
        $vetor = "|/~\\";

        //
        //
        //

        fprintf(STDOUT, "\033[?25l"); // hide cursor

        //
        //
        //

        while(true) {

            print "\r";
            print $vetor{$i};

            if(strlen($vetor) - 1 == $i) {
                $i = 0; 
            }

            ++$i;
            ++$j;
            usleep(7200);

            if($j == 50) {
                break; 
            }
        }
            
        //
        //
        //

        fprintf(STDOUT, "\033[?25h"); // show cursor
        

        //
        //
        //
        
        if(is_file(PATH_REPOSITORY)) {

            print "\n\033[32m Generated Config successfully!!";
            print "\n";

        } else {

            print "\n\033[33m Something wrong has occurred!!";
        }
    }
}