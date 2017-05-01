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
* 
*/

// Declara a interface 'TemplateHttp'
// 
// 

namespace web\src\Interfaces;

if(!interface_exists("TemplateHttp")) {

    //
    //    
    //        

    interface TemplateHttp
    {
        //
        //
        //

        public function showMsg();

        //
        //
        //

        public function showErro();
    }
}
