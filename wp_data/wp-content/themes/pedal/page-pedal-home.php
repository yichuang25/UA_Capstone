<!--@author Thomas Davidson -->
<?php /*Template Name: homePage*/ ?>
<?php get_header("1"); ?>

<script>
  function errorDisplay(){
        confirm("Error: Invalid Ticket Number")
    }

  async function checkTicket(ticketNum) {
        var root_url = wp.api.utils.getRootUrl();
        var pedal_API_url = "wp-json/pedal/v1/";
        var url = root_url + pedal_API_url + "ticket?ticket-number=" + ticketNum;
        console.log(root_url);

        targetWebpage = root_url + "/pedal-customer-" + ticketNum;
       
        const resp = await fetch(url ,{
          method: "GET"
        }).then(resp=>resp.json()).catch((error)=>{
          errorDisplay();
        });

        if (resp){
        console.log("safsdf");
        window.location.href = targetWebpage;
        }
         return 1;
    }

  //just get the ticket number. On click event. If valid in the database, load the correct password protected customer page
  function logIn(){
    var ticketNum = document.getElementById("user").value;
    //have ticket number. Check exists.
    checkTicket(ticketNum);
    //window.location.href = "http://www.w3schools.com";
    console.log(":D");
  }
</script>


<body>
<div class="main_block">
    <body style="background-color:#fffff5">
        <title>Pedal | Home</title>
        <h1>Home</h1>
        <p class="toptext">Outdoor Recreation provides a variety of bike repair services to students. Just bring us your bike, receive your password, and check the status of your bike at any time through this site. </p>
        
        <img src="https://urec.sa.ua.edu/wp-content/uploads/sites/12/2018/10/BikeShop-e1539369397191.jpg"/>
        
        <h2 class="has-text-align-center" id="have-a-ticket-login-below">Have a ticket? Login below!</h1>
        <div class="container" style="background-color: #fffff5">
            <label for="uname"><b>Ticket Number</b></label>
            <input type="number" placeholder="Enter Ticket Number" id="user" name="uname" required="">
            <button onclick="logIn()">Login</button>
        </div>

    
    <p class="has-text-align-center has-small-font-size">Visit <a href=https://urec.sa.ua.edu/outdoor-recreation-news/bike-shop> the bike shop website</a> for an overview of pricing.</p>
    </body>
    </div>

<style>
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

input[type=number], input[type=password] {
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

<?php get_footer(); ?>