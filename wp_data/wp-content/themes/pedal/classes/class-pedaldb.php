<?php
/**
 * Custom Database Class
 *
 * @package Pedal
 * @since Pedal 1.0
 */

 class PedalDB
 {
    public function __construct( $ip, $db_name, $username, $pwd )
    {
        $connectionOptions = array(
            "database" => $db_name,
            "uid" => $username,
            "pwd" => $pwd
        );

        $this->conn = sqlsrv_connect( $ip, $connectionOptions );
    }

    public function log_sql_errors( $errors )
    {
        // Display errors
        $error_string = "QL Error:\n\tError information:\n\t\t";

        foreach ($errors as $error) 
        {
            $error_string = $error_string."SQLSTATE: ".$error['SQLSTATE']."\n\t\tCode: ".$error['code']."\n\t\tCode: ".$error['code']."\n\t\tMessage: ".$error['message'];
        }

        error_log( $error_string );
    }

    public function get_num_tickets()
    {
        error_log("Entering GNT");
        $sql = "select ticket_number from tickets";
        $result = sqlsrv_query( $this->conn, $sql, array(), array( "Scrollable" => 'static' ) );

        if ( $result === false )
        {
            error_log("Exiting GNT:ERROR");
            $this->log_sql_errors( sqlsrv_errors() );
            return null;
        }

        error_log("Exiting GNT");
        return sqlsrv_num_rows( $result );
    }

    public function get_ticket( $ticket_number )
    {
        global $wpdb;
        if ( is_numeric( $ticket_number ) )
        {
            $ticket_number = intval($ticket_number);
        }
        else
        {
            error_log( "class-pedaldb.php:get_ticket - Invalid ticket number: ".$ticket_number );
            return null;
        }

        $sql = $wpdb->prepare( "select * from tickets where ticket_number = %d", $ticket_number );
        $result = sqlsrv_query( $this->conn, $sql );

        if ( $result === false )
        {
            $this->log_sql_errors( sqlsrv_errors() );
            return null;
        }

        return sqlsrv_fetch_object( $result );
    }

    public function get_tickets_summary( $order_by = 'ticket_number' )
    {
        global $wpdb;
        $sql = $wpdb->prepare( "select ticket_number, fname, lname, datetime_in, datetime_quote, datetime_complete, datetime_out, ticket_status from tickets order by %s", $order_by );

        $result = sqlsrv_query( $this->conn, $sql );

        if ( $result === false )
        {
            $this->log_sql_errors( sqlsrv_errors() );
            return null;
        }

        $rows = array();
        while ( $row = sqlsrv_fetch_object( $result ) )
        {
            $rows[] = $row;
        }

        return $rows;
    }

    public function search_by_fName( $fname, $order_by = 'ticket_number' )
    {
        global $wpdb;
        $sql = $wpdb->prepare( "select ticket_number, fname, lname, datetime_in, datetime_quote, datetime_complete, datetime_out, ticket_status from tickets where fname = %s order by %s", $fname, $order_by );

        $result = sqlsrv_query( $this->conn, $sql );

        if ( $result === false )
        {
            $this->log_sql_errors( sqlsrv_errors() );
            return null;
        }

        $rows = array();
        while ( $row = sqlsrv_fetch_object( $result ) )
        {
            $rows[] = $row;
        }

        return $rows;
    }

    public function search_by_lName( $lname, $order_by = 'ticket_number' )
    {
        global $wpdb;
        $sql = $wpdb->prepare( "select ticket_number, fname, lname, datetime_in, datetime_quote, datetime_complete, datetime_out, ticket_status from tickets where lname = %s order by %s", $lname, $order_by );

        $result = sqlsrv_query( $this->conn, $sql );

        if ( $result === false )
        {
            $this->log_sql_errors(sqlsrv_errors());
            return null;
        }

        $rows = array();
        while ( $row = sqlsrv_fetch_object( $result ) )
        {
            $rows[] = $row;
        }

        return $rows;
    }

    public function search_by_cwid( $cwid, $order_by = 'ticket_number' )
    {
        global $wpdb;
        $sql = $wpdb->prepare( "select ticket_number, fname, lname, datetime_in, datetime_quote, datetime_complete, datetime_out, ticket_status from tickets where cwid = %s order by %s", $cwid, $order_by );

        $result = sqlsrv_query( $this->conn, $sql );

        if ( $result === false )
        {
            $this->log_sql_errors(sqlsrv_errors());
            return null;
        }

        $rows = array();
        while ( $row = sqlsrv_fetch_object( $result ) )
        {
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * 
     * Finds sql db entries with matching status and sorts them.
     * Default sort is by ticket number
     * 
     * @param string    $status
     * @param string    $order_by sql db column name OPTIONAL
     * 
     * @return array    collection of sql return classes. Each column of the sql db has a member variable of the class named acordiingly.
     * 
     */
    public function search_by_status( $status, $order_by = 'ticket_number' )
    {
        global $wpdb;
        $sql = $wpdb->prepare( "select ticket_number, fname, lname, datetime_in, datetime_quote, datetime_complete, datetime_out, tiket_status from tickets where ticket_status = %s order by %s", $status, $oder_by );

        $result = sqlsrv_query( $this->conn, $sql );

        if ( $result === false )
        {
            $this->log_sql_errors( sqlsrv_errors() );
            return null;
        }

        $rows = array();
        while ( $row = sqlsrv_fetch_object( $result ) )
        {
            $rows[] = $row;
        }

        return $rows;
    }

    public function get_work_requested( $ticket_number )
    {
        global $wpdb;

        $sql = $wpdb->prepare( "select * from work_requested where ticket_number = %d", $ticket_number );

        $result = sqlsrv_query( $this->conn, $sql );

        if ( $result === false )
        {
            $this->log_sql_errors( sqlsrv_errors() );
            return null;
        }

        $rows = array();
        while ( $row = sqlsrv_fetch_object( $result ) )
        {
            $rows[] = $row;
        }

        return $rows;
    }

    public function get_work_done( $ticket_number )
    {
        global $wpdb;

        $sql = $wpdb->prepare( "select * from work_done where ticket_number = %d", $ticket_number );

        $result = sqlsrv_query( $this->conn, $sql );

        if ( $result === false )
        {
            $this->log_sql_errors( sqlsrv_errors() );
            return null;
        }

        $rows = array();
        while ( $row = sqlsrv_fetch_object( $result ) )
        {
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * 
     * Checks if all the data types are valid and trys to add them to the database.
     * If a paramater is invalid or the entry to the db cannot be made,
     * false is returned.
     * Otherwise true is returned.
     * 
     * @param string    $cwid           alpha-numeric string
     * @param string    $fname          A-Za-z charecter string
     * @param string    $lname          A-Za-z charecter string
     * @param string    $phn            numeric string
     * @param string    $email          valid email address
     * @param string    $bike_make      non-empty string w/o special chars
     * @param string    $bike_model     non-empty string w/o special chars
     * @param string    $bike_color     A-Za-z charecter string
     * @param string    $bike_other     string w/o special chars (can be empty)
     * @param bool      $quote_under_30
     * @param string    $work_requested non-empty string w/o special chars
     * 
     * @return bool
     * 
     */
    public function add_ticket( $cwid, $fname, $lname, $phn, $email, $bike_make, $bike_model,
                         $bike_color, $bike_other, $quote_under_30, $customer_comments )
    {
        global $wpdb;

        if( ctype_alnum( $cwid ) )
        {
            if( ctype_alpha( $fname ) )
            {
                if( ctype_alpha( $lname ) )
                {
                    if( ctype_digit( $phn ) )
                    {
                        if( filter_var( $email, FILTER_VALIDATE_EMAIL ) )
                        {
                            if( filter_var( $bike_make, FILTER_VALIDATE_REGEXP, array( "options" => array( "regexp"=>"/[A-Za-z0-9\s]+/" ) ) ) )
                            {
                                if( filter_var( $bike_model, FILTER_VALIDATE_REGEXP, array( "options" => array( "regexp"=>"/[A-Za-z0-9\s]+/" ) ) ) )
                                {
                                    if( ctype_alpha( $bike_color ) )
                                    {
                                        if( filter_var( $bike_other, FILTER_VALIDATE_REGEXP, array( "options" => array( "regexp"=>"/[A-Za-z0-9\s\.]*/" ) ) ) )
                                        {
                                            if ( is_string( $customer_comments ) )
                                            {
                                                $ticket_number = $this->get_num_tickets() + 1;
                                                $dateTime_in = date("Y-m-d h:i:s.000");

                                                if ( $quote_under_30 )
                                                {
                                                    $quote_under_30 = 1;
                                                }
                                                else
                                                {
                                                    $quote_under_30 = 0;
                                                }

                                                $sql = $wpdb->prepare( "insert into tickets values( %d, 'Open', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', { ts %s }, null, null, null, %d, '%s', null )", $ticket_number, $bike_make, $bike_model, $bike_color, $bike_other, $cwid, $fname, $lname, $phn, $email, $dateTime_in, $quote_under_30, $customer_comments );

                                                $result = sqlsrv_query( $this->conn, $sql );
                                                if ( $result === false )
                                                {
                                                    $this->log_sql_errors( sqlsrv_errors() );
                                                }
                                                else
                                                {
                                                    return $ticket_number;
                                                }
                                            }
                                            else
                                            {
                                                error_log( "class-pedaldb.php:add_ticket - Invalid customer comments: ".$customer_comments );
                                            }
                                            
                                        }
                                        else
                                        {
                                            error_log( "class-pedaldb.php:add_ticket - Invalid bike other: ".$bike_other );
                                        }
                                    }
                                    else
                                    {
                                        error_log( "class-pedaldb.php:add_ticket - Invalid bike color: ".$bike_color );
                                    }
                                } 
                                else
                                {
                                    error_log( "class-pedaldb.php:add_ticket - Invalid bike model: ".$bike_model );
                                }  
                            }
                            else
                            {
                                error_log( "class-pedaldb.php:add_ticket - Invalid bike make: ".$bike_make );
                            }
                        }
                        else
                        {
                            error_log( "class-pedaldb.php:add_ticket - Invalid email ".$email );
                        }
                    }
                    else
                    {
                        error_log( "class-pedaldb.php:add_ticket - Invalid phn: ".$phn );
                    }
                }
                else
                {
                    error_log( "class-pedaldb.php:add_ticket - Invalid lname: ".$lname );
                }
            }
            else
            {
                error_log( "class-pedaldb.php:add_ticket - Invalid fname: ".$lname );
            }
        }
        else
        {
            error_log( "class-pedaldb.php:add_ticket - Inavlid CWID: ".$cwid );
        }

        return false;
    }

    public function add_work_requested( $ticket_number, $work_desc )
    {
        global $wpdb;

        if ( $ticket_number < ( $this->get_num_tickets() + 1 ) )
        {
            if( filter_var( $work_desc, FILTER_VALIDATE_REGEXP, array( "options" => array( "regexp"=>"/[A-Za-z0-9\s]+/" ) ) ) )
            {
                $sql = $wpdb->prepare("insert into work_requested values( %d, %s )", $ticket_number, $work_desc );

                $result = sqlsrv_query( $this->conn, $sql );

                if ( $result === false )
                {
                    $this->log_sql_errors( sqlsrv_errors() );
                }
                else
                {
                    return $ticket_number;
                }
            }
            else
            {
                error_log( "class-pedaldb.php:add_work_requested - Invalid work description: ".$work_desc );
            }
        }
        else
        {
            error_log( "class-pedaldb.php:add_work_requested - Invalid ticket_number: ".$ticket_number );
        }

        return false;
    }

    public function add_work_done( $ticket_number, $work_desc, $parts, $labor )
    {
        global $wpdb;

        if ( $ticket_number < ( $this->get_num_tickets + 1 ) )
        {
            if ( is_float( $parts ) && is_float( $labor ) )
            {
                if( filter_var( $work_desc, FILTER_VALIDATE_REGEXP, array( "options" => array( "regexp"=>"/[A-Za-z0-9\s]+/" ) ) ) )
            {
                $sql = $wpdb->prepare("insert into work_done values( %d, %s, %f, %f )", $ticket_number, $work_desc, $parts, $labor );

                $result = sqlsrv_query( $this->conn, $sql );

                if ( $result === false )
                {
                    $this->log_sql_errors( sqlsrv_errors() );
                }
                else
                {
                    return true;
                }
            }
            else
            {
                error_log( "class-pedaldb.php:add_work_done - Invalid work description: ".$work_desc );
            }
            }
            else
            {
                error_log( "class-pedaldb.php:add_work_done - parts and labor params must be floats: ".$parts );
            }
        }
        else
        {
            error_log( "class-pedaldb.php:add_work_done - Invalid ticket number: ".$labor );
        }

        return false;
    }

    public function update_mechanic_comments( $ticket_number, $comments )
    {
        global $wpdb;

        if ( $ticket_number < ( $this->get_num_tickets + 1 ) )
        {
            $sql = $wpdb->prepare( "update tickets set mechanic_comments = %s where ticket_number = %d", $comments, $ticket_number );

            $result = sqlsrv_query( $this->conn, $sql );

            if ( $result === false )
            {
                $this->log_sql_errors( sqlsrv_errors() );
            }
            else
            {
                return true;
            }
        }
        else
        {
            error_log( "class-pedaldb.php:update_mechanic_comments - Invalid ticket number: ".$ticket_number );
        }

        return false;
    }

    public function update_ticket_status( $ticket_number, $status )
    {
        global $wpdb;

        if ( $ticket_number < ( $this->get_num_tickets + 1 ) )
        {
            
            $sql = $wpdb->prepare( "update tickets set ticket_status = %s where ticket_number = %d", $status, $ticket_number );
            
            $result = sqlsrv_query( $this->conn, $sql );
            
            if ( $result === false )
            {
                $this->log_sql_errors( sqlsrv_errors() );
            }
            else
            {
                if ( $status == 'Quoted')
                {
                    $dateTime_quote = date("Y-m-d h:i:s.000");
                    $sql = $wpdb->prepare( "update tickets set datetime_quote = { ts %s } where ticket_number = %d", $dateTime_quote, $ticket_number );
                }
                else if ( $status == 'Completed')
                {
                    $dateTime_complete = date("Y-m-d h:i:s.000");
                    $sql = $wpdb->prepare( "update tickets set datetime_complete = { ts %s } where ticket_number = %d", $dateTime_complete, $ticket_number );
                }
                else if ( $status == 'Paid')
                {
                    $dateTime_out = date("Y-m-d h:i:s.000");
                    $sql = $wpdb->prepare( "update tickets set datetime_out = { ts %s } where ticket_number = %d", $dateTime_out, $ticket_number );
                }
                $result = sqlsrv_query( $this->conn, $sql );

                if ( $result === false )
                {
                    $this->log_sql_errors( sqlsrv_errors() );
                }
                else
                {
                    return true;
                }
            }
        }
        else
        {
            error_log( "class-pedaldb.php:update_ticket_status - Invalid ticket number: ".$ticket_number );
        }

        return false;
    }

    public function remove_work_done( $ticket_number, $work_description )
    {
        global $wpdb;

        if ( $ticket_number < ( $this->get_num_tickets + 1 ) )
        {
            $sql = $wpdb->prepare( "delete from work_done where ticket_number = %d and work_description = %s", $ticket_number, $work_description );

            $result = sqlsrv_query( $this->conn, $sql );

            if ( $result === false )
            {
                $this->log_sql_errors( sqlsrv_errors() );
            }
            else
            {
                return true;
            }
        }
        else
        {
            error_log( "class-pedaldb.php:remove_work_done - Invalid ticket number: ".$ticket_number );
        }

        return false;
    }

    private $conn = null;
 }