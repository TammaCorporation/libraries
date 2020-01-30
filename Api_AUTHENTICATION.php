<?php
// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Methods: POST, DELETE, OPTIONS');
// header('Access-Control-Max-Age: 1000');
/**
 * *********************************************************************************************************
 * @_forProject: M.O.E Survey Application | Developed By: TAMMA CORPORATION
 * @_purpose: (Ensures only authorized apps interface with API) 
 * @_version Release: package_two
 * @_created Date: 00/00/2019
 * @_author(s):
 *   1) Mr. Michael kaiva Nimley. (Hercules d Newbie)
 *      @contact Phone: (+231) 777-007-009
 *      @contact Mail: michaelkaivanimley.com@gmail.com, mnimley6@gmail.com, mnimley@tammacorp.com
 *   --------------------------------------------------------------------------------------------------
 *   2) Fullname of engineer. (Code Name)
 *      @contact Phone: (+231) 000-000-000
 *      @contact Mail: -----@tammacorp.com
 * *********************************************************************************************************
 */

/**
* NOTE: for security purposes, hide server response headers
*
* @package default
* @author 
**/
trait server_auth 
{
    use audit;

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
     protected function server_authentication()
     {
        //
        $errorMsg = json_encode(['status' => '401 Authentication failed'], JSON_PRETTY_PRINT);

        # redirect requesting app to 404 page 
        # if auth is not provided in header
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="School-Mass REST API"');
            header('Error-Msg: HTTP/1.0 401 Unauthorized');
            print $errorMsg;
            exit;
        } else {
            // 
            if (password_verify("schoolmass", $_SERVER['PHP_AUTH_USER']) == 1) {
                // 
                if ( password_verify('123456789', $_SERVER['PHP_AUTH_PW']) != 1 ) 
                {
                    print $errorMsg;
                    header('refresh:1; url= '.__401PAGE__.'');
                    exit;
                } 
                else {
                    # allow app to carry on
                    return __Authenticated__;
                }
            } else {
                print $errorMsg;
                // 
                header('refresh:2; url= '.__401PAGE__.'');
                exit;
            }
        }
     }

} // END class server_auth 

?>
