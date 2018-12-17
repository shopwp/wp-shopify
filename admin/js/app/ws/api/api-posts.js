import {
  post,
  deletion
} from '../ws';

import {
  endpointPosts,
  endpointPostsProducts,
  endpointPostsCollections
} from './api-endpoints';


/*

Gets products

Returns: promise

*/
function deletePosts(data = {}) {
  return deletion( endpointPosts(), data);
}

function setProductPostsRelationships() {
  return post( endpointPostsProducts() );
}

function setCollectionPostsRelationships() {
  return post( endpointPostsCollections() );
}

export {
  deletePosts,
  setProductPostsRelationships,
  setCollectionPostsRelationships
}
