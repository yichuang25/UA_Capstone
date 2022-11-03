<?php /*Template Name: customerPage*/ ?>
<?php 
    get_header("1"); 
    wp_enqueue_script( 'wp-api' );
?>

<body> 
<!--end navbar-->
<!--main content-->
<div class="body-container">
        <h1>Database Test</h1>
    <div class="info">
        <?php 
            var_dump ( wp_get_current_user()->user_login );
        ?>
    </div>

    <script>
        function clickMe()
        {
            console.log("Hello World");
        }
    </script>
    
    <div class="buttons">
            <button class="accept" onclick="clickMe()">
                <a href=#>Accept</a>
            </button>
            <button class="deny">
                <a href=#>Deny</a>
            </button>
    </div>
   

</div>
</body>
<?php
get_footer();