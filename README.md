[![Build Status](https://travis-ci.org/anpk12/sysinfo.svg?branch=master)](https://travis-ci.org/anpk12/sysinfo)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/anpk12/sysinfo/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/anpk12/sysinfo/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/anpk12/sysinfo/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/anpk12/sysinfo/?branch=master)

This is a simple script for retrieving information
about the host system that the code (typically web
application) is running on, so that it can be
presented on a web page, and/or graphed over time.
Or perhaps it can be used to detect situations
where the system is running low on resources.

It is made as part of an online course in web
development. So I was forced to make this available
on github and packagist. Maybe it can be useful
for someone.

Note that it is intended for Linux host systems
and no effort has been made to make it work for
other host operating systems.

HOW TO USE WITH ANAX-MVC

Clone the Anax-MVC git repository using

$ git clone https://github.com/mosbth/Anax-MVC.git Anax-MVC.git

Then add the following line to the "require" property of
Anax-MVC.git/composer.json:

"anpk12/sysinfo": "dev-master"

The part after the colon is the version requirement
and can optionally be changed to a fixed version such
as "1.0.0".

Validate your composer.json file:

$ composer validate

If it succeeds, update:

$ composer update

Now, to see if it works, you need to integrate
anpk12\sysinfo into a route. We will take the
easiest route possible to create a working demo.

Make a copy of webroot/hello.php, call it
webroot/hello_sysinfo.php . After the line that
starts with "require", create an instance of
Anpk12\Sysinfo\Snapshot:

$sysinfo = new Anpk12\Sysinfo\Snapshot();

This will create a snapshot of the current system
status, which we can then use to generate html,
graphs/images or database entries etc.

The Snapshot class provides easy access to the various
types of system information it collects through
accessors.

But it can also generate a complete html report
using all the information. This is done using the
htmlReport() member function. Add the following
line to hello_sysinfo.php, just after the creation
of the Snapshot instance:

$sysReport = $sysinfo->htmlReport(false)

The boolean argument controls whether or not to
create a short summary (true) or a full report (false).

The variable $sysReport now holds a string of html markup,
which we can tell Anax-MVC to render. Simply change
the title and main variables of the theme:

// Prepare the page content
$app->theme->setVariable('title', "Hello World Sysinfo")
           ->setVariable('main', $sysreport);

Done! Try accessing
Anax-MVC.git/webroot/hello_sysinfo.php with a
web browser. You should see current memory usage etc.

In order to access the system information directly
instead of generating html, use the Snapshot member
functions meminfo(), memTotal(), memAvailable() and
loadavg() .
