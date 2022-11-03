<?php /*Template Name: customerPage*/ ?>
<?php 
require get_stylesheet_directory() . '/classes/class-pedaldb.php';
$db = new PedalDB('pedal_db', 'Pedal', 'SA', 'Passw0rd');

$current_user = wp_get_current_user();
$username = $current_user->user_login; //gets the username
error_log("USERNAME IS!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! ".$username);
//parse out the ticket number from this
//Format of username: pedal-customer-#
$ticket_number = substr($username, 15);
error_log($ticket_num);
$ticket = $db->get_ticket($ticket_number); //get the ticket object
//have ticket object, get the status and info


$status = $ticket->status; //fetches status
error_log($status);
$work_done_array = $db->get_work_done($ticket_number);
$description;
$part_costs;

foreach ($work_done_array as $i){ //build strings of each thing
    $description .= $i->word_description;
    $part_costs .= $i->parts;
}

$mechanic_comments = $ticket->mechanic_comments;


$html = "
<body> 
<div class=\"body-container\">
        <img src=\"https://urec.sa.ua.edu/wp-content/uploads/sites/12/2016/03/bike-shop-2.jpg\" >
    <div class=\"info\">
        <div class=\"ticket-number\">
            <p>Ticket#: ". $ticket_number . "</p>
        </div>
        <div class=\"status\">
            <p>Status: ".$status."</p>
        </div>
        <div class=\"info-detail\">
            <p>Information: ".$description."</p>
            <br>
            <p> ".$part_costs."</p>
            <br>
            <p> ".$mechanic_comments."</p>
        </div>
    </div>
</div>
</body>";


	
wp_enqueue_script( 'my_script', 'path/to/my/script', array( 'wp-api' ) );
the_content();
if( !post_password_required( $post )):
    get_header("1");
    echo $html;
endif;



get_footer();
