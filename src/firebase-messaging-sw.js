importScripts(
      "../node_modules/firebase/firebase-app.js",
      "../node_modules/firebase/firebase-messaging.js"
)

// Initialize Firebase
// TODO: Replace with your project's customized code snippet
var config = {
  apiKey: "AIzaSyAsrDOAwbeb3H_6fFphi4eCuIJfXVo09ck",
  authDomain: " fir-2a8a3.firebaseapp.com",
  databaseURL: "https://fir-2a8a3.firebaseio.com/",
  storage_bucket: "fir-2a8a3.appspot.com",
  messagingSenderId : "106971848447"
};
firebase.initializeApp(config);

const messaging = firebase.messaging();