/*
*
* @about 	project GitHub Webhooks,
* Application responsible
* for receiving posts from github webhooks, and automating
* our production environment by deploying
*
* @autor 	@jeffotoni
* @date 	25/04/2017
* @since    Version 0.1
 */

package main

//
//
//

import (
	"fmt"
	"log"
	"net/http"
)

//
//
//

func main() {

	//
	//
	//

	fmt.Println("vim-go && you!!!")
	fmt.Println("server start port: 9003")

	log.Fatal(http.ListenAndServe(":9003", nil))

}
