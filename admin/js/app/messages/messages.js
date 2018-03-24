import {
  findStatusCodeFirstNum
} from '../utils/utils';


/*

Get message error network

*/
function messageErrorNetwork() {
  return 'Error: The syncing process timed out or ended abruptly. Please check our documentation for a potential solution.';
}


/*

Get message error client

*/
function messageErrorClient() {
  return 'Error: The client died';
}


/*

Get message error

*/
function getMessageError(error) {

  console.log("error: ", error);

  switch ( findStatusCodeFirstNum(error.status) ) {

    case 5:
      return messageErrorNetwork();
      break;

    case 4:
      return messageErrorNetwork();
      break;

    case 3:
      return messageErrorNetwork();
      break;

    default:
      return messageErrorNetwork();
      break;

  }

}


export {
  getMessageError
}
