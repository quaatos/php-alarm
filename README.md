# php-alarm
A CLI alarm made in PHP

Currently only Linux supported.

---
How i configured this alarm:<br>

I run the script on my raspberry pi 4B which is connected to a radio with an aux cable.<br>
Then i'll connect to my pi with RDP, (Xrdp installed on the pi) then i'll start the script from there -> stop the RDP session -> go to sleep.<br>
The most fun way to wake up is to put your own custom sounds in the 'sounds/' directory, songs like the samsung alarm drip version.<br>
---
You can add as many alarms in the json file as you want, there is no limit. But i do recoment to keep it clean because it will be a mess if you configure to much alarms.
