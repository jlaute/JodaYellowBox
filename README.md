# Yellow Box Shopware Plugin (work in progress)

[![Build Status](https://travis-ci.org/jlaute/JodaYellowBox.svg?branch=master)](https://travis-ci.org/jlaute/JodaYellowBox)

**This Plugin should only be installed on a staging server! Do not install it in a live environment!**

It adds a yellow box to the frontend. More informations will follow.

The main intention behind this plugin is to simplify the communication between agency and customer. 

## Requirements

- PHP >= 7.0
- Shopware >= 5.2.0

## Project Management Tools
- Redmine

## Console Commands
- [joda:release:add](###joda:release:add)
- [joda:sync:releases](###joda:sync:releases)
- [joda:sync:tickets](###joda:sync:tickets)
- [joda:ticket:add](###joda:ticket:add)
- [joda:ticket:remove](###joda:ticket:remove)

### joda:release:add
Add a new release with optional unlimited tickets. This command should only be used when no external Projectmanagement Tool is used.

**Example**

`bin/console joda:release:add "v1.0.0" --tickets "Ticket name 1" --tickets "Ticket name 2"`

### joda:sync:releases
Sync releases from remote Projectmanagement Tool to the shop. No Parameters are required, but you should define the external project ID in plugin config.

**Example**

`bin/console joda:sync:releases`

### joda:sync:tickets
Sync tickets from remote Projectmanagement Tool to the shop. Optional parameter is `--release`, to define a specific release name to pull the tickets for. By default it will sync tickets for the currently active release.

**Example**

`bin/console joda:sync:tickets`

### joda:ticket:add
Adds a ticket to the YellowBox.

**Example**
`bin/console joda:ticket:add "Ticket 123" --description "My Ticket description"`

### joda:ticket:remove
Removes a ticket by name from the YellowBox.

**Example**
`bin/console joda:ticket:remove "Ticket 213"`

## Installation

- Download latest release
- Extract the zip file in `shopware_folder/custom/plugins/`


## Contributing

Feel free to fork and send pull requests!


## Licence

This project uses the [MIT License](LICENCE.md).
