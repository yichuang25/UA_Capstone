<?php
/**
 * Custom WP REST API  Controller Class
 *
 * @package Pedal
 * @since Pedal 1.0
 */

class Pedal_REST_Controller
{
    public function __construct()
    {
        $this->namespace = '/pedal/v1';

        require get_stylesheet_directory() . '/classes/class-pedaldb.php';
        $this->db = new PedalDB('pedal_db', 'Pedal', 'SA', 'Passw0rd');
    }

    public function register_routes()
    {
        register_rest_route( $this->namespace, '/ticket-summaries', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array( $this, 'get_ticket_summaries' ),
            'permission_callback' => '__return_true',/*function () {
                return ( ( wp_get_current_user()->user_login == "bike-shop" ) || ( current_user_can( 'read_private_pages' ) ) );
            },*/
            'args' => $this->get_search_args(),
        ), );

        register_rest_route( $this->namespace, '/tickets/count', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array( $this, 'get_num_tickets' ),
            'permission_callback' => '__return_true',
        ), );

        register_rest_route( $this->namespace, '/ticket', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array( $this, 'get_ticket' ),
            'permission_callbask' => $this->check_customer_facing_permissions,
            'args' => $this->get_ticket_args(),
        ), );

        register_rest_route( $this->namespace, '/work-requested', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array( $this, 'get_work_requested' ),
            'permission_callbask' => $this->check_customer_facing_permissions,
            'args' => $this->get_ticket_args(),
        ), );

        register_rest_route( $this->namespace, '/work-done', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array( $this, 'get_work_done' ),
            'permission_callbask' => $this->check_customer_facing_permissions,
            'args' => $this->get_ticket_args(),
        ), );

        register_rest_route( $this->namespace, '/add-ticket', array(
            'methods' => 'POST',
            'callback' => array( $this, 'add_ticket'),
            'args' => array(),
            'permission_callbask' => '__return', /*function () {
                return ( ( wp_get_current_user()->user_login == "bike-shop" ) || ( current_user_can( 'read_private_pages' ) ) );
            },*/
        ) );

        register_rest_route( $this->namespace, '/add-work-done', array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => array( $this, 'add_work_done' ),
            'permission_callbask' => function () {
                return ( ( wp_get_current_user()->user_login == "bike-shop" ) || ( current_user_can( 'read_private_pages' ) ) );
            },
        ) );

        register_rest_route( $this->namespace, '/update-mechanic-comments', array(
            'methods' => WP_REST_Server::EDITABLE,
            'callback' => array( $this, 'update_mechanic_comments' ),
            'permission_callbask' => function () {
                return ( ( wp_get_current_user()->user_login == "bike-shop" ) || ( current_user_can( 'read_private_pages' ) ) );
            },
            'args' => $this->get_update_mechanic_comments_args(),
        ) );

        register_rest_route( $this->namespace, '/update-ticket-status', array(
            'methods' => WP_REST_Server::EDITABLE,
            'callback' => array( $this, 'update_ticket_status' ),
            'permission_callbask' => $this->check_customer_facing_permissions,
            'args' => $this->get_update_ticket_status_args(),
        ) );

        register_rest_route( $this->namespace, '/remove-work-done', array(
            'methods' => WP_REST_Server::DELETABLE,
            'callback' => array( $this, 'remove_work_done' ),
            'permission_callbask' => function () {
                return ( ( wp_get_current_user()->user_login == "bike-shop" ) || ( current_user_can( 'read_private_pages' ) ) );
            },
            'args' => $this->get_remove_work_done_args(),
        ) );
    }

    public function get_num_tickets( WP_REST_Request $request )
    {
        $num_tickets = $this->db->get_num_tickets();
        
        return rest_ensure_response( $num_tickets );
    }

    public function get_ticket_summaries( WP_REST_Request $request )
    {
        
        $tickets = array();

        $order_by = 'ticket_number';
        if ( isset( $request['orderBy'] ) )
        {
            $order_by = $request['orderBy'];
        }

        if ( isset( $request['fname'] ) )
        {
            $tickets = $this->db->search_by_fname( $request['fname'], $order_by );
        }
        else if ( isset( $request['lname'] ) )
        {
            $tickets = $this->db->search_by_lname( $request['lname'], $order_by );
        }
        else if ( isset( $request['cwid'] ) )
        {
            $tickets = $this->db->search_by_cwid( $request['cwid'], $order_by );
        }
        else if ( isset( $request['status'] ) )
        {
            $tickets = $this->db->search_by_status( $request['status'], $order_by );
        }
        else
        {
            $tickets = $this->db->get_tickets_summary( $order_by );
        }

        return rest_ensure_response( $tickets );
    }

    public function get_ticket( WP_REST_Request $request )
    {
        $ticket = null;

        if ( isset( $request['ticket-number'] ) )
        {
            $ticket = $this->db->get_ticket( $request['ticket-number'] );
        }

        return rest_ensure_response( $ticket );
    }

    public function get_work_requested( WP_REST_Request $request )
    {
        $work = array();

        if ( isset( $request['ticket-number'] ) )
        {
            $work = $this->db->get_work_requested( $request['ticket-number'] );
        }

        return rest_ensure_response( $work );
    }

    public function get_work_done( WP_REST_Request $request )
    {
        $work = array();

        if ( isset( $request['ticket-number'] ) )
        {
            $work = $this->db->get_work_done( $request['ticket-number'] );
        }

        return rest_ensure_response( $work );
    }

    public function add_ticket( WP_REST_Request $request )
    {
        $result = false;
        $ticket_number = $this->db->add_ticket( $request[('cwid')], $request[('fname')], $request[('lname')],
                                         $request[('phn')], $request[('email')], $request[('bike_make')],
                                         $request[('bike_model')], $request[('bike_color')], $request[('bike_other')],
                                         $request[('quote_under_30')], $request[('customer_comments')] );

        if ( $ticket_number )
        {
            foreach( $request[('work_requested')] as $work_desc )
            {
                $result = $this->db->add_work_requested( $ticket_number, $work_desc );
            }
        }

        if ( $result )
        {
            return rest_ensure_response( $ticket_number );
        }
        else
        {
            return rest_ensure_response( false );
        }
    }

    public function add_work_done( WP_REST_Request $request )
    {
        // TODO check
        $result = $this->db->add_work_done( $request['ticket_number'], $request['work_desc'], $request['parts'], $request['labor'] );

        return rest_ensure_response( $result );
    }

    public function update_mechanic_comments( WP_REST_Request $request )
    {
        // TODO check
        $result = false;
        if ( isset( $request['ticket-number'] ) )
        {
            $result = $this->db->update_mechanic_comments( $request['ticket-number'], $request['comments'] );
        }

        return rest_ensure_response( $result );
    }

    public function update_ticket_status( WP_REST_Request $request )
    {
        // TODO check
        if ( isset( $request['ticket-number'] ) )
        {
            $work = $this->db->get_work_done( $request['ticket-number'] );
            $result = $this->db->update_ticket_status( $request['ticket-number'], $request['ticket_status'] );
        }

        return rest_ensure_response( false );
    }

    public function remove_work_done( WP_REST_Request $request )
    {
        $result = false;
        if ( isset( $request['ticket-number'] ) )
        {
            if ( isset( $request['work-description'] ) )
            {
                $result = $this->db->remove_work_done( $request['ticket-number'], $request['work-description'] );
            }
        }

        return rest_ensure_response( $result );
    }

    public function get_search_args()
    {
        $args = array();

        $args['orderBy'] = array(
            'description' => esc_html__( 'The fname parameter is used to filter the ticket summaries', 'my-text-domain' ),
            'type'        => 'string',
            'enum' => array( 'ticket_number', 'fname', 'lname', 'ticket_status' ),
        );

        $args['fname'] = array(
            'description' => esc_html__( 'The fname parameter is used to filter the ticket summaries', 'my-text-domain' ),
            'type'        => 'string',
            'validate_callback' => array($this, 'name_arg_validate_callback' ),
        );

        $args['lname'] = array(
            'description' => esc_html__( 'The fname parameter is used to filter the ticket summaries', 'my-text-domain' ),
            'type'        => 'string',
            'validate_callback' => array($this, 'name_arg_validate_callback' ),
        );

        $args['cwid'] = array(
            'description' => esc_html__( 'The fname parameter is used to filter the ticket summaries', 'my-text-domain' ),
            'type'        => 'string',
            'validate_callback' => array($this, 'cwid_arg_validate_callback' ),
        );

        $args['status'] = array(
            'description' => esc_html__( 'The fname parameter is used to filter the ticket summaries', 'my-text-domain' ),
            'type'        => 'string',
            'enum' => array( 'Open', 'Attn Mngr', 'Quoted', 'Approved', 'Parts On Order', 'Parts Out of Stock', 'Parts Arrived', 'Complete', 'Paid' ),
        );

        return $args;
    }

    public function get_ticket_args()
    {
        $args = array();

        $args['ticket-number'] = array(
            'description' => esc_html__( 'The ticket-number parameter is used to filter the ticket summaries', 'my-text-domain' ),
            'type'        => 'int',
            'validate_callback' => array($this, 'ticket_info_arg_validate_callback' ),
        );

        return $args;
    }

    public function get_update_mechanic_comments_args()
    {
        $args = array();

        $args['ticket-number'] = array(
            'description' => esc_html__( 'The ticket-number parameter is used to filter the work tickets', 'my-text-domain' ),
            'type'        => 'int',
            'validate_callback' => array( $this, 'ticket_number_arg_validate_callback' ),
        );

        return $args;
    }

    public function get_update_ticket_status_args()
    {
        $args = array();

        $args['ticket-number'] = array(
            'description' => esc_html__( 'The ticket-number parameter is used to filter the work tickets', 'my-text-domain' ),
            'type'        => 'int',
            'validate_callback' => array( $this, 'ticket_number_arg_validate_callback' ),
        );

        return $args;
    }

    public function get_remove_work_done_args()
    {
        $args = array();

        $args['ticket-number'] = array(
            'description' => esc_html__( 'The ticket-number parameter is used to filter the work tickets', 'my-text-domain' ),
            'type'        => 'int',
            'validate_callback' => array( $this, 'ticket_number_arg_validate_callback' ),
        );

        $args['work-description'] = array(
            'description' => esc_html__( 'The work-description parameter is used to find the exact work item', 'my-text-domain' ),
            'type'        => 'string',
            'validate_callback' => array( $this, 'work_description_arg_validate_callback' ),
        );

        return $args;
    }

    public function name_arg_validate_callback( $value, $request, $param )
    {
        if ( ( ! is_string( $value ) ) || ( ! ctype_alpha( $value ) ) ) 
        {
            return new WP_Error( 'rest_invalid_param', esc_html__( 'The filter argument must be a one word A-Za-z string.', 'my-text-domain' ), array( 'status' => 400 ) );
        }
    }

    public function cwid_arg_validate_callback( $value, $request, $param )
    {
        if ( ( ! is_string( $value ) ) || ( ! ctype_alnum( $value ) ) )
        {
            return new WP_Error( 'rest_invalid_param', esc_html__( 'The filter argument must be an alpha-numeric string.', 'my-text-domain' ), array( 'status' => 400 ) );
        }
    }

    public function ticket_number_arg_validate_callback( $value, $request, $param )
    {
        $num_tickets = $this->db->get_num_tickets();

        if ( ( ! is_string( $value ) ) || ( ! ctype_digit( $value ) ) ||  ( $value > $num_tickets ) )
        {
            $num_tickets += 1;
            return new WP_Error( 'rest_invalid_param', esc_html__( 'The ticket-number argument must be an integer less than '.$num_tickets.'.', 'my-text-domain' ), array( 'status' => 400 ) );
        }
    }

    public function work_description_arg_validate_callback( $value, $request, $param )
    {
        if ( ! is_string( $value ) )
        {
            return new WP_Error( 'rest_invalid_param', esc_html__( 'The work-description argument must be a string.', 'my-text-domain' ), array( 'status' => 400 ) );
        }
    }

    public function check_customer_facing_permissions( WP_REST_Request $request )
    {
        if ( ( wp_get_current_user()->user_login == "bike-shop" ) || ( current_user_can( 'read_private_pages' ) ) )
        {
            return true;
        }
        else if ( isset( $request['ticket-number'] ) )
        {
            $valid_username = "bike-shop-customer".$request['ticket-number'];
            return ( wp_get_current_user()->user_login == $valid_username );
        }
        
        return false;
    }
}