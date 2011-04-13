# InfoScreen

The purpose of this project is to give more visibility to public transport around your office. Using an adminscreen you can specify your location and the desired stations you want to display on the screen which may be placed at the entrance.

This is a project started by the Flat Turtle bvba. It's the back-end for our mobility-screen products. Flut Turtle is member of the iRail NPO. The copyright of this software is maintained by the non profit organisation and licensed under AGPL. We do not however include the designs which contain company logos and styles from the companies we work for. You are free however to make this code better and/or to use it for your own project (as long as you respect the AGPL).


# Development

We like design patterns and we Object Oriented Programming. Nonetheless we believe one should stay pragmatic. Therefore we always work according to an Model-View-Controller design pattern. The mod rewrite plugin for apache will take care of the url handling: a request to the root of your instance will trigger the controller. The controller will ask the model which data it should retrieve according to the given GET-parameters. Thereafter the view will generate the output according to the templates directory.

# Designs

We include one default template according the the iRail NPO style. It is licensed under the Creative Commons By Sa license. This means you are free to alter it, change it or remix it, as long as you add the iRail NPO as original creator and share it under the same license.

# iRail

More information can be found on [Project iRail](http://project.irail.be/).

# License

This branch has been written from scratch and therefor relicensed to: AGPL

(c) 2011 iRail vzw/asbl

# Installation

Adjust config.php to your needs and copy the entire repository to your server.

You need:

 * PHP5
 * (url rewrite mod)
 * http request
 * a database

# Some interesting links:

 * Source: <http://github.com/iRail/iRail>
 * Mailing: <http://list.irail.be/>
 * Trac: <http://project.irail.be/>
 * API: <http://api.irail.be/>
 * BeTrains: <http://betrains.mobi/>
