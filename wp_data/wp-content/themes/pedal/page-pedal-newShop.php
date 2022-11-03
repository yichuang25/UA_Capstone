<!--@author Thomas Davidson -->
<!DOCTYPE html>
<?php /*Template Name: newShop*/ ?>
<?php get_header("1"); ?>

<script>

    let pagedata = {};

    var service_map = {
            "flat_fix" : 14,
            "safety_check" : 25,
            "bike_build" : 45,
            "brake_tune" : 8,
            "brake_bleed" : 15,
            "derailleur_adjust" : 8,
            "headset_adjust" : 10,
            "BB_adjust" : 15,
            "front_hub_adjust" : 11,
            "rear_hub_adjust" : 15,
            "wheel_true" : 15
        }

    var desc_map = {
        "flat_fix" : "Flat Fix",
        "safety_check" : "Safety Check",
        "bike_build" : "Bike Build",
        "brake_tune" : "Brake Tune",
        "derailleur_adjust" : "Derailleur Adjustment",
        "headset_adjust" : "Headset Adjustment",
        "BB_adjust" : "Bottom Bracket Adjustment",
        "front_hub_adjust" : "Front Hub Adjustment",
        "rear_hub_adjust" : "Rear Hub Adjustment",
        "wheel_true" : "Wheel True"
    }

    var desc_to_price_map = {}

    for (const [key,val] of Object.entries(desc_map)) {
        desc_to_price_map[val] = service_map[key];
    }

    desc_to_price_map['Front Wheel Tune'] = service_map['wheel_true'];
    desc_to_price_map['Rear Wheel Tune'] = service_map['wheel_true'];
    desc_to_price_map['Front Shift Adjustment'] = 10;
    desc_to_price_map['Rear Shift Adjustment'] = 10;

        

    window.addEventListener('load', function() {
        populate_ticket_dropdown();
    });

    function add_work_done(workdone) {
        console.log(workdone);

        var desc = desc_map[workdone];
        var parts = "-";
        var labor = service_map[workdone];

        add_table_data({description:desc, parts:parts, labor:labor});

        // switch (workdone) {

        //     case ("flat_fix"):
        //         break;
        //     case ("safety_check"):
        //         break;                
        //     case ("bike_build"):
        //         break;
        //     case ("brake_tune"):
        //         break;  
        //     case ("wheel_true"):
        //         break;                
        //     case ("derailleur_adjust"):
        //         break;
        //     case ("front_hub_adjust"):
        //         break;  
        //     case ("rear_hub_adjust"):
        //         break;
        //     case ("headset_adjust"):
        //         break;                
        //     case ("brake_bleed"):
        //         break;
        //     case ("BB_adjust"):
        //         break;  

        return;
        // }

    }

    function add_custom_table_data() {
        add_table_data({
            description: document.getElementById("custom_desc_field").value,
            labor: document.getElementById("custom_labor_field").value,
            parts: document.getElementById("custom_parts_field").value
        });
    }

    function add_table_data({description, parts, labor}) {
        if (!description || !parts || !labor) 
            return;

        let table = document.getElementById("workdonetable");
        let newrow = table.insertRow(-1);
        newrow.insertCell(0).appendChild(document.createTextNode(description));
        newrow.insertCell(1).appendChild(document.createTextNode(parts));
        newrow.insertCell(2).appendChild(document.createTextNode(labor));
        return;
    }

    async function get_ticket_info(ticket_number) {
        var root_url = wp.api.utils.getRootUrl();
        var pedal_API_url = "wp-json/pedal/v1/";
        var url = root_url + pedal_API_url + "ticket?ticket-number="+ticket_number;

        const resp = await fetch(url, {
            method:"GET",
        })
        .then(resp=>resp.json());

        // console.log(resp);
        return resp;
    }

    async function get_ticket_work_items(ticket_num) {
        var root_url = wp.api.utils.getRootUrl();
        var pedal_API_url = "wp-json/pedal/v1/";
        var url = root_url + pedal_API_url + "work-requested?ticket-number="+ticket_num;

        const resp = await fetch(url, {
            method:"GET",
        })
        .then(resp=>resp.json());

        // console.log(resp);
        pagedata['current_ticket_work_items'] = resp;
        return resp;

    }

    async function get_all_tickets() {
        var tickets = "None";

        var root_url = wp.api.utils.getRootUrl();
        var pedal_API_url = "wp-json/pedal/v1/";
        var url = root_url + pedal_API_url + "ticket-summaries";

        const resp = await fetch(url, {
            method: "GET"
        })
        .then(resp=>resp.json());

        pagedata['ticket_list'] = resp;
        return resp;
    }

    async function populate_ticket_dropdown() {
        var status_of_interest = document.getElementById("status_dropdown").value.toLowerCase();

        const all_tickets = await get_all_tickets();

        var relevant_tickets = [];

        for (const ticket of all_tickets) {
            if (ticket['ticket_status'].toLowerCase() === status_of_interest) {
                relevant_tickets.push(ticket);
            }
        }

        console.log("relevanttix", relevant_tickets);

        var list = document.getElementById("selected_ticket_num_field");


        
        console.log(list);
        for (const ticket of relevant_tickets) {
            var option = document.createElement("option");
            option.value = ticket['ticket_number'];
            option.innerHTML = ticket['ticket_number'];
            list.appendChild(option);
        }

        return;
    }

    async function populate_page() {

        var selected_ticket_num = document.getElementById("selected_ticket_num_field").value;//.value;
        var selected_ticket = await get_ticket_info(selected_ticket_num);


        var work_requested = await get_ticket_work_items(selected_ticket_num);

        console.log(work_requested);
        // console.log(selected_ticket_num);

        console.log(selected_ticket);

        var fields = document.getElementsByClassName("population_target");

        for (const f of fields) {

            console.log(f);

            var f_name = f.id.slice(15);
            var val = selected_ticket[f_name];

            console.log(val);
            console.log(f_name);


            if (f_name !== "work_requested") {

                if (typeof(val) === "object" && val !== null) {
                    f.innerHTML = val.date;
                } 

                else if (f_name === "name"){
                    f.innerHTML = selected_ticket['fname'] + " " + selected_ticket['lname']
                }

                else if (f_name === "phone_number") { 
                    var phone_num = selected_ticket['phone_number'];
                    f.innerHTML = "(" + phone_num.slice(0,3) + ")-" + phone_num.slice(3,6)+"-"+phone_num.slice(6);
                } 
                else {
                    f.innerHTML = val;
                }

            } else {
                if (f_name === "work_requested") {
    
                    var wrstring = "";
                    for (const c of work_requested) {
                        console.log(c['work_description']);
                        wrstring += " - "
                        wrstring += c['work_description'];
                        wrstring += "<br>";
                    }


                    f.innerHTML = wrstring;
                
                }
            }


        }

        // name_field.innerHTML = selected_ticket['fname'] + " " + selected_ticket['lname'];
        // email_field.innerHTML = selected_ticket['email'];







    }

    async function save_to_db() {
        console.log("Saving..");
    }

    function generate_total_cost() {

        console.log(service_map);

        console.log(desc_to_price_map);

        var total_cost = 0;
        var parts_cost = 0;
        var labor_cost = 0;
        for (work_item of pagedata['current_ticket_work_items']) {
            console.log(work_item.work_description);
            console.log("Type is..");
            console.log(typeof(desc_to_price_map[work_item.work_description]));

            total_cost+=desc_to_price_map[work_item.work_description];
            labor_cost+=desc_to_price_map[work_item.work_description];

            if (total_cost === NaN) {
                console.log("Error!", work_item.work_description, "is NaN");
            }
        }

        var total_cost_field = document.getElementById("grand_total");
        var parts_cost_field = document.getElementById("parts_subtotal");
        var labor_cost_field = document.getElementById("comrades_cut");

        console.log(total_cost,parts_cost,labor_cost);
        total_cost_field.innerHTML = "$"+(total_cost.toString());
        parts_cost_field.innerHTML = "$"+(parts_cost.toString());
        labor_cost_field.innerHTML = "$"+(labor_cost.toString());
    }

</script>
<div class="holder">

    <div class="generalInfo"> <!-- A -->
        <div class="actionBar"> <!-- all interactions here-->
            <div class="main_search_form">
                <label for="status">Status: </label>
                <select name="status" value="open" id="status_dropdown">
                    <option value="open">Open</option>
                    <option value="quoted">Quoted</option>
                    <option value="approved">Approved</option>
                    <option value="complete">Complete</option>
                    <option value="paid">Paid</option>
                    <option value="manager">Attn. Manager</option>
                    <option value="oos">Parts OOS</option>
                    <option value="ordered">Parts Ordered</option>
                    <option value="arrived">Parts Arrv.</option>
                </select>
                <label for="ticket">Tickets matching Status: </label>
                <select id="selected_ticket_num_field" list="ticket-dropdown"  name="ticket"></select>
                <!-- <datalist id="ticket-dropdown">
                </datalist> -->

            </div>
            <div class="main_controls">
                <button id="submit_query_button" onclick="populate_page()" class="secondaryButton" type="submit">Submit Query</button>
                <button id="save_ticket_button" onclick="save_to_db()" class="secondaryButton" type="submit">Save Ticket</button>
            </div>

        </div>
        

        <!-- may want a submit button here? Or just tie together with a general
        update button for the entire page and it's changes-->
        <div class="generalGrid"> <!-- another grid-->
            <div class="nameSection">
                <h3>Name:</h3>
                <div class="population_target" id="fetched_ticket_name"></div>
            </div>

            <div class="emailSection">
                <h3>Email:</h3>
                <div class="population_target" id="fetched_ticket_email"></div>
            </div>

            <div class="numberSection">
                <h3>Phone Number:</h3>
                <div class="population_target" id="fetched_ticket_phone_number"></div>
            </div>

            <div class="dateinSection">
                <h3>Date In:</h3>
                <div class="population_target" id="fetched_ticket_datetime_in"></div>
            </div>

            <div class="datequoteSection">
                <h3>Date Quoted:</h3>
                <div class="population_target" id="fetched_ticket_datetime_quote"></div>
            </div>

            <div class="datecompleteSection">
                <h3>Date Completed:</h3>
                <div class="population_target" id="fetched_ticket_datetime_out"></div>
            </div>
        </div>
    </div>

    <div class="totalInfo"> <!-- B -->

        <div class="price_line">
            <h3 class="grandTotal">Total: </h3>
            <div id="grand_total"></div>

        </div>

        <div class="price_line">
            <h4>Parts: </h4>
            <div id="parts_subtotal"></div>
        </div>

        <div class="price_line">
            <h4>Labor: </h4>
            <div id="comrades_cut"></div>
        </div>



        <!-- Need button for generating total-->
        <button onclick="generate_total_cost()" class="totalButton">Generate Total</button>
    </div>

    <div class="bikeInfo"> <!-- C -->
        <!-- Not sure how to do the predone checkbox-->
        <h3>Quote under $30?:</h3> <!-- need to have this say yes or no dependent on data-->
        <div class="population_target subTotal" id="fetched_ticket_quote_under_30"></div>
        <h3>Bike Make:</h3> <!-- fetch it-->
        <div class="population_target subTotal" id="fetched_ticket_bike_make"></div>
        <h3>Bike Model:</h3>
        <div class="population_target subTotal" id="fetched_ticket_bike_model"></div>
        <h3>Bike Color:</h3>
        <div class="population_target subTotal" id="fetched_ticket_bike_color"></div>
        <h3>Bike Other</h3>
        <div class="population_target subTotal" id="fetched_ticket_bike_other"></div>
        <h3>Customer Comments</h3>
        <div class="population_target subTotal" id="fetched_ticket_customer_comments"></div>

    </div>

    <div class="shopLog"> <!-- D -->
        <h3 class="shoplogHeader">Shop Log</h3>
        <textarea class="shopText" id="check" name="log" value="Input"></textarea>
    </div>

    <div class="workDone"> <!-- H -->
        <table id="workdonetable" class="scrollTable">
            <caption>Work Done</caption> <!-- Adds a header, may want to just get a grid element header instead-->
            <thead class="fixedHeader">
	            <tr class="tableHeader"> <!-- the first row of the table used for header -->
		            <th class="firstCol">Desc.</th>
		            <th class="secondCol">Parts</th>
		            <th class="thirdCol">Labor</th>
            </thead>
            <tbody class="tableEntries">

            </tbody>
        </table>
    </div>



    <div class="workRequested"> <!-- F not sure what I should put here to hold the results-->
        <h3 class="workRequestedHeader">Work Requested</h3>
        <div class="population_target bump-left" id="fetched_ticket_work_requested">
            
        </div>
    </div>

    <div class="genericButtons"> <!-- G -->
        <div class="primarySection">
            <button onClick="add_work_done('flat_fix')" class="mainButton">Flat Fix</button>
            <button onClick="add_work_done('safety_check')" class="mainButton">Safety Check</button>
            <button onClick="add_work_done('bike_build')" class="mainButton">Bike Build</button>
            <button onClick="add_work_done('brake_tune')" class="mainButton">Brake Tune</button>
            <button onClick="add_work_done('wheel_true')" class="mainButton">Wheel True</button>
            <button onClick="add_work_done('derailleur_adjust')" class="mainButton">Derailleur Adjust</button>
            <button onClick="add_work_done('front_hub_adjust')" class="mainButton">Front Hub Adjust</button>
            <button onClick="add_work_done('rear_hub_adjust')" class="mainButton">Rear Hub Adjust</button>
            <button onClick="add_work_done('headset_adjust')" class="mainButton">Headset Adjust</button>
            <button onClick="add_work_done('brake_bleed')" class="mainButton">Brake Bleed</button>
            <button onClick="add_work_done('BB_adjust')" class="mainButton">BB Adjust</button>
        </div>

        <div class="pushdownslightly cableSection">
            <div class="buttonandfield">  
                <button class="secondButton">Cable</button>
                <input type="text"></input>
            </div>  
            <div class="buttonandfield">
                <button class="secondButton">Housing</button>
                <input type="text"></input>
            </div>
        </div>
    </div>

    <div class="custom_work_done_area">
        <table class="workdonetable">
            <tr>
                <td>Description:</td>
                <td>
                <input id="custom_desc_field" type="text"></input>
                </td>
            </tr>
            <tr>
                <td>Parts:</td>
                <td>
                <input id="custom_parts_field" type="text"></input>
                </td>
            </tr>
            <tr>
                <td>Labor:</td>
                <td>
                <input id="custom_labor_field" type="text"></input>
                </td>
            </tr>
        </table> 

        <div class="custom_input_table_buttons">
            <button onclick="add_custom_table_data()" class="secondaryButton">Add</button>
            <button class="secondaryButton">Update</button>
            <button class="secondaryButton">Remove</button>
        </div>

    </div>

</div>

<?php wp_enqueue_script( 'my_script', 'path/to/my/script', array( 'wp-api' ) ); ?>
<!-- HOWEVER CHAT IS DONE, JUST ADD ANOTHER COL AND LET IT SPAN THE WHOLE THING -->
<style>

    .price_line {
        display: grid;

    }

    .bump-left {
        margin-left: 10%;
    }

    .workdonetable {
        overflow: auto;
        overflow-x: hidden;
    }

    .main_search_form {
        display:grid;
    }

    /*Primary sections*/
    .holder{ /*grid parent element*/
        top: 80px;
        position: relative;
        display: grid;
        grid-template-columns: 33% 33% 34%;
        grid-template-rows: auto;
        grid-gap: 1em;
        box-sizing: border-box;
        width: 75%;
        margin-left: auto;
        margin-right: auto;
    }
    .generalInfo{
        border: 1px solid black;
        grid-column: 1 / span 2;
        grid-row: 1;
        
    }
    .generalGrid{
        display: grid;
        grid-template-columns: 2;
        grid-template-rows: 3;
    }
    .actionBar{
        display: flex;
    }

    .custom_work_done_area {
        grid-column: 3;
        grid-row: 3 ;
    }

    .pushdownslightly {
        padding-top:20px;
    }

    .submitColor{
        background-color: #47ad58;
    }
    .nameSection{
        grid-column: 1;
        grid-row: 1;
    }
    .emailSection{
        grid-column: 1;
        grid-row: 2;
    }
    .numberSection{
        grid-column: 1;
        grid-row: 3;
    }
    .dateinSection{
        grid-column: 2;
        grid-row: 1;
    }
    .datequoteSection{
        grid-column: 2;
        grid-row: 2;
    }
    .datecompleteSection{
        grid-column: 2;
        grid-row: 3;
    }
    .totalInfo{
        border: 1px solid black;
        grid-column: 2;
        grid-row: 2;
    }

    .subTotal{
        text-indent: 15%;
    }

    .totalButton{
        color: white;
        background: grey;
        text-align: center;
        padding: 10px;
        width: fit-content;
        display: block;
        margin: 0 auto;
    }
    .bikeInfo{
        border: 1px solid black;
        grid-column: 1;
        grid-row: 2 / span 2;
    }
    .centerHeader{
        margin: auto;
        text-align: center;
    }
    .centerText{
        text-align: center;
    }
    .shopLog{
        border: 1px solid black;
        grid-column: 3;
        grid-row: 1;
    }
    .shopText{
        border: 1px solid black;
        background-color: Bisque;
        overflow: auto;
        overflow-x: hidden;
        resize: none;
        box-sizing: border-box;
        width: 98%;
        height: 72%;
        display: block; /*forces to be a block type element. lets me use auto margins*/
        margin-left: auto;
        margin-right: auto;
    }
    .someButtons{
        border: 1px solid black;
        grid-column: 3;
        grid-row: 2;
        height: 50%;
        margin-top: auto;
        margin-bottom: auto;
    }
    .secondaryButton{ /*size needs to be set to fill that container */
        color: white;
        background: grey;
        text-align: center;
        padding: 10px;
        width: 4vw;
    }
    .buttonandfield{
        margin-left: auto;
        margin-right: auto;
    }
    .secondButton{
        color: white;
        background: grey;
        text-align: center;
        padding: 5px 10px;
        width: fit-content;
    }
    .workRequested{
        border: 1px solid black;
        grid-column: 1;
        grid-row: 4;
    }
    .workText{
        overflow: auto;
        overflow-x: hidden;
        width: 95%;
        margin-left: auto;
        margin-right: auto;
    }
    .genericButtons{
        border: 1px solid black;
        grid-column: 2;
        grid-row: 3 / span 2;
        text-align: center;
        display: block;
        grid-template-columns: 2;
        grid-template-rows: 2;
        grid-gap: 1em;
        align-items: center;
    }
    .primarySection{
        grid-column: 1 / span 2;
        grid-row: 1;
    }
    .rebuildSection{
        grid-column: 1;
        grid-row: 2;
    }
    .cableSection{
        grid-column: 2;
        grid-row: 2;
    }
    .mainButton{
        color: white;
        background: grey;
        text-align: center;
        padding: 10px;
        width: fit-content;
    }
    .workDone{
        border: 1px solid black;
        grid-column: 3;
        grid-row: 2 / span 1;
    }
    /*TABLE INFO*/
    .scrollTable{
        overflow: auto;
        table-layout: auto;
        width: 100%;
    }
    .tableHeader{
        background-color: #47ad58;
    }
    table, th, td { /*apply a border to every row*/
        border: 1px solid black;
        border-collapse: collapse;
    }


</style>

<!-- vw and vh are browser related width and height-->