#!/usr/bin/python
from sys import argv
import subprocess
import ConfigParser
import json
import urllib2
from os import getenv

print argv[1:]
configDir = getenv("XDG_CONFIG_HOME", "$HOME/.config")

config = ConfigParser.ConfigParser()
config.read(configDir + '/runreg/config.ini')
try:
    project = config.get("General", "project")
except ConfigParser.NoSectionError, ConfigParser.NoOptionError:
    project = "test"

baseUrl = "http://compphys.dragly.org/wp-content/plugins/run-statistics/"
registerUrl = baseUrl + "register-run.php?project=" + project
updateUrl = baseUrl + "update-run.php"

runData = json.load(urllib2.urlopen(registerUrl))
runId = runData["runid"]

returnValue = 0
try:
    returnValue = subprocess.check_call(argv[1:])
except KeyboardInterrupt:
    returnValue = 2
except:
    returnValue = 999

if returnValue == 0:
    updateData = json.load(urllib2.urlopen(updateUrl + "?runid="+ runId + "&state=finished"))
elif returnValue == 2:
    updateData = json.load(urllib2.urlopen(updateUrl + "?runid="+ runId + '&state=stopped'))
else:
    updateData = json.load(urllib2.urlopen(updateUrl + "?runid="+ runId + '&state=failed'))

print "Returned data from server: " + str(updateData)