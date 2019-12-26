# CS:GO Sessions

Session based gameplay statistics for CS:GO servers (no SourceMod plugin/extension needed)

## How it works

This app uses [CS:GO Pipeline](https://github.com/HugoJF/csgo-pipeline) in order to receive UDP logs from CS:GO servers pre-processed into JSON form via a Redis list.

A job is dispatched every minute to process pending events:

  - `PlayerConnected`: starts a new session that will be used to hold information.
  - `PlayerDisconnected`: closes an existing session and builds a JSON object containing every information collected during the session.
  - `PlayerDamage`: collects damage done, damage received, death, kills information.
  
During a live session, statistics are collected and stored into Redis (for performance reasons) and after it's closed a JSON object is stored as a serialized JSON object into the main SQL database.

Data collected and stored per session are called `metrics`, which hold maximum amount of information with minimal redundancy or repetition (amount of damage done with a certain weapon and hitgroup). Metrics can then be processed into `statistics`, which are more user-friendly information (total damage done or total damage done with a certain weapon).

This is done to avoid holding redundant statistics (which would need more storage) and to allow other kinds of statistics to be processed after events were collected.

## Screenshots

#### Search 

<p align="center">
  <img src="https://imgur.com/EDNx8yW.png">
</p>

#### Search results

<p align="center">
  <img src="https://imgur.com/r19V254.png">
</p>

#### Statistics

<p align="center">
  <img src="https://imgur.com/V0qMXvH.png">
</p>

## Requirements
  - PHP 7.x
  - MySQL/MariaDB
  - Redis
  - Installation of [CS:GO Pipeline](https://github.com/HugoJF/csgo-pipeline)
  - CS:GO server

## Installation
  - Deploy this Laravel app;
  - If you don't have [CS:GO Pipeline](https://github.com/HugoJF/csgo-pipeline), deploy it;
  - Add a list in your CS:GO Pipeline installation with the key `sessions` (set maximum size to at least 5k items);
  - Setup a cronjob for the scheduler;
  - Add your server address into the `servers` table.
  
## Configuration
  There are no configuration options yet.

## TODO:
  - Finish it and complete documentation.
