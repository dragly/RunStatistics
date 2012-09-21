#!/usr/bin/python
from sys import argv
import subprocess
import ConfigParser
import json
import urllib2
from os import getenv

# UPDATE THESE VARIABLES
baseUrl = "http://compphys.dragly.org"
# END UPDATE VARIABLES

configDir = getenv("XDG_CONFIG_HOME", "$HOME/.config")

config = ConfigParser.ConfigParser()
config.read(configDir + '/runreg/config.ini')
try:
    project = config.get("General", "project")
except ConfigParser.NoSectionError, ConfigParser.NoOptionError:
    project = "test"

pluginUrl = baseUrl + "/wp-content/plugins/run-statistics/"
registerUrl = pluginUrl + "register-run.php?project=" + project
updateUrl = pluginUrl + "update-run.php"

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