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


    function __construct() 
    {

        self::$runing = false;

        self::$END_POINT = null;

        self::$CALLBACK = null;

    }

    //
    //
    //

    public function Methods($method) 
    {

        //
        //
        //
        self::$runing = false;

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


    

    /**
 * [Run description] 
*/

    public function Run() 
    {

        if(self::$END_POINT && self::$CALLBACK) {

            //
            //
            //

            self::$runing = true;

            // echo "\n End point: ";
            // echo self::$END_POINT;
            // echo "\n";

            //
            // Endpoints must be the same
            //


            //
            //
            //

            $this->HandleFunc(self::$END_POINT, self::$CALLBACK);

            //
            //
            //

            self::$END_POINT = null;
            self::$CALLBACK = null;

            //
            //
            //

            if(self::RequestMethod()) {

                //
                //
                //

                if (!isset(self::$routeCollection[self::RequestMethod()])) {


                    //
                    //
                    //
                    self::$routeCollection = [];

                    return null;
                }


                //
                //
                //

                $parameters['$response'] = new Response();

                // print_r(self::$routeCollection);
                // exit("aqui...");

                //
                //
                //

                foreach (self::$routeCollection[self::RequestMethod()] as $route => $callback) {

                    // /^\/webhooks\/repository\/add\/{name}$/% 
                    // 
                    // ^\/webhooks\/repository\/add\/project1$\/\/
                    // 

                    $endPosition = explode("/", $route);
                    array_shift($endPosition);
                    array_pop($endPosition);
                    $last = sizeof($endPosition) - 1;

                    if($endPosition[$last] == '{name}$') {

                        $tmp_lasturipos = explode("/", self::RequestUri());
                        $lasturipos = end($tmp_lasturipos);

                        $endPosition[$last] = $lasturipos;

                        $routeNew = implode("/", $endPosition);
                        $routeNew = str_replace(array("/"), array("/"), $routeNew);
                        $routeNew = "/{$routeNew}$/";

                        //
                        // request
                        //

                        $value_name = $lasturipos;

                    } else {

                        $value_name = "";
                        $routeNew = $route;
                    }


                    if (preg_match($routeNew, self::RequestUri(), $arguments)) {
                    
                        //
                        // 
                        //

                        array_shift($arguments);
                        
                        //
                        //
                        //

                        $parameters['$request'] = new Request("name", $value_name);


                        //
                        //
                        //

                        self::$routeCollection = [];
                        
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