/*

Bootstrapping the build

*/

import js from "./gulp/tasks/js";

import cssAdmin from "./gulp/tasks/css-admin";
import cssPublic from "./gulp/tasks/css-public";
import cssPublicCore from "./gulp/tasks/css-public-core";
import cssPublicGrid from "./gulp/tasks/css-public-grid";

import imagesPublic from "./gulp/tasks/images-public";
import imagesAdmin from "./gulp/tasks/images-admin";

import server from "./gulp/tasks/server";
import watch from "./gulp/tasks/watch";


import cleanTmp from "./gulp/tasks/clean-tmp";
import cleanFreeRepo from "./gulp/tasks/clean-free-repo";

import build from "./gulp/tasks/build";

// Clean builds
import cleanFree from "./gulp/tasks/clean-free";
import cleanPro from "./gulp/tasks/clean-pro";
import cleanBuilds from "./gulp/tasks/clean-builds";

import init from "./gulp/tasks/init";
