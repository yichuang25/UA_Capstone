async function get_number_of_tickets() {
    console.log("Testing");
    var root_url = wp.api.utils.getRootUrl();
    var pedal_API_url = "wp-json/pedal/v1/";
    var url = root_url + pedal_API_url + "tickets/count";

    const resp = await fetch(url, {
        method: "GET"
    }).then(resp=>resp.json());

    console.log(resp);
    return resp;
}

function test_utils() {
    console.log("Utils online");
    return true;
}

console.log("This file executed, somehow");
