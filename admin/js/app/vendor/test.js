if (!!window.EventSource) {
  var source = new EventSource('stream.php');

  console.log("pluginsUrl: ", pluginsUrl);


} else {
  // Result to xhr polling :(

}
