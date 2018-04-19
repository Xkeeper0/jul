# Jul (Dada fork)

Jul is a fork of "Acmlmboard 1.92", an *ancient* system designed by Acmlm (not me). This repository is mostly kept so that improvements and updates are possible by forum members, and to patch any possible vulnerabilities. The actual running code itself includes a few protections that are not in this repository, mostly to prevent and guard against automated attacks.

This specific repository is the Dada fork of Jul, which has the following improvements over [the original](asdf):

* Includes an installer and default configuration
* Has most of the old, unused code removed
* Includes a converter for IPB 3.0 to Jul

## Installing

1. Copy the files to a directory served by e.g. Apache running PHP 7. (Using a lower version should be possible as well, but is untested.)

1. Copy `lib/config.example.php` to `lib/config.php` and add your database configuration.

1. Now run `install.php` from the browser. There's no need to remove the installer after you're done.

1. Your installation of Jul should now be ready and waiting with a single user account active: `admin` with password `admin`. You should change that right away.

## Code notes

The following globals are used:

* `$GLOBALS['jul_base_path']` – base path, e.g. to put in front of `/css/base.css`
* `$GLOBALS['jul_views_path']` – path to the views base directory (same as base path for now), e.g. for `thread.php`, etc.
* `$GLOBALS['jul_settings']` – user configuration (with defaults) from `/lib/config.php`
* `$GLOBALS['jul_sql_settings']` – database connection settings, also from `/lib/config.php`

## Contributing

Pull requests are welcome, and are usually processed in a timely basis.

## License

This code is **not under a known license at this time**. That is not to say it is "free software" — it is just not distributed under any license at the time being. In the future, maybe this will change.
