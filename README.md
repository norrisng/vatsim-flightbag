# vatsim-flightbag

A collection of simple tools for flight simulation, especially for flying on the VATSIM network.

A deployed, publicly accessible version [can be found here](http://elato.duckdns.org:4/fltsim_resource.html). Feel free to deploy it on your own server! It'll reduce the load on mine :)

If you're cloning it for yourself, fltsim_resource.html is the "main menu", so please access the entire application from there.

##Features

###atc.php

Displays online ATC stations on the VATSIM network, as well as some basic information about each.

####Callsign display

The callsign is parsed from the controller info, and must be placed at the beginning of the message in the format "[word] [position type]" (e.g. "Eurocontrol West", "Los Angeles Center" [see below]). As of this writing, the word "callsign" may precede the actual callsign, and case does not matter. 

If the controller does not provide any information about his/her station, does not include the callsign, or controls a city with two words (with the exception of select cities), the callsign will not be displayed.

###config.php

Allows the user to download a pre-generated OFP (exported to PDF by another program, such as PFPX or simBrief) to the server.

###wx.php

A simple METAR tool that displays VATSIM airport weather information at a glance.

*Disclaimer: VATSIM weather may differ from real world weather!*
