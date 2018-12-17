import axios from 'axios';


const nonce_api = () => WP_Shopify.nonce_api;


function getRestErrorContents(error) {

  return {
    statusCode: error.status,
    message: error.data.message,
    action_name: error.data.code
  }

}


/*

Generic GET method for communicating with the WP Shopify REST API

endpoint - string representing the API enpoint

*/
function get(endpoint) {
  return request('get', endpoint);
}


/*

Generic POST method for communicating with the WP Shopify REST API

endpoint - string representing the API enpoint
data - the POST data object

*/
function post(endpoint, data = {}) {
  return request('post', endpoint, data);
}


/*

Generic DELETE method for communicating with the WP Shopify REST API

endpoint - string representing the API enpoint
data - the DELETE data object

*/
function deletion(endpoint, data = {}) {
  return request('delete', endpoint, data);
}


/*

Main request function

*/
function request(method, endpoint, data = {}) {

  return new Promise( (resolve, reject) => {

    axios({
      method: method,
      url: endpoint,
      data: data,
      headers: {
        'X-WP-Nonce': nonce_api()
      }
    })
    .then( response => resolve(response) )
    .catch ( error => reject( getRestErrorContents(error.response) ) );

  });

}


export {
  get,
  post,
  deletion
}
