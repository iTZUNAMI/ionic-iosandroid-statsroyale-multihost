# The Mad King (stats royale) 

_Note: the Mad King requires Ionic CLI 3

<img src="/resources/android/icon/drawable-xhdpi-icon.png" />

The Mad King : Stats and Chest tracker for Clash Royale 

Check your next Chest!
Wondering when that Legendary or Magical chest is coming? We got you covered

Features:
Check what chest is coming to you (Yes.. they are not random!)
Add any player to your app:
Check your stats, your clan stats or your favorite Youtube player
Share your stats with your friends


Youtube PRO players of Clash Royale added by default
nickatnyte
MOLT
Chief Pat


Disclamer (also added on the app under Help tab):
"This content is not affiliated with, endorsed, sponsored, or specifically approved by Supercell and Supercell is not responsible for it. For more information see Supercell’s Fan Content Policy: www.supercell.com/fan-content-policy."

This is a fan guide app with the use of some Supercell assets based on the popular game Clash Royale. Permitted Fan Content normally includes for example non-commercial fan-generated online guides and guide apps, fan meetups, fan pages and gameplay videos as long as they follow this Policy.
This fan app follow all the rules on this Policy for the assets of Supercell' Clash Royale.

Note:
This is not a WebView app of the fan created website statsroyale.com.

## Screenshots (Android)
<img src="/resources/build android/en1.png" width="450px"/>
<img src="/resources/build android/en2.png" width="450px"/>
<img src="/resources/build android/en3.png" width="450px"/>
<img src="/resources/build android/en4.png" width="450px"/>
<img src="/resources/build android/en5.png" width="450px"/>

## Screenshots (iOS)

<img src="/resources/build ios/Simulator Screen Shot 19 ago 2017, 18.10.52.png" width="450px"/>
<img src="/resources/build ios/Simulator Screen Shot 19 ago 2017, 18.11.13.png" width="450px"/>
<img src="/resources/build ios/Simulator Screen Shot 19 ago 2017, 18.11.26.png" width="450px"/>
<img src="/resources/build ios/Simulator Screen Shot 19 ago 2017, 18.11.31.png" width="450px"/>
<img src="/resources/build ios/Simulator Screen Shot 19 ago 2017, 18.11.44.png" width="450px"/>

## Screenshots (Tablet)

<img src="/resources/build ios/tablet/Simulator Screen Shot 19 ago 2017, 18.14.05.png" width="500px"/>

## i18n

The Mad King comes with internationalization (i18n) out of the box with [ngx-translate](https://github.com/ngx-translate/core). This makes it easy to change the text used in the app by modifying only one file. 

We have added the following languages:

Spanish

German

French

Russian

Portuguese

## www

Added a simple mobile friendly website/landing page for ios and andorid download page

## crawling method 1 : simple

v1: all stats are processed and taken from the statsroyale website and saved to our db. all client request check our db and if not updated or not present there will be a server request from our main server.

problem: 100k users and too much request from our main server -> crawling by our IP has been blocked
solution : method 2

## crawling method 2 : hydra

added a multiple crawling from different servers. all result are sent to our main db. all clients request all info from main server as well, but we have spliiter to about 8-10 different IP servers all request.

problem : too much request as method 1, so servers has been banned after 1 month 
final solution : method 3

## crawling method 3: 3-way

to prevent IP blocking i have changed the client request. Now the client (android/ios app) act as crawler. All infos are than sent and processed by our server and again sent back to client. With this 3-way request all request have different and unique IP and there aren't IP blocked request. Very strong method.





