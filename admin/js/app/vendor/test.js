if (!!window.EventSource) {
  var source = new EventSource('stream.php');


} else {
  // Result to xhr polling :(

}
