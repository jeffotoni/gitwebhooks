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
    
// 
// 
// 

namespace web\src\Http;

//
//
//

use web\src\Http\Response as Response;   

//
//
//

use web\src\Http\Request as Request;

//
//
//

class NewRouter
{
    
    //
    //
    //

    private static $runing = false;

    //
    //
    //

    private static $END_POINT = null;
    
    //
    //
    //

    private static $CALLBACK = null;


    //
    //
    //

    private static $msgconcat = "";

    //
    //
    //

    private static $routeCollection = [];


    //
    //
    //

    private static $_METHOD = "GET";


    function __construct() {

        self::$runing = false;

        self::$END_POINT = null;

        self::$CALLBACK = null;

    }

    //
    //
    //

    public function Methods($method) {

        //
        //
        //

        $method = trim(strtoupper($method));

        //
        //
        //

        if(self::RequestMethod() == $method) {

            // 
            // 
            //
            
            self::$_METHOD = empty($method) ? "GET" : $method;


        } else {

            //
            //
            //

            self::$msgconcat .= "\nThe receiving method is [".self::RequestMethod()." != {$method}]\n";
        }

        return $this;
    }


    //
    //
    //

    public function HandleFunc($end_point, $callback)
    {

        //
        //
        //

        self::$END_POINT = $end_point;

        //
        //
        //

        self::$CALLBACK = $callback;


        //
        //
        //

        if(self::$runing) {

            //
            //
            //

            if (!isset(self::$routeCollection[self::$_METHOD])) {
                    
                //
                //    
                //        

                self::$routeCollection[self::$_METHOD] = [];
            }

            //
            //
            //

            $uri = substr($end_point, 0, 1) !== '/' ? '/' . $end_point : $end_point;

            //
            //
            //

            $pattern = str_replace('/', '\/', $uri);

            //
            //
            //

            $route = '/^' . $pattern . '$/';

            //
            //
            //
            

            self::$routeCollection[self::$_METHOD][$route] = $callback;
        }

        //
        //
        //

        return $this;
    }


    

    /** [Run description] */

    public function Run() {

        self::$runing = true;

        if(self::$END_POINT && self::$CALLBACK) {

            //
            //
            //

            $this->HandleFunc(self::$END_POINT, self::$CALLBACK);

            //
            //
            //

            if(self::RequestMethod()){

                //
                //
                //

                if (!isset(self::$routeCollection[self::RequestMethod()])) {

                    //
                    //
                    //

                    return null;
                }


                //
                //
                //

                $parameters['$response'] = new Response();


                //
                //
                //

                foreach (self::$routeCollection[self::RequestMethod()] as $route => $callback) {
                    
                    //
                    //
                    //

                    if (preg_match($route, self::RequestUri(), $arguments)) {

                  
                        //
                        // 
                        //

                        array_shift($arguments);
                        
                        //
                        //
                        //
                        

                        $parameters['$request'] = new Request($arguments);

                        //
                        //
                        //

                        return $this->callFunc($callback, $parameters);
                    }
                }
            }

            return null;


        } else {

            self::$msgconcat .= "Fatal error, Run() Could not execute!!";
            die("\n".self::$msgconcat."\n");

        }

    }


    //
    //
    //

    public function callFunc($callback, $arguments)
    {   

        //
        //
        //

        if (is_callable($callback)) {

            //
            //
            //

            return call_user_func_array($callback, $arguments);

        }

        //
        //
        //

        return null;
    }

    //
    //
    //

    private static function RequestMethod()
    {

        //
        //
        //

        return isset($_SERVER['REQUEST_METHOD']) ? strtoupper(trim($_SERVER['REQUEST_METHOD'])) : 'cli';    
    }

    //
    //
    //

    private static function RequestUri()
    {

        //
        //
        //

        $self = isset($_SERVER['PHP_SELF']) ? str_replace(array('index.php/'), '', $_SERVER['PHP_SELF']) : '';

        //
        //
        //

        $uri = isset($_SERVER['REQUEST_URI']) ? explode('?', $_SERVER['REQUEST_URI'])[0] : '';

        // 
        // Only locally
        //

        $uri = str_replace(array("/gitwebhooks"), array(), $uri);

        //
        //
        //

        if ($self !== $uri) {

            //
            //
            //

            $peaces = explode('/', $self);

            //
            //
            //

            array_pop($peaces);

            //
            //
            //

            $start = implode('/', $peaces);

            //
            //
            //

            $search = '/' . preg_quote($start, '/') . '/';

            //
            //
            //

            $uri = preg_replace($search, '', $uri, 1);
        }

        //
        //
        //

        return $uri;
    }
}