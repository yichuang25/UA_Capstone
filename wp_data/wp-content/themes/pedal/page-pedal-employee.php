<?php /*Template Name: employeePage*/ ?>
<?php 

$baseurl = get_site_url();

$html = "<body>
<div class=\"body-container\">
    <img src=\"https://urec.sa.ua.edu/wp-content/uploads/sites/12/2016/03/bike-shop-2.jpg\" >

    <div class=\"buttons\">
        <div class=\"first-row\">
            <button class=\"shop-view\">
                <a href=\"".$baseurl."/pedal-shop-view/\">Shop View</a>
            </button>
            <button class=\"ticket-search\">
                <a href=\"".$baseurl."/pedal-ticket-search/\">Ticket Search</a>
            </button>
        </div>
        <div class=\"second-row\">
            <button class=\"check-out\">
                <a href=\"".$baseurl."/pedal-checkout/\">Check Out</a>
            </button>
            <button class=\"new-ticket\">
                <a href=\"".$baseurl."/pedal-new-ticket/\">New Ticket</a>
            </button>
        </div>
    </div>

</div>
</body>

";
the_content();
if( !post_password_required( $post )):
    get_header("1");
    echo $html;
endif;



get_footer();
