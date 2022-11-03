<?php /*Template Name: checkout*/ ?>
<?php get_header("1"); ?>

<script>

	async function get_ticket_info(ticket_number) {
        var root_url = wp.api.utils.getRootUrl();
        var pedal_API_url = "wp-json/pedal/v1/";
        var url = root_url + pedal_API_url + "ticket?ticket-number=" + ticket_number;

        const resp = await fetch(url, {
            method:"GET",
        })
        .then(resp=>resp.json());

        //console.log(resp);
        return resp;
    }

	async function get_ticket_work_items(ticket_number) {
        var root_url = wp.api.utils.getRootUrl();
        var pedal_API_url = "wp-json/pedal/v1/";
        var url = root_url + pedal_API_url + "work-requested?ticket-number=" + ticket_number;
		console.log(url);
        const resp = await fetch(url, {
            method:"GET",
        })
        .then(resp=>resp.json());

        //console.log(resp);
        return resp;

    }

	async function ticket_search() {
		var ticket_number = document.getElementById("myInput").value;
		//console.log(ticket_number);
		var summary =  await get_ticket_info(ticket_number); //json
		var work_items = await get_ticket_work_items(ticket_number); //json
		//console.log(summary);
		document.getElementById("first").innerHTML = summary['fname'];
		document.getElementById("last").innerHTML = summary['lname'];
		document.getElementById("ticketNum").innerHTML = summary['ticket_number'];
		document.getElementById("date").innerHTML = summary['datetime_complete'];
		for (const work_request of work_items) {
                        console.log(work_request.work_description);
                    // do shit with it
               }
		/*document.getElementById("service").innerHTML = summary['']; //work description
		document.getElementById("parts").innerHTML = 
		document.getElementById("labor").innerHTML = 
		document.getElementById("total").innerHTML = 
*/
	}

	function delete_user() {
		var ticket_number = document.getElementById("myInput").value;
		var userid = "pedal-customer-" + ticket_number;
		var result = wp_delete_user(userid);
		if(result == true) {
			window.location.href = 'http://localhost:8080/pedal-home/';
		}
		else {
			alert("User not deleted");
		}
	}
</script>

<body> 
<!--end navbar-->
<!--main content-->
<div class="body-container">
    <img src="https://urec.sa.ua.edu/wp-content/uploads/sites/12/2016/03/bike-shop-2.jpg" >
	<div class="body">
	  <input type="text" class="myInput" name="myInput" id="myInput"  placeholder="Search By Ticket#...">
	  <button type="search" id="ticket" name = "subject" onclick="ticket_search()">Search</button>
	  
    <div class="Info">
		<h1> Check Out </h1>
		<h2> First Name </h2>
		<p id="first">First Name:</p>
		<h2> Last Name </h2>
		<p id="last">Last Name: </p>
		<h2> Ticket Number </h2>
		<p id="ticketNum">Ticket Number: </p>
		<h2>  Service </h2>
		<p id="service">Service: </p>
		<h2> Date </h2>
		<p id="date">Date: </p>
		<h2> Parts </h2>
		<p id="parts">Parts: </p>
		<h2> Labor </h2>
		<p id="labor">Labor: </p>
		<h2> Total </h2>
		<p id="total">Total: </p>
    </div>
    <div class="buttons">
		<button class="btn" type="submit" onclick="delete_user()">Submit</button>
	</div>

</div>
</body>

<style>
button {
	background-color: #345434;
    color: #fff;
    border: none;
    padding: 10px;
    font-size: 16px;
    border-radius: 5px;
    margin-right: 10px;
    margin-bottom: 10px;
}
</style>

<?php
get_footer();