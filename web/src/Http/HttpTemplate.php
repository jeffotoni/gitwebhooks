<?php
/**
*
* @about    project GitHub Webhooks, 
* Application responsible 
* for receiving posts from github webhooks, and automating 
* our production environment by deploying
* 
* @autor    @jeffotoni
* @date     25/04/2017
* @since    Version 0.1
*/

namespace web\src\Http;

// 
// 
// 

if(!class_exists("HttpTemplate")) {

    echo "\nI'm here!!!";

    class HttpTemplate implements TemplateHttp
    {
        
        //
        //
        //

        public function showMsg()
        {
            print "\n";

            print "I'm in HttpTemplate() implements TemplateHttp / -> ". __METHOD__;

            print "\n";
        }

        //
        //
        //

        public function showErro()
        {
            
            print "\n";

            print "I'm in HttpTemplate() implements TemplateHttp / -> ". __METHOD__;

            print "\n";

        }
    }
}