<?php /*Template Name: employeePage*/ ?>
<?php get_header("1"); ?>
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
            <input type="text"></input>
        </div>
        <div class="new-ticket-form-field">
            <label><b>Last Name</b></label>
            <input type="text"></input>
        </div>
        <div class="new-ticket-form-field">
            <label><b>CWID</b></label>
            <input type="text"></input>
        </div>
        <div class="new-ticket-form-field">
            <label><b>Phone Number</b></label>
            <input type="text"></input>
        </div>

        <div class="new-ticket-form-field">
            <label><b>Email Address</b></label>
            <input type="text"></input>
        </div>
    </div>

    <div class="new-ticket-form-bike-info">
        <h2>Bike Info</h2>
        <div class="new-ticket-form-field">
            <label><b>Make</b></label>
            <input type="text"></input>
        </div>
        <div class="new-ticket-form-field">
            <label><b>Model</b></label>
            <input type="text"></input>
        </div>
        <div class="new-ticket-form-field">
            <label><b>Color</b></label>
            <input type="text"></input>
        </div>
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
                <td>Wheel True</td>
                <td>         
                    <input type='checkbox'>Front</input>
                    <input type='checkbox'>Rear</input>
                </td>
            </tr>
            <tr>
                <td>Tune Up</td>
                <td>         
                    <input type='checkbox'>Major</input>
                    <input type='checkbox'>Minor</input>
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

    <div class="problem-description">
        <h2>Problem Description</h2>

        <textarea type="text"></textarea>
    </div>      

    <div class="agreement">
        <input type="checkbox"></input>
        <a href="#">Agreement</a>
    </div>

    <div>
        <button class="submit-btn">Submit</button>
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

    input[type=text], input[type=password] {
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