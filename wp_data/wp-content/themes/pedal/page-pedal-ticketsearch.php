<?php /*Template Name: ticketSearch*/ ?>
<?php get_header("1"); ?>
<script>
    // stretch goal
    // document.querySelector("#query_input_field").addEventListener('keypress', function(e) {
    //     if (e.key === "Enter") {
    //         search_tickets();
    //     }
    // })

    function format_date_fields(ticket) {
        ticket['converted_date_in'] = ticket['datetime_in'] ? ticket['datetime_in'].date.slice(0,10) : "-";
        ticket['converted_date_quote'] = ticket['datetime_quote'] ? ticket['datetime_quote'].date.slice(0,10) : "-";
        ticket['converted_date_complete'] = ticket['datetime_out'] ? ticket['datetime_out'].date.slice(0,10) : "-";
        return ticket;
    }

    function add_ticket_to_table(ticket) {

        ticket = format_date_fields(ticket);
        console.log("Ticket is", ticket);
        let table = document.getElementById('ticket_table');
        let newrow = table.insertRow(-1);

        var cell_index = 0;

        var relevant_fields = ["ticket_number", "fname", "lname", "converted_date_in", "converted_date_quote", "converted_date_complete", "ticket_status"];

        for (const field of relevant_fields) {
            console.log(field);
            newrow.insertCell(cell_index++)
                .appendChild(document.createTextNode(ticket[field]));
        }

    }

    function clear_table() {
        let table = document.getElementById('ticket_table');

        for (var i = table.rows.length-1; i > 0; i--) {
            table.deleteRow(table.rows.length-1);
        }

    }

    function update_table_with_tickets(tickets) {
        clear_table();

        for (const ticket in tickets) {
            console.log("Adding to table:", ticket);
            add_ticket_to_table(tickets[ticket]);
        }
    }

    async function search_tickets() {
        let table = document.getElementById('ticket_table');
        console.log("Table is",table);


        var criteria = parseInt(document.getElementById("search_on_selection").value);

        var search_str = document.getElementById("query_input_field").value;

        var query_str = "?";


        switch (criteria) {

            case (0):
                console.log("ALL");
                break;
            
            case (1):
                console.log("TICKET NUMBER");
                query_str+="fname=";
                break;

            case (2):
                console.log("TICKET NUMBER");
                query_str+="lname=";
                break;

            case (3):
                console.log("TICKET NUMBER");
                query_str+="cwid=";
                break;

            case (4):
                console.log("TICKET NUMBER");
                query_str+="status=";
                break;
        };

        query_str+=search_str;


        var root_url = wp.api.utils.getRootUrl();
        var pedal_API_url = "wp-json/pedal/v1/";
        var url = root_url + pedal_API_url + "ticket-summaries"+query_str;

        console.log(url);

        const resp = await fetch(url, {
            method: "GET"
        }).then(resp=>resp.json());


        update_table_with_tickets(resp);

    } 


</script>
<body> 
<!--end navbar-->
<!--main content-->
<div class="body-container">
    <img src="https://urec.sa.ua.edu/wp-content/uploads/sites/12/2016/03/bike-shop-2.jpg">
    <div class="body">
      <input type="text" class="search" id="query_input_field"  placeholder="Search...">
      <select name="field" id="search_on_selection" default=1>
        <option value="0">All</option>
        <option value="1">First Name</option>
        <option value="2">Last Name</option>
        <option value="3">CWID</option>
        <option value="4">Ticket Status</option>
      </select>
      <button type="search" onclick="search_tickets()">Search</button>
      <script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>
		<table class="searchable sortable" id="ticket_table">
        <thead>
          <tr class="header">
            <th>Ticket Number</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Date In</th>
            <th>Date Quote</th>
            <th>Date Complete</th>
            <th>Ticket Status</th>
          </tr>
        </thead>
        
          <tr>
            <td>000001</td>
            <td>Jane</td>
            <td>Doe</td>
            <td>2022-01-01</td>
            <td>2022-02-01</td>
            <td>2022-03-01</td>
            <td>Complete</td>
          </tr>
          <tr>
            <td>000002</td>
            <td>John</td>
            <td>Doe</td>
            <td>2022-03-01</td>
            <td>2022-03-05</td>
            <td>2022-03-20</td>
            <td>Complete</td>
          </tr>
      </table>





    </div>
</div>
</body>


<style>
  th {
    background-color: #345434;
    color: white;
  }
  .body {
    padding-top: 20px;
  }
  table{
    margin-top: auto;
  }   
  button{
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