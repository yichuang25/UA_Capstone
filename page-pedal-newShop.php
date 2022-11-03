<!--@author Thomas Davidson -->
<!DOCTYPE html>
<?php /*Template Name: newShop*/ ?>
<?php get_header("1"); ?>
<div class="holder">

    <div class="generalInfo"> <!-- A -->
        <div class="actionBar"> <!-- all interactions here-->
                <input list="tickets" name="ticket">
                <datalist id="tickets">
                    <option value="Example"> <!-- requires dynamic population-->
                </datalist>
            <input type="submit">
        </div>
        
        <label for="status">Status: </label>
        <select name="status" id="status">
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
        <!-- may want a submit button here? Or just tie together with a general
        update button for the entire page and it's changes-->
        <div class="generalGrid"> <!-- another grid-->
            <div class="nameSection">
                <h3>Name:</h3>
            </div>

            <div class="emailSection">
                <h3>Email:</h3>
            </div>

            <div class="numberSection">
                <h3>Phone Number:</h3>
            </div>

            <div class="dateinSection">
                <h3>Date In:</h3>
            </div>

            <div class="datequoteSection">
                <h3>Date Quoted:</h3>
            </div>

            <div class="datecompleteSection">
                <h3>Date Completed:</h3>
            </div>
        </div>
    </div>

    <div class="totalInfo"> <!-- B -->
        <h2 class="grandTotal">Total: </h2>
        <h3 class="subTotal">Parts: </h3>
        <br>
        <h3 class="subTotal">Labor: </h3>
        <!-- Need button for generating total-->
        <button class="totalButton">Generate Total</button>
    </div>

    <div class="bikeInfo"> <!-- C -->
        <!-- Not sure how to do the predone checkbox-->
        <h3>Quote under $30: X </h3> <!-- need to have this say yes or no dependent on data-->
        <h3>Bike Make:</h3> <!-- fetch it-->
        <h3>Bike Model:</h3>
        <h3>Bike Color:</h3>
        <h3 class="centerHeader">Bike Other</h3>
        <p class="centerText">Info about bike</p>
        <h3 class="centerHeader">Customer Comments</h3>
        <p class="centerText">Area for displaying that info from the data</p>
    </div>

    <div class="shopLog"> <!-- D -->
        <h3 class="shoplogHeader">Shop Log</h3>
        <textarea class="shopText" id="check" name="log" value="Input"></textarea>
    </div>

    <div class="someButtons"> <!-- E WANT THESE TO BE A BIG SQUARE REALLY--> 
        <button class="secondaryButton">Desc</button>
        <button class="secondaryButton">Parts</button>
        <button class="secondaryButton">Labor</button>
        <button class="secondaryButton">Add</button>
        <button class="secondaryButton">Update</button>
        <button class="secondaryButton">Remove</button>
    </div>

    <div class="workRequested"> <!-- F not sure what I should put here to hold the results-->
        <h3 class="workRequestedHeader">Work Requested</h3>
        <p class="workText">
            bruruh
        </p>
    </div>

    <div class="genericButtons"> <!-- G -->
        <div class="primarySection">
            <button class="mainButton">Flat Fix</button>
            <button class="mainButton">Safety Check</button>
            <button class="mainButton">Bike Build</button>
            <button class="mainButton">Brake Tune</button>
            <button class="mainButton">Wheel True</button>
            <button class="mainButton">Derailleur Adjust</button>
            <button class="mainButton">Front Hub Adjust</button>
            <button class="mainButton">Rear Hub Analyst</button>
            <button class="mainButton">Headset Adjust</button>
            <button class="mainButton">Brake Bleed</button>
            <button class="mainButton">BB Adjust</button>
        </div>

        <div class="rebuildSection">
            <button class="mainButton">BB Rebuild</button>
            <button class="mainButton">Front Hub Rebuild</button>
            <button class="mainButton">Rear Hub Rebuild</button>
            <button class="mainButton">Headset Rebuild</button>
        </div>

        <div class="cableSection">
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

    <div class="workDone"> <!-- H -->
        <table class="scrollTable">
            <caption>Work Done</caption> <!-- Adds a header, may want to just get a grid element header instead-->
            <thead class="fixedHeader">
	            <tr class="tableHeader"> <!-- the first row of the table used for header -->
		            <th class="firstCol">Desc.</th>
		            <th class="secondCol">Parts</th>
		            <th class="thirdCol">Labor</th>
	            </tr>
                <tr> <!-- the first row of the table used for header -->
		            <td>we did a thing here</td>
		            <td>this much monry</td>
		            <td>this much monry</td>
	            </tr>
                <tr> <!-- the first row of the table used for header -->
		            <td>we did a thing here</td>
		            <td>this much monry</td>
		            <td>this much monry</td>
	            </tr>
                <tr> <!-- the first row of the table used for header -->
		            <td>we did a thing here</td>
		            <td>this much money</td>
		            <td>this much money</td>
	            </tr>
            </thead>
            <tbody class="tableEntries">
                <!-- Thing to populate the table goes here -->
            </tbody>
        </table>
    </div>
</div>

<!-- HOWEVER CHAT IS DONE, JUST ADD ANOTHER COL AND LET IT SPAN THE WHOLE THING -->
<style>
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
        width: 31%;
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
        grid-row: 3 / span 2;
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