<?php /*Template Name: new ticket*/ ?>
<?php get_header("1"); ?>
<!--main content-->

<script>
    async function confirmationDisplay( pwd ){
        var ticket_number = await get_ticketCount(); //gets the ticket count
        ticket_number = parseInt(ticket_number);

        confirm("Your ticket has been processed. Thank you for using the URec Bike Shop. Your ticket# is " + ticket_number + " Your password is " + pwd );
    }

    function errorDisplay(){
        confirm("There was an error submitting your ticket.");
    }

    async function get_ticketCount() {
        var root_url = wp.api.utils.getRootUrl();
        var pedal_API_url = "wp-json/pedal/v1/";
        var url = root_url + pedal_API_url + "tickets/count";
        //console.log(url);

        const resp = await fetch(url);
        let ticketCount = await resp.text();
         console.log(ticketCount);
         return ticketCount;
    }

    async function add_User(formInfo){ //used to accept (formData) as arg
        var ticketNum = await get_ticketCount(); //gets the ticket count
        ticketNum = parseInt(ticketNum);


        var newUsername = 'pedal-customer-' + String(ticketNum);
        var newPass = String(Math.floor(Math.random() * (1000000 - 100000)));
        var emailAddress = formInfo['email'];

        var newUser = new wp.api.models.User( { username: newUsername, email: emailAddress, 
            password: newPass} );
            newUser.save(); //saves the user as a valid one to the wp database
            console.log("New user successfully made", newUser);
    }

    async function add_customer_page()
    {
        var ticket_number = await get_ticketCount();
        ticket_number = parseInt( ticket_number );


        var new_page_name = 'pedal-customer-' + String( ticket_number );
        var password = String(Math.floor(Math.random() * (1000000 - 100000)));
        
        var new_page = new wp.api.models.Page( { slug: new_page_name, title: new_page_name, status: 'publish', password: password, template: 'page-pedal-customer.php' } );
        new_page.save();
        console.log("New customer page successfully made", new_page);
        return password;
    }

    function extract_field_name(id) {
        return id.slice(0, -6);
    }

    function collect_customer_info() {
        var x = document.getElementsByClassName("info_field");  
        var info = {};

        for (var i = 0; i < x.length; i++) {
            var field = x[i];
            var field_name = extract_field_name(field.id);
            var field_value = field.value;
            info[field_name] = field_value;
        }
        return info;
    }

    function workRequested(info) {
        if (Object.keys(info["work_requested"]).length === 0)
            return false;
        // console.log("is not empty, returning true");
        return true
    }

    function customer_comments_provided(info) {
        if (info['customer_comments'] === "") 
            return false
        else 
            return true
    }

    function provided_a_request(info) {
        return (workRequested(info) || customer_comments_provided(info))
    }

    function validate_info(info) {
        for (const key in info) {
            if (key !== "work_requested" && key !== "customer_comments" && key !== "bike_other")
                if (info[key] === "") 
                    return false
        }

        console.log("all required forms valid\n");
        if (!provided_a_request(info)) {
            console.log("Returning false");
            return false
        }

        return true
    }

    function agreed_to_ToS() {
        return document.getElementById("agreement_checkbox").checked;
    }



    async function post_ticket(ticket_info) {
        var root_url = wp.api.utils.getRootUrl();
        var pedal_API_url = "wp-json/pedal/v1/";
        var url = root_url + pedal_API_url + "add-ticket";


        const resp = await fetch(root_url+"/wp-json/pedal/v1/add-ticket", {
            method: "POST",
            headers: {
                "Content-Type": 'application/json'
            },
            body: JSON.stringify(ticket_info)
        })
        .then(resp=>resp.json());

        console.log("?",resp);

        return resp;
    }

    async function process_page() {
        console.log("Processing page...");

        // if (!agreed_to_ToS()) {
        //     return false;
        // }

        var customer_info = collect_customer_info();

        customer_info['customer_comments'] = document.getElementById("customer_comments-textarea").value;
        customer_info['quote_under_30'] =  document.getElementById("quote_under_30").checked;
        customer_info['bike_other'] = "none";

        // work requested
        var table_rows = document.getElementsByTagName("tr");
        var work_requested = [];

        for (var i = 0; i < table_rows.length; i++) {

            var children = table_rows[i].children;
            var field_name = children[0].innerHTML;
            var checkboxes = children[1].children;


            var req = "";

            var options = ["Front", "Rear"];

            for (var box = 0; box < checkboxes.length; box++) {
                // console.log(checkboxes[box]);
                if (checkboxes[box].checked === true) {
                    console.log(field_name);
                    var service_name = "";

                    if (checkboxes.length === 2) {
                        service_name+=options[box];
                        service_name+=" "
                    }

                    service_name += field_name;

                    work_requested.push(service_name);

                }
            }



        }

        customer_info["work_requested"] = work_requested;
        console.log(customer_info);

        if (validate_info(customer_info)) {
            var x = await post_ticket(customer_info);
            if ( x )
            {
                add_User(customer_info);
                var pwd = await add_customer_page();
                confirmationDisplay( pwd );
                return true;
            }
        }

        errorDisplay();
        return false;
    }


</script>

<body>
<!--navbar-->

<!--end navbar-->
<!--main content-->
<div class="body-container submit-new-ticket-page">
    <img class="header-image" src="https://urec.sa.ua.edu/wp-content/uploads/sites/12/2016/03/bike-shop-2.jpg" >

    <h1 class='new-ticket-title'> Submit New Ticket </h1>

    <div class="new-ticket-form-customer-info">
        <h2>Customer Info</h2>
        <div class="new-ticket-form-field">
            <label><b>First Name</b></label>
            <input class="info_field" id="fname_field" type="text"></input>
        </div>
        <div class="new-ticket-form-field">
            <label><b>Last Name</b></label>
            <input class="info_field" id="lname_field" type="text"></input>
        </div>
        <div class="new-ticket-form-field">
            <label><b>CWID</b></label>
            <input class="info_field" id="cwid_field" type="text"></input>
        </div>
        <div class="new-ticket-form-field">
            <label><b>Phone Number</b></label>
            <input class="info_field" type="tel" id="phn_field" name="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"></input>
            <small class="format">Format: 1234567890</small>
        </div>
        <br>
        

        <div class="new-ticket-form-field">
            <label><b>Email Address</b></label>
            <input class="info_field" id="email_field" type="text"></input>
        </div>
    </div>

    <div class="new-ticket-form-bike-info">
        <h2>Bike Info</h2>
        <div class="new-ticket-form-field">
            <label><b>Make</b></label>
            <input class="info_field" id="bike_make_field" type="text"></input>
        </div>
        <div class="new-ticket-form-field">
            <label><b>Model</b></label>
            <input class="info_field" id="bike_model_field" type="text"></input>
        </div>
        <div class="new-ticket-form-field">
            <label><b>Color</b></label>
            <input class="info_field" id="bike_color_field" type="text"></input>
        </div>
    </div>

    <div class="quote-under-thirty-checkbox">
        <input type='checkbox' id="quote_under_30">Quote under thirty?</input>
    </div>

    <div class="new-ticket-form-service-selection">
        <h2>Service Selection</h2>
        <table class="select-services-table">
            <tr>
                <th>Service</th>
                <th>Selection<th>
            </tr>
            <tr>
                <td>Brake Adjustment</td>
                <td>         
                    <input type='checkbox'>Front</input>
                    <input type='checkbox'>Rear</input>
                </td>
            </tr>
            <tr>
                <td>Shift Adjustment</td>
                <td>         
                    <input type='checkbox'>Front</input>
                    <input type='checkbox'>Rear</input>
                </td>
            </tr>
            <tr>
                <td>Wheel Tune</td>
                <td>         
                    <input type='checkbox'>Front</input>
                    <input type='checkbox'>Rear</input>
                </td>
            </tr>
            <tr>
                <td>Safety Check</td>
                <td>         
                    <input type='checkbox'></input>
                </td>
            </tr>
            <tr>
                <td>Bike Build</td>
                <td>         
                    <input type='checkbox'></input>
                </td>
            </tr>
            <tr>
                <td>Hub Adjustment</td>
                <td>         
                    <input type='checkbox'>Front</input>
                    <input type='checkbox'>Rear</input>
                </td>
            </tr>

            <tr>
                <td>Flat Fix</td>
                <td>         
                    <input type='checkbox'>Front</input>
                    <input type='checkbox'>Rear</input>
                </td>
            </tr>

            <tr>
                <td>Headset Adjustment</td>
                <td>         
                    <input type='checkbox'></input>
                </td>
            </tr>
            <tr>
                <td>BB Adjustment</td>
                <td>         
                    <input type='checkbox'></input>
                </td>
            </tr>
        </table>
    </div>

    <div class="customer_comments">
        <h2>Problem Description</h2>

        <textarea id="customer_comments-textarea" type="text"></textarea>
    </div>      

    <div class="agreement">
        <input id="agreement_checkbox" type="checkbox"></input>
        I have read and agree to the <a href="https://cdn.discordapp.com/attachments/937939547814232116/969726993652383744/UAOR_Bike_Shop_Repair_Agreement.pdf" target="_blank"> repair agreement</a>.
    </div>

    <div>
        <button onClick="process_page()" class="submit-btn">Submit</button>
    </div>


</div>

<?php wp_footer(); ?>
</body>
<style>
    .service {
        /* border-bottom: solid; */
    }

    .submit-new-ticket-page {
        width: 80vw;
        text-align: center;
        margin: auto;

    }

    .new-ticket-form-field {
        max-width: 300px;
        margin:auto;
    }

    .submit-btn {
        max-width: 300px;
    }

    .select-services-table {
        margin: auto;
        text-align: center;
        /* background: #f00; */
    }

    /* .submit-new-ticket-page > h1 {
        font-size: 62px;
    }

    .submit-new-ticket-page > div > h2 {
        font-size: 40px;
    } */


    .new-ticket-title {
        text-align: center;
    }

    

    .header-image {
        width: 100vw;
    }

    .testclass1 {
        display: inline;

        
    }

    .toptext{
        font-size: 30px;
    }
    body {font-family: Arial, Helvetica, sans-serif;}
    form {border: 3px solid #c9c9c9;}

    img {
        max-width: 100%
    }
    .main_block {
        margin: auto;
        margin-right: 10%;
        margin-left: 10%;
        position: relative;
        top: 80px;
    }

    input[type=text], input[type=password], input[type=tel] {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    box-sizing: border-box;
    }

    .has-text-align-center {
        text-align: center;
    }

    button {
    background-color: #345434;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    cursor: pointer;
    width: 100%;
    }

    button:hover {
    opacity: 0.8;
    }

    .cancelbtn {
    width: auto;
    padding: 10px 18px;
    background-color: #f44336;
    }

    .container {
    padding: 16px;
    }
    span.psw {
    float: right;
    padding-top: 16px;
    }
    body {
        background-color: #fffff5
    }
    /* Change styles for span and cancel button on extra small screens */
    @media screen and (max-width: 300px) {
    span.psw {
        display: block;
        float: none;
    }
    .cancelbtn {
        width: 100%;
    }
    }
</style>


<?php
get_footer();