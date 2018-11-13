import { Notice } from '@wordpress/components';
import React from 'react';
import ReactDOM from 'react-dom';

/*

<ProductsHeading />

*/
class AdminNotice extends React.Component {

  onremov = state => {
    
  }

  render() {

    return (
			<Notice status={ this.props.type } onRemove={this.onremov}> { this.props.message } </Notice>
    );

  }

}


/*

Init <AdminNotice />

*/
function showAdminNoticeNew(type, message) {

  ReactDOM.render(
    <AdminNotice
      type={type}
      message={message}
    />,
    document.getElementById("wps-admin-notices")
  );

  jQuery('#wps-admin-notices').removeClass('wps-is-hidden');
}

export {
  showAdminNoticeNew
}
