// import * as firebase from "firebase";
var CACHE_NAME = 'dependencies-cache';
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

const messaging = firebase.messaging()

messaging.requestPermission().then(function(){

      console.log("NEW PERMISSION");
messaging.getToken()
.then(function(currentToken) {
    if (currentToken) {
      sendTokenToServer(currentToken);
      updateUIForPushEnabled(currentToken);
    } else {
      // Show permission request.
      console.log('No Instance ID token available. Request permission to generate one.');
      // Show permission UI.
      updateUIForPushPermissionRequired();
      setTokenSentToServer(false);
    }
  })
  .catch(function(err) {
    console.log('An error occurred while retrieving token. ', err);
    showToken('Error retrieving Instance ID token. ', err);
    setTokenSentToServer(false);
  });
})


// Files required to make this app work offline
var REQUIRED_FILES = [
  'random-1.png',
  'random-2.png',
  'random-3.png',
  'random-4.png',
  'random-5.png',
  'random-6.png',
  'style.css',
  'index.html',
  'offline.html',
  '/', // Separate URL than index.html!
  'index.js',
  'app.js'
];
if ('serviceWorker' in navigator && 'PushManager' in window) {
      console.log('Service Worker and Push is supported FF');

      navigator.serviceWorker.register('sw.js')
      .then(function(swReg) {
        console.log('Service Worker is registered', swReg);

        swRegistration = swReg;
        initialiseUI()
      })
      .catch(function(error) {
        console.error('Service Worker Error', error);
      });
    } else {
      console.warn('Push messaging is not supported');
      // pushButton.textContent = 'Push Not Supported';
    }
self.addEventListener('install', function(event) {
  // Perform install step:  loading each required file into cache
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(function(cache) {
        // Add all offline dependencies to the cache
        return cache.addAll(REQUIRED_FILES);
      })
      .then(function() {
      	// At this point everything has been cached
        return self.skipWaiting();
      })
  );
});
self.addEventListener('fetch', function(event) {
  event.respondWith(
    caches.match(event.request)
      .then(function(response) {
        // Cache hit - return the response from the cached version
        if (response) {
          return response;
        }

        // Not in cache - return the result from the live server
        // `fetch` is essentially a "fallback"
        return fetch(event.request);
      }
    )
  );
});
self.addEventListener('activate', function(event) {
  // Calling claim() to force a "controllerchange" event on navigator.serviceWorker
  event.waitUntil(self.clients.claim());
});
// console.log(navigator,self)
navigator.serviceWorker.addEventListener('controllerchange', function(event) {
  // Listen for changes in the state of our ServiceWorker
  navigator.serviceWorker.controller.addEventListener('statechange', function() {
    // If the ServiceWorker becomes "activated", let the user know they can go offline!
    if (this.state === 'activated') {
      // Show the "You may now use offline" notification
      document.getElementById('offlineNotification').classList.remove('hidden');
    }
  });
});

//OFFILNE
self.addEventListener('install', function(event) {
  // Put `offline.html` page into cache
  var offlineRequest = new Request('offline.html');
  event.waitUntil(
    fetch(offlineRequest).then(function(response) {
      return caches.open('offline').then(function(cache) {
        return cache.put(offlineRequest, response);
      });
    })
  );
});

function initialiseUI() {
  // Set the initial subscription value
  swRegistration.pushManager.getSubscription()
  .then(function(subscription) {
    isSubscribed = !(subscription === null);

    if (isSubscribed) {
      console.log('User IS subscribed.');
    } else {
      console.log('User is NOT subscribed.');
    }

//     updateBtn();
  });
}
function subscribeUser() {
  applicationServerKey = urlB64ToUint8Array(applicationServerPublicKey);
  swRegistration.pushManager.subscribe({
    userVisibleOnly: true,
    applicationServerKey: applicationServerKey
  })
  .then(function(subscription) {
    console.log('User is subscribed:', subscription);

    updateSubscriptionOnServer(subscription);

    isSubscribed = true;

    updateBtn();
  })
  .catch(function(err) {
    console.log('Failed to subscribe the user: ', err);
    updateBtn();
  });
}

self.addEventListener('push', function(event) {

      var apiPath = '<apiPath>';
      event.waitUntil(registration.pushManager.getSubscription().then(function (subscription){
            response.json().then(function(data) {  
          console.log(data);  
          console.log('Push message received', event);
          if (data.img_url && data.img_url != "") {
            img = data.img_url;
          }
          self.targetUrl = data.target_url;
          console.log(self.targetUrl);
          if (!data.title || data.title == "") {
            console.log("Not firing as title is absent");
            return;
          }
          
            self.registration.showNotification(data.title, {
              body: data.desc,
              icon: img,
              tag: data.city,
              requireInteraction: true
            })
          
        });
            
      }).catch(function(err){
          console.log(err)
      }));
});

function subscribeUser() {
  const applicationServerKey = urlB64ToUint8Array(applicationServerPublicKey);
  swRegistration.pushManager.subscribe({
    userVisibleOnly: true,
    applicationServerKey: applicationServerKey
  })
  .then(function(subscription) {
    console.log('User is subscribed:', subscription);

    updateSubscriptionOnServer(subscription);

    isSubscribed = true;

    updateBtn();
  })
  .catch(function(err) {
    console.log('Failed to subscribe the user: ', err);
    updateBtn();
  });
}
function updateSubscriptionOnServer(subscription) {
  // TODO: Send subscription to application server

  const subscriptionJson = document.querySelector('.js-subscription-json');
  const subscriptionDetails =
    document.querySelector('.js-subscription-details');

  if (subscription) {
    subscriptionJson.textContent = JSON.stringify(subscription);
    subscriptionDetails.classList.remove('is-invisible');
  } else {
    subscriptionDetails.classList.add('is-invisible');
  }
}